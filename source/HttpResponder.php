<?php declare(strict_types=1);

namespace ChatApi;

/**
 * Class HttpResponder
 * @package ChatApi
 */
final class HttpResponder
{
    public function respond(HttpResponse $response): void
    {
        header(
            "HTTP/1.1 {$response->getStatusCode()} "
            . StatusCodeMessages::getHttpStatusMessage($response->getStatusCode())
        );

        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        echo $response->getBody();
        exit(0);
    }
}