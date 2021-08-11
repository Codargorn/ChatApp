<?php declare(strict_types=1);

use ChatApi\HttpResponder;
use ChatApi\ImageRepository;
use ChatApi\MysqlConnection;
use ChatApi\UserRepository;
use ChatApi\HttpResponse;
use Fig\Http\Message\StatusCodeInterface;

require_once __DIR__ . '/../../vendor/autoload.php';


$settings = require __DIR__ . '/../../config/database.php';

$pdo = MysqlConnection::fromConfig($settings);


$request = new \ChatApi\HttpRequest();


$uploadedFiles = $request->getUploadedFiles();

$uploadedFile = $uploadedFiles['image'] ?? null;


if ( $uploadedFile === null )
{
    (new HttpResponder())->respond(
        new HttpResponse(
            StatusCodeInterface::STATUS_BAD_REQUEST,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'image could not be uploaded'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );

}
$type = $uploadedFile['type'];
if ( $type !== 'image/jpeg' )
{
    (new HttpResponder())->respond(
        new HttpResponse(
            StatusCodeInterface::STATUS_BAD_REQUEST,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'file must be image/jpeg'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}

$tempFile=$uploadedFile['tmp_name'];
if ( !file_exists($tempFile) || !is_readable($tempFile))
{
    (new HttpResponder())->respond(
        new HttpResponse(
            StatusCodeInterface::STATUS_BAD_REQUEST,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => false,
                    'error' => 'file could not be read'
                ],
                JSON_THROW_ON_ERROR
            )
        )
    );
}

$blob = base64_encode(file_get_contents($uploadedFile['tmp_name']));

$imageRepository = new ImageRepository($pdo);
$userRepository = new UserRepository($pdo);
$userRepository->assignImage((int)$request->getPostParams()['currentUserId'],$imageRepository->persist($blob));


(new HttpResponder())->respond(
    new HttpResponse(
    StatusCodeInterface::STATUS_OK,
    [
        'Cache-Control' => 'no-cache'
    ],
    json_encode(
        [
            'success' => true,
            'message' => 'image successfully uploaded'
        ],
        JSON_THROW_ON_ERROR
    )
)
);
