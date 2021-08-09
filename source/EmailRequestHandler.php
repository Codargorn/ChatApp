<?php

namespace ChatApi;


use ChatApi\Contracts\ProvidesUsers;
use ChatApi\Contracts\RepresentsRequest;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use PDOException;
use Throwable;

/**
 * Class EmailRequestHandler
 * @package ChatApi
 */
final class EmailRequestHandler
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

        try {
            $email = $request->getPostParams()['email'] ?? null;

            if (!$email) {

                return new HttpResponse(
                    StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
                    [
                        'Cache-Control' => 'no-cache'
                    ],
                    json_encode(
                        [
                            'success' => false,
                            'error' => 'email not specified'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );

            }

            if ($this->userRepository->existsWithEmail($email)) {

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

            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => true,
                        'message' => "email doesn't exist"
                    ],
                    JSON_THROW_ON_ERROR
                )
            );

        } catch (PDOException $exception) {

            return new HttpResponse(
                StatusCodeInterface::STATUS_BAD_REQUEST,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'email could not be checked'
                    ],
                    JSON_THROW_ON_ERROR
                )
            );

        } catch (JsonException $exception) {

            return new HttpResponse(
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
            );

        } catch (Throwable $exception) {

            return new HttpResponse(
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
            );

        }
    }
}