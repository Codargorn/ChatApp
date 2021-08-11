<?php

namespace ChatApi\Contracts;


use DateTime;

/**
 * Interface ProvidesMessage
 * @package ChatApi\Contracts
 */
interface ProvidesMessage
{
    /**
     * @return string
     */
    public function getText(): string;


    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;


    /**
     * @return int
     */
    public function getSenderId(): int;


    /**
     * @return int
     */
    public function getReceiverId(): int;


    /**
     * @return int
     */
    public function getId(): int;

}