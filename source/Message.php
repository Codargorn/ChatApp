<?php


namespace ChatApi;

use DateTime;

/**
 * Class Message
 * @package ChatApi
 */
final class Message
{
    /** @var string */
    private $text;

    /** @var DateTime */
    private $createdAt;

    /** @var int */
    private $senderId;

    /** @var int */
    private $receiverId;

    /**
     * Note constructor.
     * @param string $text
     * @param DateTime $createdAt
     * @param int $senderId
     * @param int $receiverId
     */
    public function __construct(string $text, DateTime $createdAt, int $senderId, int $receiverId)
    {
        $this->text = $text;
        $this->createdAt = $createdAt;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
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


}