<?php declare(strict_types=1);

namespace ChatApi\RequestHandler;


use ChatApi\Contracts\ProvidesSession;
use ChatApi\Contracts\ProvidesUsers;
use ChatApi\Contracts\RepresentsRequest;
use ChatApi\HttpResponse;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use PDOException;
use Throwable;

/**
 * Class UserRequestHandler
 * @package ChatApi
 */
final class UserRequestHandler
{
    /** @var ProvidesSession */
    private $session;

    /** @var ProvidesUsers */
    private $userRepository;


    /**
     * UserRequestHandler constructor.
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
        if ($request->getMethod() !== 'GET') {
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

        if ($this->session->get('user_id') === null) {
            return new HttpResponse(
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
            );
        }

        try {
            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    $this->userRepository->getUsers(),
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
                        'error' => 'Users could not be read'
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








