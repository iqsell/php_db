<?php

namespace App;

use InvalidArgumentException;
use PDO;
use RuntimeException;

class User
{
    private PDO $connection;

    public function __construct(Database $db)
    {
        $this->connection = $db->getConnection();
    }

    public function addUser(string $name, string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format");
        }

        $query = 'INSERT INTO users (name, email) VALUES (:name, :email)';
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':name' => $name, ':email' => $email]);
    }


    public function updateUser(int $id, string $name, string $email): void
    {
        $user = $this->getUserById($id);
        if (!$user) {
            throw new RuntimeException("User with id $id does not exist");
        }

        $query = 'UPDATE users SET name = :name, email = :email WHERE id = :id';
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':name' => $name, ':email' => $email, ':id' => $id]);
    }

    public function getUserById(int $id): ?array
    {
        $query = 'SELECT id, name, email FROM users WHERE id = :id';
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function searchUsers(?string $search = null): array
    {
        $query = 'SELECT id, name, email FROM users';
        if ($search) {
            $query .= ' WHERE name LIKE :search OR email LIKE :search';
            $stmt = $this->connection->prepare($query);
            $stmt->execute([':search' => '%' . $search . '%']);
        } else {
            $stmt = $this->connection->query($query);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser(int $id): void
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
    }
}
