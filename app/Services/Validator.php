<?php

namespace App\Services;

class Validator
{
    public static function validateMovieData($data)
    {
        $errors = [];

        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!preg_match('~^/movies/\d+$~', $url)) {
            if (empty($data['name'])) {
                $errors['name'][] = 'Name is required';
            }

            if (empty($data['releaseDate'])) {
                $errors['releaseDate'][] = 'Release date is required';
            }

            if (empty($data['image']['name'])) {
                $errors['image'][] = 'Image is required';
            }
        }

        if (!empty($data['name']) && strlen($data['name']) > 255) {
            $errors['name'][] = 'Name must be less than 255 characters';
        }

        if (!empty($data['releaseDate']) && !strtotime($data['releaseDate'])) {
            $errors['releaseDate'][] = 'Invalid release date format';
        }

        if (!empty($data['description']) && strlen($data['description']) > 1000) {
            $errors['description'][] = 'Description must be less than 1000 characters';
        }

        if (!empty($data['image']['name']) && !in_array($data['image']['type'], ['image/jpeg', 'image/png'])) {
            $errors['image'][] = 'Image must be a JPEG or PNG file';
        }

        return $errors;
    }
}
