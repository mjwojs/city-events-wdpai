<?php

class User {
    private $id;
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    private $username;
    private $city;

    public function __construct(string $email, string $password, ?string $firstName = null, ?string $lastName = null, ?string $username = null, ?string $city = null, ?int $id = null) {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->city = $city;
        $this->id = $id;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getFirstName(): ?string {
        return $this->firstName;
    }

    public function getLastName(): ?string {
        return $this->lastName;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function getCity(): ?string {
        return $this->city;
    }
}
