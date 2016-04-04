# hubkat.hooker
Consume github webhooks
```php
<?php

use josegonzalez\Dotenv\Loader as Dotenv;

use Hubkat\Hooker\Boot;
use Hubkat\Hooker\HookHandler;
use Hubkat\Event\EventParser;
use Hubkat\Event\EventValidator;
use Hubkat\Event\Event;
use Hubkat\EventInterface\EventType;

use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;
use Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;

require '../vendor/autoload.php';

Dotenv::load(
    [
    'filepath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env',
    'toEnv' => true,
    'putenv' => true
    ]
);

$boot = new Boot();
$hooker = $boot->hooker();

$hooker->middle(new ResponseSender());
$hooker->middle(new ExceptionHandler(new Response()));

$hooker->middle(EventParser::class);
$hooker->middle(EventValidator::class);
$hooker->middle(HookHandler::class);

$hooker->hook(EventType::EVENT_ISSUE, 'My\Issue\Handler');
$hooker->hook(EventType::EVENT_DEPLOYMENT, 'My\Deployment\Handler');

$hooker->run(ServerRequestFactory::fromGlobals(), new Response());
```
