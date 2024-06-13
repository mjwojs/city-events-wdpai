<?php

require_once 'Database.php';
require_once 'src/models/User.php';

class UserRepository {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function save(User $user): void {
        $conn = $this->database->connect();

        $stmt = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':password', $user->getPassword());
        $stmt->execute();
    }

    public function findByEmail(string $email): ?User {
        $conn = $this->database->connect();

        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User($data['email'], $data['password']);
        }

        return null;
    }

    public function find(int $id): ?User {
        $conn = $this->database->connect();

        $stmt = $conn->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User($data['email'], $data['password']);
        }

        return null;
    }
}
