<?php declare(strict_types=1);

use ChatApi\HttpResponder;

require_once __DIR__ . '/../../vendor/autoload.php';


$request = new \ChatApi\HttpRequest();
$handler = new \ChatApi\RegistrationRequestHandler();
(new HttpResponder())->respond(
    $handler->handle($request)
);
