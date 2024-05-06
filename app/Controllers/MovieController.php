<?php

namespace App\Controllers;

use App\Repositories\MovieRepository;
use App\Services\ImageService;
use App\Services\Validator;
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
            $password,
            $database
        );
    }

    /**
     * Display a listing of the movies.
     */
    public function index($request)
    {
        try {
            $page = $request['page'] ?? 1;
            $perPage = $request['per_page'] ?? 10;

            $movies = $this->moviesRepository->getAllMovies($page, $perPage);

            $lastPage = $this->moviesRepository->getTotalPages($perPage);

            $prevPage = match (true) {
                $page - 1 <= 0 => 1,
                $page - 1 >= $lastPage => $lastPage - 1,
                default => $page - 1,
            };

            $nextPage = match (true) {
                $page + 1 >= $lastPage => $lastPage,
                default => $page + 1,
            };

            $_SESSION['response']['pages'] = [
                'perPage' => $perPage,
                'current' => (int)$page,
                'last' => $lastPage,
                'prev' => $prevPage,
                'next' => $nextPage,
            ];
            $_SESSION['response']['movies'] = $movies;

            $viewPath = Application::getApp()->resources['views'] . 'moviesList.php';

            ob_start();
            include $viewPath;
            $content = ob_get_clean();

            echo $content;

        } catch (Exception $e) {
            $_SESSION['response']['error'] = "Error of getting all movies: " . $e->getMessage();
            header('Location: /');
            exit();
        }
    }

    /**
     * Show form for adding new movie
     */
    public function create()
    {
        $viewPath = Application::getApp()->resources['views'] . 'createForm.php';

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        echo $content;
    }

    /**
     * Store a newly created movie to DB.
     */
    public function store($request)
    {
        try {
            $errors = Validator::validateMovieData($request);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['response']['oldMovieData'] = $request;
                header('Location: /movies/create');
                exit();
            } else {
                unset($_SESSION['errors'], $_SESSION['response']['oldMovieData']);
            }

            $image = $request['image'];
            $imagePath = $this->imageService->save($image);
            $request['image'] = $imagePath;

            $this->moviesRepository->addMovie($request);

            $_SESSION['response']['message'] = "Movie " . $request['name'] . " successfully added";
            header('Location: /movies/create');
            exit();

        } catch (Exception $e) {
            $_SESSION['response']['error'] = "Error of adding movie: " . $e->getMessage();
            header('Location: /movies/create');
            exit();
        }
    }

    /**
     * Display the specified movie.
     */
    public function show(string $id)
    {
        try {
            $movie = $this->moviesRepository->getMovieById($id);

            $_SESSION['response']['movie'] = $movie;

            $viewPath = Application::getApp()->resources['views'] . 'movieView.php';

            ob_start();
            include $viewPath;
            $content = ob_get_clean();

            echo $content;

        } catch (Exception $e) {
            $_SESSION['response']['error'] = "Error of getting movie: " . $e->getMessage();
            header('Location: /movies');
            exit();
        }
    }

    /**
     * Show the form for editing the specified movie.
     */
    public function edit(string $id)
    {
        try {
            $movie = $this->moviesRepository->getMovieById($id);

            $_SESSION['response']['movie'] = $movie;

            $viewPath = Application::getApp()->resources['views'] . 'updateForm.php';

            ob_start();
            include $viewPath;
            $content = ob_get_clean();

            echo $content;

        } catch (Exception $e) {
            $_SESSION['response']['error'] = "Error of getting movie: " . $e->getMessage();
            header('Location: /movies/' . $id);
            exit();
        }
    }

    /**
     * Update the specified movie in storage.
     */
    public function update($request, string $id)
    {
        try {
            $errors = Validator::validateMovieData($request);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['response']['oldMovieData'] = $request;
                header('Location: /movies/' . $id . '/edit');
                exit();
            } else {
                unset($_SESSION['errors'], $_SESSION['response']['oldMovieData']);
            }

            if (isset($request['image']) && !empty($request['image']['name'])) {
                $image = $request['image'];
                $imagePath = $this->imageService->save($image);
                $request['image'] = $imagePath;

                $movie = $this->moviesRepository->getMovieById($id);
                $this->imageService->delete($movie['image']);
            }

            $this->moviesRepository->editMovie($id, $request);

            $_SESSION['response']['message'] = "Movie " . $id . " successfully updated";
            header('Location: /movies/' . $id);
            exit();

        } catch (Exception $e) {
            $_SESSION['response']['error'] = "Error of updating movie: " . $e->getMessage();
            header('Location: /movies/' . $id);
            exit();
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

            $_SESSION['response']['message'] = "Movie by id " . $id . " successfully deleted";
            header('Location: /movies');
            exit();
        } catch (Exception $e) {
            $_SESSION['response']['error'] = "Error of deleting movie: " . $e->getMessage();
            header('Location: /movies/' . $id);
            exit();
        }
    }
}
