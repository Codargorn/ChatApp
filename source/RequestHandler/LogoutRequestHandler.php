<?php declare(strict_types=1);

namespace ChatApi\RequestHandler;

use ChatApi\Contracts\ProvidesSession;
use ChatApi\Contracts\RepresentsRequest;
use ChatApi\HttpResponse;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use PDOException;
use Throwable;


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
            $this->session->close();
            return new HttpResponse(
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
        } catch (PDOException $exception) {

            return new HttpResponse(
                StatusCodeInterface::STATUS_BAD_REQUEST,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => false,
                        'error' => 'user could not be logged out'
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


