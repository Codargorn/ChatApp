<?php


namespace ChatApi;


use Fig\Http\Message\StatusCodeInterface;
use JsonException;

/**
 * Class RegistrationRequestHandler
 * @package ChatApi
 */
final class RegistrationRequestHandler
{
    /**
     * @param HttpRequest $request
     * @return HttpResponse
     * @throws JsonException
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        $email = $request->getPostParams()['email'] ?? null;
        $password = $request->getPostParams()['password'] ?? null;
        $username = $request->getPostParams()['username'] ?? null;

        $settings = require __DIR__ . '/../config/database.php';

        $pdo = MysqlConnection::fromConfig($settings);
        $userRepository = new UserRepository($pdo);

        if ($email && !$password) {
            if ($userRepository->existsWithEmail($email)) {

                return new HttpResponse(
                    StatusCodeInterface::STATUS_OK,
                    [
                        'Cache-Control' => 'no-cache'
                    ],
                    json_encode(
                        [
                            'success' => true,
                            'error' => 'email already exists'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );
            }

            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => "email doesn't exist"
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
        }

        $userRepository->createNewUser($email, $password, $username);

        return new HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => true,
                    'error' => 'User stored'
                ],
                JSON_THROW_ON_ERROR
            )
        );
    }
}