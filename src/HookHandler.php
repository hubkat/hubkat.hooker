<?php
// @codingStandardsIgnoreFile

namespace Hubkat\Hooker;

use Hubkat\Event\Event;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HookHandler
{
    protected $hooks;

    protected $resolver;

    protected $responder = 'Hubkat\Hooker\Responder';

    public function __construct(Hooks $hooks, callable $resolver = null)
    {
        $this->hooks = $hooks;
        $this->resolver = $resolver;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $event = $request->getParsedBody();

        if (! $event instanceof Event) {
            throw new \InvalidArgumentException(
                'Parsed body must be instance of Event'
            );
        }

        if (! $this->hooks->has($event->name)) {
            return $this->noHook($response);
        }

        $response = $this->handle(
            $event, $request, $response
        );

        return $next($request, $response);
    }

    protected function handle(
        Event $event,
        Request $request,
        Response $response
    ) {
        $responder = $this->resolve($this->responder);

        if (! $responder) {
            throw new \Exception('Could not resolve responder for action.');
        }

        $hook = $this->resolve($this->hooks->get($event->name));
        $payload = $hook($event);

        return $responder($request, $response, $payload);
    }

    protected function noHook(Response $response)
    {
        $response = $response->withStatus(404);
        $response->getBody()->write(
            'no hook configured for this event'
        );
        return $response;
    }

    protected function resolve($spec)
    {
        if (! $spec) {
            return null;
        }
        if (! $this->resolver) {
            return $spec;
        }
        return call_user_func($this->resolver, $spec);
    }
}
