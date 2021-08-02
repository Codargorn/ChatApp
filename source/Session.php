<?php declare(strict_types=1);

namespace ChatApi;

/**
 * Class Session
 * @package ChatApi
 */
final class Session
{

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function close(): void
    {
        setcookie((string)session_name(), '', 0, '/');
        session_destroy();
    }
}