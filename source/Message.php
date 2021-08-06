<?php


namespace ChatApi;

use ChatApi\Contracts\ProvidesMessage;
use DateTime;

/**
 * Class Message
 * @package ChatApi
 */
final class Message implements ProvidesMessage
{
    /**@var int */
    private $id;

    /** @var string */
    private $text;

    /** @var DateTime */
    private $createdAt;

    /** @var int */
    private $senderId;

    /** @var int */
    private $receiverId;

    /**
     * Message constructor.
     * @param int $id
     * @param string $text
     * @param DateTime $createdAt
     * @param int $senderId
     * @param int $receiverId
     */
    public function __construct(int $id, string $text, DateTime $createdAt, int $senderId, int $receiverId)
    {
        $this->id = $id;
        $this->text = $text;
        $this->createdAt = $createdAt;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }

    /**
     * @param string $text
     * @param DateTime $createdAt
     * @param int $senderId
     * @param int $receiverId
     * @return static
     */
    public static function new(string $text, DateTime $createdAt, int $senderId, int $receiverId):self
    {
        return new self(
            0,
            $text,
            $createdAt,
            $senderId,
            $receiverId
        );
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * @return int
     */
    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}