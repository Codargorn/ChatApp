<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use ChatApi\HttpRequest;
use ChatApi\HttpResponder;
use ChatApi\MysqlConnection;
use ChatApi\Message;
use ChatApi\MessagesRepository;
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

$senderId = (int)$request->getQueryParams()['sender_id']; //?? null;
$receiverId = (int)$request->getQueryParams()['receiver_id']; //?? null;

$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$messageRepository = new MessagesRepository($pdo);

if ($request->getMethod() === 'GET') {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(array_map(
                static function (Message $message): array {
                    return [
                        'id' => $message->getId(),
                        'text' => $message->getText(),
                        'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                        'sender_id' => $message->getSenderId(),
                        'receiver_id' => $message->getReceiverId()
                    ];
                },
                $messageRepository->getMessages($senderId, $receiverId)),
                JSON_THROW_ON_ERROR
            )
        )
    );
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