<?php
require_once 'header.php';

$pages = $_SESSION['response']['pages'];
$movies = $_SESSION['response']['movies'];

?>
<div class="button-container">
    <label for="per_page">Per page: </label>
    <select name="per_page" id="per_page" onchange="window.location.href = '/movies?per_page=' + this.value;">
        <?php for ($i = 5; $i <= 100; $i *= 2): ?>
            <option value="<?= $i ?>" <?= ($pages['perPage'] == $i ? 'selected' : '') ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
</div>

<div class="button-container">
    <?php if ($pages['prev'] != $pages['current']): ?>
        <button class="pagination-button"
                onclick="window.location.href = '/movies?page=<?= $pages['prev'] ?>&per_page=<?= $pages['perPage'] ?>';">
            Previous
        </button>
    <?php endif; ?>

    <button class="pagination-button">Current page <?= $pages['current'] ?></button>

    <?php if ($pages['next'] != $pages['current']): ?>

        <button class="pagination-button"
                onclick="window.location.href = '/movies?page=<?= $pages['next'] ?>&per_page=<?= $pages['perPage'] ?>';">
            Next
        </button>
    <?php endif; ?>
</div>
<div class="container">
    <div class="row">
        <?php $count = 0; ?>
        <?php foreach ($movies as $movie): ?>
            <?php if ($count == 0): ?>
                <div class="row">
            <?php endif; ?>
            <div class="col">
                <div class="movie-card">
                    <div class="image-container">
                        <img class="movie-image"
                             src="<?php echo str_replace('/var/www/movies/public', '', $movie['image']); ?>"
                             alt="Movie Image">
                    </div>
                    <div class="movie-details">
                        <div class="movie-header">
                            <h2 class="movie-name">
                                <?php echo $movie['name']; ?>
                            </h2>
                            <div class="button-container">
                                <button class="see-button"
                                        onclick="window.location.href = '/movies/<?php echo $movie['id'] ?>';">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="24"
                                         height="24">
                                        <path fill="currentColor"
                                              d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                                    </svg>
                                </button>
                                <form id="deleteForm" action=/movies/<?php echo $movie['id']; ?>" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="delete-button" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                             height="24">
                                            <path fill="currentColor"
                                                  d="M19.707 4.293a1 1 0 0 0-1.414 0L12 10.586 5.707 4.293a1 1 0 0 0-1.414 1.414L10.586 12 4.293 18.293a1 1 0 0 0 1.414 1.414L12 13.414l6.293 6.293a1 1 0 0 0 1.414-1.414L13.414 12l6.293-6.293a1 1 0 0 0 0-1.414z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($count == 4): ?>
                </div>
                <?php $count = 0; ?>
            <?php else: ?>
                <?php $count++; ?>
            <?php endif ?>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
