<?php

namespace App\Controllers;
class HomeController
{
    public function view(int $id)
    {
        echo '<h1>Product ' . $id . '</h1>';
    }

}