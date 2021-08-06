<?php

namespace ChatApi;

use ChatApi\Contracts\ProvidesSession;
use ChatApi\Contracts\ProvidesUsers;

use ChatApi\Contracts\RepresentsRequest;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Throwable;

/**
 * Class LoginRequestHandler
 * @package ChatApi
 */
final class LoginRequestHandler
{
    /** @var ProvidesSession */
    private $session;

    /** @var ProvidesUsers */
    private $userRepository;


    /**
     * LoginRequestHandler constructor.
     * @param ProvidesSession $session
     * @param ProvidesUsers $userRepository
     */
    public function __construct(ProvidesSession $session, ProvidesUsers $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }


    /**
     * @param RepresentsRequest $request
     * @return HttpResponse
     * @throws JsonException
     */
    public function handle(RepresentsRequest $request): HttpResponse
    {
        if ($request->getMethod() === 'GET') {

            if ($this->session->get('user_id') !== null) {
                return new HttpResponse(
                    StatusCodeInterface::STATUS_OK,
                    [
                        'Cache-Control' => 'no-cache'
                    ],
                    json_encode(
                        [
                            'success' => true,
                            'error' => 'logged in'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );
            }
        }

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

        if (!$email || !$password) {

            return new HttpResponse(
                StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'email or password not specified'
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
        }

        try {
            $userId = $this->userRepository->authenticate(
                $email,
                $password
            );

            $this->session->set('user_id', $userId);


            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => true,
                        'user_id' => $userId
                    ],
                    JSON_THROW_ON_ERROR
                )
            );

        } catch (Throwable $excep) {

            return new HttpResponse(
                StatusCodeInterface::STATUS_UNAUTHORIZED,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'user could not ne authenticated'
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
        }
    }
}





