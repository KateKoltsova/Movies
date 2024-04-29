<?php

namespace App\Controllers;

use App\Repositories\MovieRepository;
use PDOException;

class MovieController
{
    private $moviesRepository;

    public function __construct()
    {
        $env = parse_ini_file(__DIR__ . '/../../mysql.env');

        $host = $env['MYSQL_HOST'] ?? 'mysql';
        $database = $env['MYSQL_DATABASE'];
        $user = $env['MYSQL_USER'] ?? 'root';
        $password = $env['MYSQL_PASSWORD'] ?? ($env['MYSQL_ROOT_PASSWORD'] ?? '');

        $this->moviesRepository = new MovieRepository(
            'mysql:host=' . $host . ';dbname=' . $database,
            $user,
            $password
        );
    }

    /**
     * Display a listing of the movies.
     */
    public function index()
    {

    }

    /**
     * Show form for adding new movie
     */
    public function create()
    {

    }

    /**
     * Store a newly created movie to DB.
     */
    public function store($request)
    {
        try {
            $this->moviesRepository->addMovie(
                $request['name'],
                $request['releaseDate'],
                $request['description'],
                $request['image']
            );

            return jsonResponse([
                'success' => true,
                'message' => "Movie " . $request['name'] . " successfully added"
            ]);

        } catch (PDOException $e) {
            return jsonResponse([
                'success' => false,
                'message' => "Error of adding movie: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified movie.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified movie.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified movie in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified movie from DB.
     */
    public function destroy(string $id)
    {
        //
    }

}