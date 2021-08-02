<?php


require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = \ChatApi\MysqlConnection::fromConfig($settings);


$userRepository = new \ChatApi\UserRepository($pdo);

$userRepository->createNewUser('tester@tag24.de', 'test123.');