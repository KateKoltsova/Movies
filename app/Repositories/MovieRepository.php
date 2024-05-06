<?php

namespace App\Repositories;

use Framework\Exceptions\NotFoundException;
use PDO;
use PDOException;

class MovieRepository
{
    private $pdo;

    public function __construct($dsn, $username, $password, $database)
    {
        $this->pdo = new PDO($dsn, $username, $password);
        $this->createSchemaIfNotExists($database);
        $this->createTableIfNotExists();
    }

    private function createSchemaIfNotExists($database)
    {
        $sql = "CREATE SCHEMA IF NOT EXISTS " . $database;

        return $this->pdo->exec($sql);
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

        return $this->pdo->exec($sql);
    }

    public function getAllMovies($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM movies ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $movies = $stmt->fetchALL(PDO::FETCH_ASSOC);

        if (empty($movies)) {
            throw new NotFoundException("Movies page not found");
        }

        return $movies;
    }

    public function getTotalRecords()
    {
        $sql = "SELECT COUNT(*) as total FROM movies";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalPages($perPage)
    {
        $totalRecords = $this->getTotalRecords();
        $lastPageNumber = ceil($totalRecords / $perPage);

        return $lastPageNumber;
    }

    public function getMovieById($id)
    {
        $sql = "SELECT * FROM movies WHERE id = $id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($movie)) {
            throw new NotFoundException("Movie by id $id not found");
        }

        return $movie;
    }

    public function addMovie($params)
    {
        $sql = "INSERT INTO movies (name, releaseDate, description, image) VALUES (:name, :releaseDate, :description, :image)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':name' => $params['name'],
            ':releaseDate' => $params['releaseDate'],
            ':description' => $params['description'],
            ':image' => $params['image']
        ]);
    }

    public function editMovie($id, $params)
    {
        $movie = $this->getMovieById($id);

        if (empty($movie)) {
            throw new NotFoundException("Movie by id $id not found");
        }

        $sql = "UPDATE movies SET ";
        $executeParams = [];

        foreach ($params as $key => $value) {
            if ($key == 'id') {
                continue;
            }

            if ($key == 'image' && isset($value['name'])) {
                continue;
            }

            if (isset($movie[$key])) {
                $sql .= "$key = :$key, ";
                $executeParams[":$key"] = $value;
            }
        }

        $sql = rtrim($sql, ', ');
        $sql .= " WHERE id = :id";
        $executeParams[":id"] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($executeParams);
    }

    public function deleteMovie($id)
    {
        $movie = $this->getMovieById($id);

        if (empty($movie)) {
            throw new NotFoundException("Movie by id $id not found");
        }

        $sql = "DELETE FROM movies WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
