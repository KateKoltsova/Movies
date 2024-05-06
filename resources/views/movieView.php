<?php
require_once 'header.php';

$movie = $_SESSION['response']['movie'];

?>
<div class="movie-container">
    <div class="movie-info">
        <div class="image">
            <img src="<?php echo str_replace('/var/www/movies/public', '', $movie['image']) ?>" alt="Image">
        </div>

        <div class="details">
            <div class="movie-header">
                <h2 class="movie-name">
                    <?php echo $movie['name']; ?>
                </h2>
                <div class="button-container">
                    <button class="edit-button"
                            onclick="window.location.href = '/movies/<?php echo $movie['id'] ?>/edit';">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24">
                            <path fill="currentColor"
                                  d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                        </svg>
                    </button>
                    <form id="deleteForm" action=/movies/<?php echo $movie['id']; ?>" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="delete-button" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="24" height="24">
                                <path fill="currentColor"
                                      d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <?php
            echo '<label>Release Date:</label>';
            echo '<span>' . $movie['releaseDate'] . '</span>';
            echo '<br>';
            echo '<br>';
            echo '<label>Description:</label>';
            echo '<span>' . $movie['description'] . '</span>';
            ?>
        </div>
    </div>
</div>

</body>
</html>
