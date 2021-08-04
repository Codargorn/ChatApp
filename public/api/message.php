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

$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);
$messageRepository = new MessagesRepository($pdo);

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
try {

    $text = $request->getPostParams()['text'] ?? null;
    $SenderId = (int)$request->getPostParams()['sender_id'];
    $ReceiverId = (int)$request->getPostParams()['receiver_id'];

    $message = new Message(
        0,
        $text,
        new DateTime(),
        $SenderId,
        $ReceiverId
    );
    $messageRepository->add($message);

    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => true,
                    'message' => 'message successfully stored'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}catch (PDOException $exception) {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_BAD_REQUEST,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'message could not be written'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
} catch (JsonException $exception) {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_BAD_REQUEST,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'wrong payload'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
} catch (Throwable $exception) {
    (new HttpResponder())->respond(
        new \ChatApi\HttpResponse(
            StatusCodeInterface::STATUS_BAD_REQUEST,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => $exception->getMessage()
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}