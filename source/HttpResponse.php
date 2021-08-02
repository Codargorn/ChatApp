<?php declare(strict_types=1);


namespace ChatApi;

use Fig\Http\Message\StatusCodeInterface;

/**
 * Class HttpResponse
 * @package ChatApi
 */
final class HttpResponse
{
    /** @var int */
    private $statusCode;

    /** @var array<string, string> */
    private $headers;

    /** @var string */
    private $body;

    /**
     * HttpResponse constructor.
     * @param int $status
     * @param string[] $headers
     * @param string $body
     */
    public function __construct(int $status = StatusCodeInterface::STATUS_OK, array $headers = [], string $body = '')
    {
        $this->statusCode = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}