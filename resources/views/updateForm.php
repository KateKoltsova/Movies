<?php
require_once 'header.php';

$movie = $_SESSION['response']['movie'];

if (isset($_SESSION['response']['oldMovieData'])) {
    $oldMovieData = $_SESSION['response']['oldMovieData'];
    $errors = $_SESSION['errors'];
}

?>

<div class="form-container">
    <h1>Edit Movie</h1>
    <form action="<?php echo '/movies/' . $movie['id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PATCH">

        <label for="name">Name:</label>
        <input type="text" id="name"
               name="name"
               value="<?php echo (!empty($oldMovieData) && isset($oldMovieData['name'])) ? $oldMovieData['name'] : $movie['name']; ?>">
        <?php
        if (!empty($oldMovieData) && !empty($errors['name'])) {
            foreach ($errors['name'] as $nameError) {
                echo '<div class="error-message">' . $nameError . '</div>';
            }
        }
        ?>

        <label for="releaseDate">Release Date:</label>
        <input type="date" id="releaseDate" name="releaseDate"
               value="<?php echo (!empty($oldMovieData) && isset($oldMovieData['releaseDate'])) ? $oldMovieData['releaseDate'] : $movie['releaseDate'] ?>">
        <?php
        if (!empty($oldMovieData) && !empty($errors['releaseDate'])) {
            foreach ($errors['releaseDate'] as $releaseDateError) {
                echo '<div class="error-message">' . $releaseDateError . '</div>';
            }
        }
        ?>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"
                  cols="50"><?php echo (!empty($oldMovieData) && isset($oldMovieData['description'])) ? $oldMovieData['description'] : $movie['description'] ?></textarea>
        <?php
        if (!empty($oldMovieData) && !empty($errors['description'])) {
            foreach ($errors['description'] as $descriptionError) {
                echo '<div class="error-message">' . $descriptionError . '</div>';
            }
        }
        ?>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php
        if (!empty($oldMovieData) && !empty($errors['image'])) {
            foreach ($errors['image'] as $imageError) {
                echo '<div class="error-message">' . $imageError . '</div>';
            }
        }
        ?>
        <div class="image">
            <img class="image" src="<?php echo str_replace('/var/www/movies/public', '', $movie['image']) ?>"
                 alt="Image">
        </div>

        <input class="button green" type="submit" value="Submit">
    </form>
</div>
</body>
</html>