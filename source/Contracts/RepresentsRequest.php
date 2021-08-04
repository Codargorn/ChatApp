<?php


namespace ChatApi\Contracts;


interface RepresentsRequest
{
    public function getMethod(): string;

    /**
     * @return array<string, string>
     */
    public function getQueryParams(): array;

    /**
     * @return array<string, string>
     */
    public function getPostParams(): array;

    /**
     * @return array<string, string>
     */
    public function getServerParams(): array;

    /**
     * @return string
     */
    public function getBody(): string;
}