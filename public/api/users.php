<?php declare(strict_types=1);

use ChatApi\HttpResponder;
use ChatApi\MysqlConnection;
use ChatApi\Session;
use ChatApi\UserRepository;
use ChatApi\UserRequestHandler;

require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$userRepository = new UserRepository($pdo);

$request = new \ChatApi\HttpRequest();
$session = new Session();
$handler = new UserRequestHandler($session, $userRepository);
(new HttpResponder())->respond(
    $handler->handle($request)
);






