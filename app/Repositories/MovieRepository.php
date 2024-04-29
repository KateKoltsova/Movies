<?php

namespace App\Repositories;

use PDO;
use PDOException;

class MovieRepository
{
    private $pdo;

    public function __construct($dsn, $username, $password)
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->createTableIfNotExists();
        } catch (PDOException $e) {
            die("Error of connection to database: " . $e->getMessage());
        }
    }

    private function createTableIfNotExists()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS movies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                releaseDate DATE NOT NULL,
                description TEXT(1000),
                image VARCHAR(255) NOT NULL
            )
        ";

        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            die("Error of creating table: " . $e->getMessage());
        }
    }

    public function addMovie($name, $releaseDate, $description, $image)
    {
        $sql = "INSERT INTO movies (name, releaseDate, description, image) VALUES (:name, :releaseDate, :description, :image)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':releaseDate', $releaseDate);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        $stmt->execute();
    }

    public function editMovie($id, $name, $releaseDate, $description, $image)
    {
        $sql = "UPDATE movies SET name = ?, releaseDate = ?, description = ?, image = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute([$id, $name, $releaseDate, $description, $image]);
            echo "Movie successfully edited";
        } catch (PDOException $e) {
            die("Error of editing movie: " . $e->getMessage());
        }
    }

    public function deleteMovie($id)
    {
        $sql = "DELETE FROM movies WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute([$id]);
            echo "Movie successfully deleted";
        } catch (PDOException $e) {
            die("Error of deleting movie: " . $e->getMessage());
        }
    }
}