<?php

require_once 'Database.php';
require_once 'src/models/Project.php';

class EventRepository {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getAllProjectsForUser(int $userId): array {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('
                SELECT p.* FROM projects p
                JOIN event_attendees ea ON p.id = ea.event_id
                WHERE ea.user_id = :user_id
            ');
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            $projects = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $projects[] = new Project(
                    $row['title'],
                    $row['description'],
                    $row['location'],
                    $row['id'],
                    $row['date'],
                    $row['creator_id']  // Dodanie `creator_id`
                );
            }
            return $projects;
        } catch (PDOException $e) {
            error_log('Error fetching projects: ' . $e->getMessage());
            return [];
        }
    }

    public function addEvent(string $title, string $description, string $location, string $date, int $creatorId, array $emails): int {
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

            $eventId = $stmt->fetchColumn();

            // Add creator as attendee
            $this->addEventAttendee($eventId, $creatorId);

            // Add invitees as attendees
            foreach ($emails as $email) {
                $userId = $this->getUserIdByEmail($email);
                if ($userId) {
                    $this->addEventAttendee($eventId, $userId);
                }
            }

            return $eventId;
        } catch (PDOException $e) {
            error_log('Error adding event: ' . $e->getMessage());
            return 0;
        }
    }

    public function addEventAttendee(int $eventId, int $userId): void {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('
                INSERT INTO event_attendees (event_id, user_id) 
                VALUES (:event_id, :user_id)
            ');
            $stmt->bindParam(':event_id', $eventId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error adding event attendee: ' . $e->getMessage());
        }
    }

    private function getUserIdByEmail(string $email): ?int {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('SELECT id FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['id'] : null;
        } catch (PDOException $e) {
            error_log('Error fetching user by email: ' . $e->getMessage());
            return null;
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
                    $row['date'],
                    $row['creator_id']  // Dodanie `creator_id`
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log('Error fetching event: ' . $e->getMessage());
            return null;
        }
    }

    public function updateEvent(int $id, string $title, string $description, string $location, string $date): bool {
        $conn = $this->database->getConnection();

        try {
            $stmt = $conn->prepare('
                UPDATE projects
                SET title = :title, description = :description, location = :location, date = :date
                WHERE id = :id
            ');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error updating event: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent(int $id): bool {
        $conn = $this->database->getConnection();

        try {
            // Najpierw usuń powiązania z tabeli event_attendees
            $stmt = $conn->prepare('DELETE FROM event_attendees WHERE event_id = :event_id');
            $stmt->bindParam(':event_id', $id);
            $stmt->execute();

            // Następnie usuń wydarzenie z tabeli projects
            $stmt = $conn->prepare('DELETE FROM projects WHERE id = :id');
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error deleting event: ' . $e->getMessage());
            return false;
        }
    }
}
