<?php
include './Config/database.php';

$query = "SELECT * FROM Films ORDER BY RAND() LIMIT 6"; 
$result = mysqli_query($scann, $query);

if ($result) {
    $films = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} else {
    error_log("Database query failed: " . mysqli_error($scann));
    echo "Sorry, we couldn't load the films right now.";
}

?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
  </head>
  <body>
    <?php include './includes/header.php'; ?>

    <section class="hero">
      <h1>Welcome to <span>CineRent</span></h1>
      <div class="hero-inner">
        <p class="hero-description">
          Browse thousands of DVD & Blu-ray titles - rent, enjoy, return
        </p>
        <div class="button-wrapper">
          <button class="primary-button">Browse Catalog</button>
          <button class="third-button">Join Free</button>
        </div>
      </div>
    </section>

    <main class="main">
      <header class="main-header">
        <p class="main-featured">FEATURED TONIGHT</p>
        <h2 class="main-title">Randomly Picked For You</h2>
        <div class="border"></div>
      </header>

      <div class="movie-list">

        <?php
            foreach ($films as $film) {
        ?>
            <div class="movie">
                <img
                    src="https://upload.wikimedia.org/wikipedia/en/2/2e/Inception_%282010%29_theatrical_poster.jpg?utm_source=en.wikipedia.org&utm_campaign=parser&utm_content=thumbnail_unscaled"
                    alt=""
                />
                <div class="movie-details">
                    <div class="genre-list">
                        <span class="genre">Sci-Fi</span>
                    </div>
                    <div class="movie-title-wrapper">
                        <h3 class="movie-title"><?php echo htmlspecialchars($film['title'])?></h3>
                        <span class="movie-year"><?php echo htmlspecialchars($film['releaseYear'])?></span>
                    </div>

                    <div class="movie-info">
                        <div class="movie-stock">
                            <?php 
                                if($film['stock'] <= 3) {
                                    echo "Low Stock (" . $film['stock'] . ")";
                                } else if($film['stock'] > 3) {
                                    echo "Available (" . $film['stock'] . ")";
                                } else if($film['stock'] == 0) {
                                    echo 'Not available';
                                }
                            ?>
                        </div>
                        <a class="view-detail-button" href="<?php ?>">View Detail</a>
                    </div>
                </div>
            </div>
        <?php } ?>

      </div>
      <div class="button-wrapper">
        <button class="third-button">View Full Catalog -></button>
      </div>
    </main>

    <?php include './includes/footer.php'; ?>
  </body>
</html>
