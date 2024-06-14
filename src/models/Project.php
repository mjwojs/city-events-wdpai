<?php

class Project {
    private $title;
    private $description;
    private $location;
    private $id;
    private $date;
    private $creatorId;  // Dodana właściwość

    public function __construct(string $title, string $description, string $location, int $id, string $date, int $creatorId) {
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->id = $id;
        $this->date = $date;
        $this->creatorId = $creatorId;  // Przypisanie wartości
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

    public function getId(): int {
        return $this->id;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getCreatorId(): int {  // Nowa metoda
        return $this->creatorId;
    }
}
