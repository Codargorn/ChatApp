<?php declare(strict_types=1);

use ChatApi\UserRepository;
use Fig\Http\Message\StatusCodeInterface;
use ChatApi\HttpRequest;
use ChatApi\HttpResponder;
use ChatApi\MysqlConnection;
use ChatApi\Session;

require_once __DIR__ . '/../../vendor/autoload.php';

$session = new Session();

if ($session->get('user_id') === null) {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_UNAUTHORIZED,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'not logged in'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}

$request = new HttpRequest();
$status = StatusCodeInterface::STATUS_OK;

$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$UserRepository = new UserRepository($pdo);

$loggedInUserId = (int)$request->getQueryParams()['logged_in_user_id'];

if($request->getMethod() === 'GET'){
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                $UserRepository->getUsers($loggedInUserId),
                JSON_THROW_ON_ERROR
            )
        )
    );
}


if ($request->getMethod() !== 'GET') {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'method not allowed'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}






