<?php declare(strict_types=1);

use ChatApi\HttpRequest;
use ChatApi\HttpResponder;
use ChatApi\RequestHandler\LogoutRequestHandler;
use ChatApi\Session;

require_once __DIR__ . '/../../vendor/autoload.php';


$request = new HttpRequest();
$session = new Session();
$handler = new LogoutRequestHandler($session);
(new HttpResponder())->respond(
    $handler->handle($request)
);