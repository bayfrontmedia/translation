<?php

/**
 * @package translation
 * @link https://github.com/bayfrontmedia/translation
 * @author John Robinson <john@bayfrontmedia.com>
 * @copyright 2020 Bayfront Media
 */

namespace Bayfront\Translation\Adapters;

use Bayfront\Translation\AdapterException;
use Bayfront\Translation\AdapterInterface;
use PDOException;

class PDO implements AdapterInterface
{

    protected $pdo;

    protected $table;

    /**
     * PDO constructor.
     *
     * @param \PDO $pdo
     * @param string $table
     *
     * @throws AdapterException
     */

    public function __construct(\PDO $pdo, string $table = 'languages')
    {

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Throw exceptions

        try {

            $query = $pdo->prepare("CREATE TABLE IF NOT EXISTS $table (`locale` varchar(80) NOT NULL, `id` varchar(255) NOT NULL, `contents` longtext NOT NULL, UNIQUE KEY unique_index(locale,id))");

            $query->execute();

        } catch (PDOException $e) {

            throw new AdapterException($e->getMessage(), 0, $e);

        }

        $this->pdo = $pdo;

        $this->table = $table;
    }


    /**
     * @param string $locale
     * @param string $id
     *
     * @return array
     */

    public function read(string $locale, string $id): array
    {

        try {

            $stmt = $this->pdo->prepare("SELECT contents FROM $this->table WHERE locale = :locale AND id = :id");

            $stmt->execute([
                ':locale' => $locale,
                ':id' => $id
            ]);

            $result = $stmt->fetchColumn();

            if (false === $result) {
                return [];
            }

            $result = json_decode($result, true);

            if (!is_array($result)) {
                return [];
            }

            return $result;

        } catch (PDOException $e) {

            /*
             * Exceptions are returned as an empty array, so no error will bubble up
             * if the database query is unable to execute, or if the contents are not
             * a valid JSON format.
             */

            return [];

        }

    }

}