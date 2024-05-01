<?php

namespace App\Controllers;

use App\Repositories\MovieRepository;
use App\Services\ImageService;
use Exception;
use Framework\Application;
use Framework\Exceptions\NotFoundException;

class MovieController
{
    private MovieRepository $moviesRepository;
    private ImageService $imageService;

    public function __construct()
    {
        $this->imageService = Application::getApp()->getComponent('imageService');

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
    public function index($request)
    {
        try {
            $page = (int)($request['page'] ?? 1);
            $perPage = (int)($request['per_page'] ?? 10);

            $movies = $this->moviesRepository->getAllMovies($page, $perPage);

            $lastPage = (int)($this->moviesRepository->getTotalPages($perPage));

            $prevPage = match (true) {
                $page - 1 <= 0 => 1,
                $page - 1 >= $lastPage => $lastPage - 1,
                default => $page - 1,
            };

            $nextPage = match (true) {
                $page + 1 >= $lastPage => $lastPage,
                default => $page + 1,
            };

            return jsonResponse([
                'success' => true,
                'pages' => [
                    'current' => $page,
                    'last' => $lastPage,
                    'prev' => $prevPage,
                    'next' => $nextPage,
                ],
                'movies' => $movies
            ]);

        } catch (Exception $e) {
            return jsonResponse([
                'success' => false,
                'message' => "Error of getting all movies: " . $e->getMessage()
            ], $e->getCode());
        }
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
            $image = $request['image'];
            $imagePath = $this->imageService->save($image);
            $request['image'] = $imagePath;

            $this->moviesRepository->addMovie($request);

            return jsonResponse([
                'success' => true,
                'message' => "Movie " . $request['name'] . " successfully added"
            ]);

        } catch (Exception $e) {
            return jsonResponse([
                'success' => false,
                'message' => "Error of adding movie: " . $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * Display the specified movie.
     */
    public function show(string $id)
    {
        try {
            $movie = $this->moviesRepository->getMovieById($id);

            return jsonResponse([
                'success' => true,
                'movie' => $movie
            ]);

        } catch (NotFoundException $e) {
            return jsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return jsonResponse([
                'success' => false,
                'message' => "Error of getting movie: " . $e->getMessage()
            ], $e->getCode());
        }
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
    public function update($request, string $id)
    {
        try {
            if (isset($request['image'])) {
                $image = $request['image'];
                $imagePath = $this->imageService->save($image);
                $request['image'] = $imagePath;

                $movie = $this->moviesRepository->getMovieById($id);
                $this->imageService->delete($movie['image']);
            }

            $this->moviesRepository->editMovie($id, $request);

            $movie = $this->moviesRepository->getMovieById($id);

            return jsonResponse([
                'success' => true,
                'message' => "Movie by id " . $id . " successfully updated",
                'movie' => $movie
            ]);

        } catch (NotFoundException $e) {
            return jsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return jsonResponse([
                'success' => false,
                'message' => "Error of updating movie: " . $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * Remove the specified movie from DB.
     */
    public function destroy(string $id)
    {
        try {
            $movie = $this->moviesRepository->getMovieById($id);
            $this->imageService->delete($movie['image']);

            $this->moviesRepository->deleteMovie($id);

            return jsonResponse([
                'success' => true,
                'message' => "Movie by id " . $id . " successfully deleted"
            ]);

        } catch (NotFoundException $e) {
            return jsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return jsonResponse([
                'success' => false,
                'message' => "Error of updating movie: " . $e->getMessage()
            ], $e->getCode());
        }
    }

}