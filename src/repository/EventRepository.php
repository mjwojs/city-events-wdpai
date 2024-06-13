<?php

require_once 'Database.php';
require_once 'src/models/Project.php';

class EventRepository {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getAllProjects(): array {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('SELECT * FROM projects');
        $stmt->execute();

        $projects = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = new Project(
                $row['title'],
                $row['description'],
                $row['image'],
                $row['id'],
                $row['date']
            );
        }

        return $projects;
    }

    public function addEvent(string $title, string $description, string $location, string $date, int $creatorId): int {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('
            INSERT INTO projects (title, description, image, date, creator_id) 
            VALUES (:title, :description, :image, :date, :creator_id) RETURNING id
        ');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $location); // Assuming location is used as image URL for simplicity
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':creator_id', $creatorId);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function addEventAttendee(int $eventId, string $email): void {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('
            INSERT INTO event_attendees (event_id, user_id) 
            SELECT :event_id, id FROM users WHERE email = :email
        ');
        $stmt->bindParam(':event_id', $eventId);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
}
