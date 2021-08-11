<?php
declare(strict_types=1);

use ChatApi\HttpRequest;
use ChatApi\HttpResponder;
use ChatApi\MysqlConnection;
use ChatApi\RequestHandler\LoginRequestHandler;
use ChatApi\Session;
use ChatApi\UserRepository;

require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$userRepository = new UserRepository($pdo);

$request = new HttpRequest();
$session = new Session();
$handler = new LoginRequestHandler($session, $userRepository);
(new HttpResponder())->respond(
    $handler->handle($request)
);