<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use ChatApi\HttpResponder;
use ChatApi\MysqlConnection;
use ChatApi\Session;
use ChatApi\UserRepository;

require_once __DIR__ . '/../../vendor/autoload.php';

$session = new Session();
$request = new \ChatApi\HttpRequest();


if ($request->getMethod() === 'GET') {

    if ($session->get('user_id') !== null) {
        (new HttpResponder())->respond(
            new \ChatApi\HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => true,
                        'error' => 'logged in'
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
    }
}

if ($request->getMethod() !== 'POST') {
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

$email = $request->getPostParams()['email'] ?? null;
$password = $request->getPostParams()['password'] ?? null;

if (!$email || !$password) {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'email or password not specified'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}

$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$userRepository = new UserRepository($pdo);

try {
    $userId = $userRepository->authenticate(
        $email,
        $password
    );

    $session->set('user_id', $userId);

    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => true,
                    'user_id' => $userId
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
} catch (Throwable $excep) {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_UNAUTHORIZED,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'user could not ne authenticated'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}


print_r($userId);
