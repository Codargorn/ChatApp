<?php

namespace ChatApi\Contracts;


use Exception;

/**
 * Interface ProvidesMessages
 * @package ChatApi\Contracts
 */
interface ProvidesMessages
{
    /**
     * @param ProvidesMessage $message
     */
    public function add(ProvidesMessage $message): void;



    /**
     * @param int $senderId
     * @param int $receiverId
     * @return array
     * @throws Exception
     */
    public function getMessages(int $senderId, int $receiverId): array;



    /**
     * @param int $senderId
     * @param int $receiverId
     * @return ProvidesMessage
     * @throws Exception
     */
    public function lastMessages(int $senderId, int $receiverId): ProvidesMessage;

}