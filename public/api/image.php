<?php declare(strict_types=1);

use ChatApi\HttpResponder;
use ChatApi\HttpResponse;
use ChatApi\ImageRepository;
use ChatApi\MysqlConnection;
use Fig\Http\Message\StatusCodeInterface;

require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);


$request = new \ChatApi\HttpRequest();
$userId = (int)$request->getQueryParams()['user_id'];

$imageRepository = new ImageRepository($pdo);

try {
    $blob = $imageRepository->fetch($userId);
    (new HttpResponder())->respond(
        new HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'image/jpeg'
            ],
            base64_decode($blob)
        )
    );
} catch (LogicException $excep) {
    (new HttpResponder())->respond(
        new HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'image/png'
            ],
            file_get_contents(__DIR__ . '/../../dummy-profile-pic.png')
        )
    );
}