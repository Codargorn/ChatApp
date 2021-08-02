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

$userId = (int)$session->get('userId');
$request = new HttpRequest();
$status = StatusCodeInterface::STATUS_OK;

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
                        'title' => $note->getTitle(),
                        'text' => $note->getText(),
                        'createdAt' => $note->getCreatedAt()->format('Y-m-d H:i:s')
                    ];
                },
                $noteRepository->findAll($userId)),
                JSON_THROW_ON_ERROR
            )
        )
    );
}