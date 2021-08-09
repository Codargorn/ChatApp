<?php


namespace ChatApi;


use ChatApi\Contracts\ProvidesMessages;
use ChatApi\Contracts\ProvidesSession;


use ChatApi\Contracts\RepresentsRequest;
use DateTime;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use PDOException;
use Throwable;

/**
 * Class UserRequestHandler
 * @package ChatApi
 */
final class MessageRequestHandler
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
                ['Cache-Control' => 'no-cache'],
                json_encode(
                    ['success' => false,
                        'error' => 'not logged in'],
                    JSON_THROW_ON_ERROR
                )
            );
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
        try {

            $text = $request->getPostParams()['text'] ?? null;
            $senderId = (int)$request->getPostParams()['sender_id'];
            $receiverId = (int)$request->getPostParams()['receiver_id'];

            $this->messagesRepository->add(Message::new(
                $text,
                new DateTime(),
                $senderId,
                $receiverId
            ));


            return new HttpResponse(
                StatusCodeInterface::STATUS_OK,
                [
                    'Cache-Control' => 'no-cache'
                ],
                json_encode(
                    [
                        'success' => true,
                        'message' => 'message successfully stored'
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
                        'error' => 'message could not be written'
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

