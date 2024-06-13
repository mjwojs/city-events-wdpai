<?php

class User {
    private $id;
    private $email;
    private $password;

    public function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }
}
