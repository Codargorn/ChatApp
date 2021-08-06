<?php

namespace ChatApi;

use ChatApi\Contracts\ProvidesSession;
use ChatApi\Contracts\ProvidesUsers;

use ChatApi\Contracts\RepresentsRequest;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;


/**
 * Class LogoutRequestHandler
 * @package ChatApi
 */
final class LogoutRequestHandler
{
    /** @var ProvidesSession */
    private $session;


    /**
     * LogoutRequestHandler constructor.
     * @param ProvidesSession $session
     */
    public function __construct(ProvidesSession $session)
    {
        $this->session = $session;
    }


    /**
     * @param RepresentsRequest $request
     * @return HttpResponse
     * @throws JsonException
     */
    public function handle(RepresentsRequest $request): HttpResponse
    {
        if ($request->getMethod() !== 'GET') {
            $this->session->close();

                return new \ChatApi\HttpResponse(
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


            $this->session->close();
                return new \ChatApi\HttpResponse(
                    StatusCodeInterface::STATUS_OK,
                    [
                        'Cache-Control' => 'no-cache'
                    ],
                    json_encode(
                        [
                            'success' => true,
                            'error' => 'logged out'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );
        }
}


