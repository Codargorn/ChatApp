<?php


namespace ChatApi;


use ChatApi\Contracts\ProvidesUsers;

use ChatApi\Contracts\RepresentsRequest;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;

/**
 * Class RegistrationRequestHandler
 * @package ChatApi
 */
final class RegistrationRequestHandler
{

    /** @var ProvidesUsers */
    private $userRepository;

    /**
     * RegistrationRequestHandler constructor.
     * @param ProvidesUsers $userRepository
     */
    public function __construct(ProvidesUsers $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @param RepresentsRequest $request
     * @return HttpResponse
     * @throws JsonException
     */
    public function handle(RepresentsRequest $request): HttpResponse
    {
        if ($request->getMethod() !== 'POST') {

             return new HttpResponse(
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
                );

        }

        $email = $request->getPostParams()['email'] ?? null;
        $password = $request->getPostParams()['password'] ?? null;
        $username = $request->getPostParams()['username'] ?? null;

        if (!$email || $password || $username ) {
                return  new HttpResponse(
                    StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
                    [
                        'Cache-Control' => 'no-cache'
                    ],
                    json_encode(
                        [
                            'success' => false,
                            'error' => 'email, password or username not specified'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );

        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {

            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'email not valid'
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
        }

        if ($this->userRepository->existsWithEmail($email))
        {
            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'email already exists'
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
        }

        if (strlen($password) < 9)
        {

            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'password too short'
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
        }

        $this->userRepository->createNewUser($email, $password, $username);

        return new HttpResponse(
            StatusCodeInterface::STATUS_OK,
            [
                'Cache-Control' => 'no-cache'
            ],
            json_encode(
                [
                    'success' => true,
                    'message' => 'User stored'
                ],
                JSON_THROW_ON_ERROR
            )
        );
    }
}