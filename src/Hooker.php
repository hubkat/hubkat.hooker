<?php
// @codingStandardsIgnoreFile

namespace Hubkat\Hooker;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Relay\RelayBuilder;

class Hooker
{
    protected $hooks;

    protected $middle = [];

    protected $relayBuilder;

    public function __construct(
        Hooks $hooks,
        RelayBuilder $relayBuilder
    ) {
        $this->hooks = $hooks;
        $this->relayBuilder = $relayBuilder;
    }

    public function hook($event, $action = null)
    {
        $this->hooks->hook($event, $action);
        return $this;
    }

    public function middle($spec)
    {
        $this->middle[] = $spec;
    }

    public function run(Request $request, Response $response)
    {
        $relay = $this->relayBuilder->newInstance($this->middle);
        return $relay($request, $response);
    }
}
