<?php


class Project {

    private $title;
    private $description;
    private $photoUrl;

    public function __construct(string $title, string $description, string $photoUrl) {
        $this->title = $title;
        $this->description = $description;
        $this->photoUrl = $photoUrl;
    }

    public function getTitle() : string {
        return $this->title;
    }
}