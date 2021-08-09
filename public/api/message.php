<?php declare(strict_types=1);

use ChatApi\HttpResponder;
use ChatApi\MessageRequestHandler;
use ChatApi\MessagesRepository;
use ChatApi\MysqlConnection;
use ChatApi\Session;

require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$messagesRepository = new MessagesRepository($pdo);

$request = new \ChatApi\HttpRequest();
$session = new Session();
$handler = new MessageRequestHandler($session,$messagesRepository);
(new HttpResponder())->respond(
    $handler->handle($request)
);