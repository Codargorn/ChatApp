<?php


namespace ChatApi;

use ChatApi\Contracts\ProvidesMessages;
use ChatApi\Contracts\ProvidesSession;

use ChatApi\Contracts\RepresentsRequest;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Throwable;

/**
 * Class UserRequestHandler
 * @package ChatApi
 */
final class EventSourceRequestHandler
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

            $lastMessage = $this->messagesRepository->lastMessages($senderId, $receiverId);
            $eventString = "event: message\n";
            $eventString .= 'id:' . $lastMessage->getId() . "\n";
            $eventString .= 'data: ' . json_encode(
                    [
                        'id' => $lastMessage->getId(),
                        'text' => $lastMessage->getText(),
                        'createdAt' => $lastMessage->getCreatedAt()->format('Y-m-d H:i:s'),
                        'sender_id' => $lastMessage->getSenderId(),
                        'receiver_id' => $lastMessage->getReceiverId()
                    ],
                    JSON_THROW_ON_ERROR
                ) . "\n\n";


            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'text/event-stream'
                ],
                $eventString
            );

        } catch (Throwable $exception) {

            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'text/event-stream'
                ],
                ''
            );
        }


    }
}


