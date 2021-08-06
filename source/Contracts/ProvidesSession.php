<?php

namespace ChatApi\Contracts;


/**
 * Interface ProvidesSession
 * @package ChatApi\Contracts
 */
interface ProvidesSession
{
    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    public function close(): void;
}