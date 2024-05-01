<?php

namespace App\Services;

use Exception;

class ImageService
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function save($image)
    {
        if (!file_exists($this->path)
            && !mkdir($this->path, 0777, true)) {
            throw new Exception("Failed to create directory: " . $this->path);
        }

        $path = $this->path . rand(10000, 99999) . $image['name'];

        if (move_uploaded_file($image['tmp_name'], $path)) {
            return $path;
        }

        throw new Exception('Saving image is failed', 500);
    }

    public function delete($path)
    {
        if (file_exists($path)) {

            if (unlink($path)) {
                return true;
            }
        }

        return false;
    }
}
