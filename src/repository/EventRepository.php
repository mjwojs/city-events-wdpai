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

        try {
            $stmt = $conn->prepare('SELECT * FROM projects');
            $stmt->execute();

            $projects = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $projects[] = new Project(
                    $row['title'],
                    $row['description'],
                    $row['location'],
                    $row['id'],
                    $row['date']
                );
            }
            return $projects;
        } catch (PDOException $e) {
            error_log('Error fetching projects: ' . $e->getMessage());
            return [];
        }
    }

    public function addEvent(string $title, string $description, string $location, string $date, int $creatorId): int {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('
                INSERT INTO projects (title, description, location, date, creator_id) 
                VALUES (:title, :description, :location, :date, :creator_id) RETURNING id
            ');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':creator_id', $creatorId);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error adding event: ' . $e->getMessage());
            return 0;
        }
    }

    public function addEventAttendee(int $eventId, string $email): void {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('
                INSERT INTO event_attendees (event_id, user_id) 
                SELECT :event_id, id FROM users WHERE email = :email
            ');
            $stmt->bindParam(':event_id', $eventId);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error adding event attendee: ' . $e->getMessage());
        }
    }

    public function getEventById(int $id): ?Project {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('SELECT * FROM projects WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Project(
                    $row['title'],
                    $row['description'],
                    $row['location'],
                    $row['id'],
                    $row['date']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log('Error fetching event: ' . $e->getMessage());
            return null;
        }
    }
}
