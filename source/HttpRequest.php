<?php declare(strict_types=1);

namespace ChatApi;


use ChatApi\Contracts\RepresentsRequest;

/**
 * Class HttpRequest
 * @package ChatApi
 */
final class HttpRequest implements RepresentsRequest
{
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * @return array<string, string>
     */
    public function getQueryParams(): array
    {
        return $_GET ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getPostParams(): array
    {
        return $_POST ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getServerParams(): array
    {
        return $_SERVER ?? [];
    }

    /**
     * @return array
     */
    public function getUploadedFiles(): array
    {
        return $_FILES ?? [];
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return (string)file_get_contents('php://input');
    }
}