<?php declare(strict_types=1);

namespace ChatApi\Contracts;

/**
 * Interface ProvidesUsers
 * @package ChatApi\Contracts
 */
interface ProvidesUsers
{
    /**
     * @param string $email
     * @param string $password
     * @param string $username
     */
    public function createNewUser(string $email, string $password, string $username): void;

    /**
     * @param string $email
     * @return bool
     */
    public function existsWithEmail(string $email): bool;
}