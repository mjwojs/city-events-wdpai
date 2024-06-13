<?php

class Event {
    private $id;
    private $title;
    private $description;
    private $location;
    private $date;

    public function __construct(string $title, string $description, string $location, string $date) {
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->date = $date;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getDate(): string {
        return $this->date;
    }
}
