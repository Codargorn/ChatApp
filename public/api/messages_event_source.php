<?php declare(strict_types=1);

use ChatApi\HttpRequest;
use ChatApi\HttpResponder;
use ChatApi\Message;
use ChatApi\MessagesRepository;
use ChatApi\MysqlConnection;
use ChatApi\Session;
use Fig\Http\Message\StatusCodeInterface;

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


    try {
        $lastMessage = $messageRepository->lastMessages($senderId, $receiverId);
        $eventString = "event: message\n";
        $eventString = 'id:'. $lastMessage->getId()."\n";
        $eventString .= 'data: '. json_encode(
                [
                    'id' => $lastMessage->getId(),
                    'text' => $lastMessage->getText(),
                    'createdAt' => $lastMessage->getCreatedAt()->format('Y-m-d H:i:s'),
                    'sender_id' => $lastMessage->getSenderId(),
                    'receiver_id' => $lastMessage->getReceiverId()
                ],
                JSON_THROW_ON_ERROR
            ) . "\n\n";


        (new HttpResponder())->respond(
            new \ChatApi\HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'text/event-stream'
                ],
                $eventString
            )
        );
    }
    catch (Throwable $excep)
    {
        (new HttpResponder())->respond(
            new \ChatApi\HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'text/event-stream'
                ],
                ''
            )
        );
    }

}