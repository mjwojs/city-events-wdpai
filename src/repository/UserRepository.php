<?php

require_once 'Database.php';
require_once 'src/models/User.php';

class UserRepository {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function save(User $user): void {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('INSERT INTO users (email, password, first_name, last_name, username, city) VALUES (:email, :password, :first_name, :last_name, :username, :city)');
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':password', $user->getPassword());
        $stmt->bindParam(':first_name', $user->getFirstName());
        $stmt->bindParam(':last_name', $user->getLastName());
        $stmt->bindParam(':username', $user->getUsername());
        $stmt->bindParam(':city', $user->getCity());
        $stmt->execute();
    }

    public function findByEmail(string $email): ?User {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['email'],
                $data['password'],
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                $data['username'] ?? null,
                $data['city'] ?? null,
                $data['id']
            );
        }

        return null;
    }

    public function find(int $id): ?User {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['email'],
                $data['password'],
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                $data['username'] ?? null,
                $data['city'] ?? null,
                $data['id']
            );
        }

        return null;
    }
}
