<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movies</title>
    <link rel="stylesheet" href="/css/styles.css">

</head>
<body>
<header class="header">
    <div class="container">
        <h1>Movies</h1>
        <nav>
            <ul>
                <?php

                $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

                if ($url !== '/') {
                    echo '<li><button class="button" onclick="window.location.href = \'/\';">Home</button></li>';
                }

                if ($url !== '/movies/create') {
                    echo '<li><button class="button" onclick="window.location.href = \'/movies/create\';">Create Movie</button></li>';
                }

                if ($url !== '/movies') {
                    echo '<li><button class="button" onclick="window.location.href = \'/movies\';">Movies list</button></li>';
                }

                ?>
            </ul>
        </nav>
    </div>
</header>

<?php

if ($url !== '/') {
    if (isset($_SESSION['response']['message'])) {
        echo '<div class="message-container">' . $_SESSION['response']['message'] . '</div>';
        unset($_SESSION['response']['message']);
    }
}

if ($url !== '/') {
    if (isset($_SESSION['response']['error'])) {
        echo '<div class="error-message">' . $_SESSION['response']['error'] . '</div>';
        unset($_SESSION['response']['error']);
    }
}

?>