<?php declare(strict_types=1);

namespace ChatApi\Contracts;

/**
 * Interface ProvidesUsers
 * @package ChatApi\Contracts
 */
interface ProvidesUsers
{
    /**
     * @param int $loggedInUserId
     * @return array[]
     */
    public function getUsers(int $loggedInUserId): array;

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

    /**
     * @param string $email
     * @param string $password
     * @return int
     */
    public function authenticate(string $email, string $password): int;
}