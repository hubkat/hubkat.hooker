<?php
// @codingStandardsIgnoreFile

namespace Hubkat\Hooker;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Relay\RelayBuilder;
use Hubkat\Event\EventValidator;

class Config extends ContainerConfig
{
    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function define(Container $di)
    {
        $di->values['SECRET'] = getenv('SECRET');

        $di->set('hubkat/hooker:hooker', $di->lazyNew(Hooker::class));
        $di->set('hubkat/hooker:resolver', $di->lazyNew(Resolver::class));
        $di->set('hubkat/hooker:hooks', $di->lazyNew(Hooks::class));

        $di->params[RelayBuilder::class]['resolver'] = $di->lazyGet('hubkat/hooker:resolver');

        $di->params[Hooker::class]['hooks'] = $di->lazyGet('hubkat/hooker:hooks');
        $di->params[Hooker::class]['relayBuilder'] = $di->lazyNew(RelayBuilder::class);

        $di->params[HookHandler::class]['resolver'] = $di->lazyGet('hubkat/hooker:resolver');
        $di->params[HookHandler::class]['hooks'] = $di->lazyGet('hubkat/hooker:hooks');

        $di->params[EventValidator::class]['secret'] = $di->lazyValue('SECRET');

        $di->params[Resolver::class]['injectionFactory'] = $di->getInjectionFactory();
    }
}
