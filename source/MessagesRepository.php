<?php declare(strict_types=1);


namespace ChatApi;

use DateTime;
use Exception;
use PDO;
use RuntimeException;


/**
 * Class MessagesRepository
 * @package ChatApi
 */
final class MessagesRepository
{
    /** @var PDO */
    private $pdo;

    /**
     * MessagesRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Message $message
     */
    public function add(Message $message): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO messages(text, created_at, sender_id, receiver_id) VALUES (:text, :created_at, :sender_id, :receiver_id)"
        );
        $succeeds = $statement->execute([
            ':text' => $message->getText(),
            ':created_at' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            ':sender_id' => $message->getSenderId(),
            ':receiver_id' => $message->getReceiverId()
        ]);

        if (!$succeeds) {
            throw new RuntimeException('note could not be added');
        }
    }

    /**
     * @throws Exception
     */
    public function getMessages(int $senderId, int $receiverId): array
    {
        $statement1 = $this->pdo->prepare(
            'SELECT *
                    FROM messages
                    WHERE (sender_id = :senderId AND receiver_id = :receiverId)
                        OR (sender_id = :receiverId AND receiver_id = :senderId)
                    ORDER BY id'
        );

        $statement1->execute([
            ':senderId' => $senderId,
            ':receiverId' => $receiverId
        ]);

        return array_map(
            static function (array $record) {
                return new Message(
                    $record['text'],
                    new DateTime($record['created_at']),
                    (int)$record['sender_id'],
                    (int)$record['receive_id']
                );
            },
            $statement1->fetchAll(PDO::FETCH_ASSOC)
        );
    }

}