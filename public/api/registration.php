<?php declare(strict_types=1);

use ChatApi\HttpResponder;
use ChatApi\MysqlConnection;
use ChatApi\UserRepository;

require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$userRepository = new UserRepository($pdo);

$request = new \ChatApi\HttpRequest();
$handler = new \ChatApi\RegistrationRequestHandler($userRepository);
(new HttpResponder())->respond(
    $handler->handle($request)
);
