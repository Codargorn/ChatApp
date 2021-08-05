<?php

require_once __DIR__ . '/../vendor/autoload.php';


class FakeRequest implements \ChatApi\Contracts\RepresentsRequest
{

    public function getMethod(): string
    {
        // TODO: Implement getMethod() method.
    }

    public function getQueryParams(): array
    {
        // TODO: Implement getQueryParams() method.
    }

    public function getPostParams(): array
    {
        return [
            'email' => 'test.@excample.com',
            'password' => null,
            'username' => 'Tester, Gerd',
        ];
    }

    public function getServerParams(): array
    {
        // TODO: Implement getServerParams() method.
    }

    public function getBody(): string
    {
        // TODO: Implement getBody() method.
    }
}


class FakeRepository implements \ChatApi\Contracts\ProvidesUsers
{

    public function createNewUser(string $email, string $password, string $username): void
    {
    }

    public function existsWithEmail(string $email): bool
    {
        return false;
    }
}


$request = new \ChatApi\HttpRequest();
$handler = new \ChatApi\RegistrationRequestHandler(new FakeRepository());
$response =     $handler->handle(new FakeRequest());
print_r($response);
if ($response->getStatusCode() === 200)
{
    $body = json_decode($response->getBody(), true);


    if (!$body['success'])
    {
        echo "KAPUTT", "\n";
        exit(1);
    }

    if ($body['message'] !== 'User stored')
    {
        echo "KAPUTT", "\n";
        exit(1);
    }

    echo "OK", "\n";
}
else
{
    echo "KAPUTT", "\n";
    exit(1);
}
