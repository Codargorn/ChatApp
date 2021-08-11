<?php declare(strict_types=1);

namespace ChatApi\RequestHandler;


use ChatApi\Contracts\ProvidesMessages;
use ChatApi\Contracts\ProvidesSession;
use ChatApi\Contracts\RepresentsRequest;
use ChatApi\HttpResponse;
use ChatApi\Message;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use PDOException;
use Throwable;


/**
 * Class UserRequestHandler
 * @package ChatApi
 */
final class MessagesRequestHandler
{
    /** @var ProvidesSession */
    private $session;


    /** @var ProvidesMessages */
    private $messagesRepository;

    /**
     * UserRequestHandler constructor.
     * @param ProvidesSession $session
     * @param ProvidesMessages $messagesRepository
     */
    public function __construct(ProvidesSession $session, ProvidesMessages $messagesRepository)
    {
        $this->session = $session;
        $this->messagesRepository = $messagesRepository;
    }


    /**
     * @param RepresentsRequest $request
     * @return HttpResponse
     * @throws JsonException
     */
    public function handle(RepresentsRequest $request): HttpResponse
    {
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


        try {

            $senderId = (int)$request->getQueryParams()['sender_id']; //?? null;
            $receiverId = (int)$request->getQueryParams()['receiver_id']; //?? null;

            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(array_map(
                    static function (Message $message): array {
                        return [
                            'id' => $message->getId(),
                            'text' => $message->getText(),
                            'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                            'sender_id' => $message->getSenderId(),
                            'receiver_id' => $message->getReceiverId()
                        ];
                    },
                    $this->messagesRepository->getMessages($senderId, $receiverId)),
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
                        'error' => 'messages could not be sent'
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


