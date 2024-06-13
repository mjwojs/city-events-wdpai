<?php

require_once 'Database.php';
require_once 'src/models/Event.php';

class EventRepository {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function findAll(): array {
        $conn = $this->database->connect();

        $stmt = $conn->prepare('SELECT * FROM events');
        $stmt->execute();

        $events = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events[] = new Event($row['title'], $row['description'], $row['location'], $row['date']);
        }

        return $events;
    }
}
