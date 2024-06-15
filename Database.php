<?php

require_once "config.php";

class Database {
    private $username;
    private $password;
    private $host;
    private $database;
    private $conn;

    public function __construct() {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
        $this->conn = $this->connect();
        $this->initializeDatabase();
    }

    public function connect() {
        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode" => "prefer"]
            );

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // database.php
    public function initializeDatabase() {
        try {
            $this->conn->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id SERIAL PRIMARY KEY,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    first_name VARCHAR(255) NOT NULL,
                    last_name VARCHAR(255) NOT NULL,
                    username VARCHAR(255) NOT NULL,
                    city VARCHAR(255) NOT NULL,
                    profile_picture BYTEA
                );
            ");

            $this->conn->exec("
                CREATE TABLE IF NOT EXISTS projects (
                    id SERIAL PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    description TEXT,
                    location VARCHAR(255) NOT NULL,
                    date TIMESTAMP,
                    creator_id INTEGER REFERENCES users(id),
                    is_public BOOLEAN DEFAULT FALSE
                );
            ");

            $this->conn->exec("
                CREATE TABLE IF NOT EXISTS event_attendees (
                    event_id INTEGER REFERENCES projects(id),
                    user_id INTEGER REFERENCES users(id),
                    PRIMARY KEY (event_id, user_id)
                );
            ");
        } catch (PDOException $e) {
            die("Database initialization failed: " . $e->getMessage());
        }
    }


    public function getConnection() {
        return $this->conn;
    }
}
