<?php

namespace App;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private PDO $connection;

    public function __construct(string $dbPath)
    {
        $this->connect($dbPath);
    }

    private function connect(string $dbPath): void
    {
        try {
            $this->connection = new PDO('sqlite:' . $dbPath);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAllUsers(): array
    {
        $query = 'SELECT id, name, email FROM users';
        $stmt = $this->connection->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
