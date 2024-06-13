<?php

require_once 'Database.php';
require_once 'src/models/Event.php';

class EventRepository {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function save(Event $event): void {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('INSERT INTO events (title, description, location, date) VALUES (:title, :description, :location, :date)');
        $stmt->bindParam(':title', $event->getTitle());
        $stmt->bindParam(':description', $event->getDescription());
        $stmt->bindParam(':location', $event->getLocation());
        $stmt->bindParam(':date', $event->getDate());
        $stmt->execute();
    }

    public function findAll(): array {
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare('SELECT * FROM events');
        $stmt->execute();

        $events = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events[] = new Event($row['title'], $row['description'], $row['location'], $row['date']);
        }

        return $events;
    }
}
