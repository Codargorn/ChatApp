<?php

namespace ChatApi;

use LogicException;
use PDO;

final class ImageRepository
{
    /** @var PDO */
    private $pdo;

    /**
     * NotesRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function persist(string $blob): int
    {
        $statement = $this->pdo->prepare('INSERT INTO images(file, created_at) VALUES (:file, NOW())');

        $statement->execute([
            ':file' => $blob,
        ]);

        return (int)$this->pdo->lastInsertId('images');
    }

    /**
     * @param int $userId
     * @return string
     */
    public function fetch(int $userId): string
    {
        $statement = $this->pdo->prepare(
            'SELECT i.file
                    FROM images AS i
                        JOIN users AS u
                            ON u.image_id = i.id
                    WHERE u.id = :user_id
                    LIMIT 1'
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        $blob = $statement->fetchColumn();
        if (!$blob) {
            throw new LogicException('image could not be found');
        }


        return $blob;
    }
}