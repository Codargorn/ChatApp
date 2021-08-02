<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use ChatApi\HttpResponder;
use ChatApi\Session;

require_once __DIR__ . '/../../vendor/autoload.php';

$session = new Session();
$request = new \ChatApi\HttpRequest();

if ($request->getMethod() !== 'GET') {
    $session->close();
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


if ($request->getMethod() === 'GET') {
    $session->close();
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => true,
                    'error' => 'logged out'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}
