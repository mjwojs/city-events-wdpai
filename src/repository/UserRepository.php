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

        $stmt = $conn->prepare('
            INSERT INTO users (email, password, first_name, last_name, username, city, profile_picture) 
            VALUES (:email, :password, :first_name, :last_name, :username, :city, :profile_picture)
        ');
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':password', $user->getPassword());
        $stmt->bindParam(':first_name', $user->getFirstName());
        $stmt->bindParam(':last_name', $user->getLastName());
        $stmt->bindParam(':username', $user->getUsername());
        $stmt->bindParam(':city', $user->getCity());
        $stmt->bindParam(':profile_picture', $user->getProfilePicture(), PDO::PARAM_LOB);
        $stmt->execute();
    }

    public function findByEmail(string $email): ?User {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $profilePicture = $data['profile_picture'] ? stream_get_contents($data['profile_picture']) : null;

            return new User(
                $data['email'],
                $data['password'],
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                $data['username'] ?? null,
                $data['city'] ?? null,
                $profilePicture ? base64_encode($profilePicture) : null,
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
            $profilePicture = $data['profile_picture'] ? stream_get_contents($data['profile_picture']) : null;

            return new User(
                $data['email'],
                $data['password'],
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                $data['username'] ?? null,
                $data['city'] ?? null,
                $profilePicture ? base64_encode($profilePicture) : null,
                $data['id']
            );
        }

        return null;
    }

    public function updateProfilePicture(int $id, $profilePicture): void {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('UPDATE users SET profile_picture = :profile_picture WHERE id = :id');
        $stmt->bindParam(':profile_picture', $profilePicture, PDO::PARAM_LOB);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
