<?php
require_once 'header.php';

if (isset($_SESSION['response']['oldMovieData'])) {
    $oldMovieData = $_SESSION['response']['oldMovieData'];
    $errors = $_SESSION['errors'];
}

?>

<div class="form-container">
    <h1>Create Movie</h1>
    <form action="/movies" method="POST" enctype="multipart/form-data">
        <label for="name">Name <span>*</span>:</label>
        <input type="text" id="name" name="name"
               required value="<?php echo (!empty($oldMovieData)) ? $oldMovieData['name'] : ''; ?>">
        <?php
        if (!empty($oldMovieData) && !empty($errors['name'])) {
            foreach ($errors['name'] as $nameError) {
                echo '<div class="error-message">' . $nameError . '</div>';
            }
        }
        ?>

        <label for="releaseDate">Release Date <span>*</span>:</label>
        <input type="date" id="releaseDate" name="releaseDate"
               required value="<?php echo (!empty($oldMovieData)) ? $oldMovieData['releaseDate'] : ''; ?>">
        <?php
        if (!empty($oldMovieData) && !empty($errors['releaseDate'])) {
            foreach ($errors['releaseDate'] as $releaseDateError) {
                echo '<div class="error-message">' . $releaseDateError . '</div>';
            }
        }
        ?>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"
                  cols="50"><?php echo (!empty($oldMovieData)) ? $oldMovieData['description'] : ''; ?></textarea>
        <?php
        if (!empty($oldMovieData) && !empty($errors['description'])) {
            foreach ($errors['description'] as $descriptionError) {
                echo '<div class="error-message">' . $descriptionError . '</div>';
            }
        }
        ?>

        <label for="image">Image <span>*</span>:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <?php
        if (!empty($oldMovieData) && !empty($errors['image'])) {
            foreach ($errors['image'] as $imageError) {
                echo '<div class="error-message">' . $imageError . '</div>';
            }
        }
        ?>

        <input class="button green" type="submit" value="Submit">
    </form>
</div>

</body>
</html>