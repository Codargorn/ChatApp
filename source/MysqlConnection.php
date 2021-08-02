<?php declare(strict_types=1);

namespace ChatApi;

use PDO;

/**
 * Class MysqlConnection
 * @package ChatApi
 */
final class MysqlConnection
{
    /**
     * @param array $settings
     * @return PDO
     */
    public static function fromConfig(array $settings): PDO
    {
        return new PDO(
            "mysql:dbname={$settings['database']};host={$settings['host']};port={$settings['port']}",
            $settings['username'],
            $settings['password']
        );
    }
}