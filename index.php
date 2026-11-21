<?php
include './Config/database.php';

$query = "SELECT * FROM Films ORDER BY RAND() LIMIT 6"; 
$result = mysqli_query($scann, $query);

if ($result) {
    while ($film = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($film['title']) . "</h3>";
        if (isset($film['genre'])) {
            echo "<p>Genre: " . htmlspecialchars($film['genre']) . "</p>";
        }
        echo "</div><hr>";
    }   
    mysqli_free_result($result);
} else {
    error_log("Database query failed: " . mysqli_error($scann));
    echo "Sorry, we couldn't load the films right now.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinerent</title>
</head>
<body>
    <h1>Cinerent</h1>
    <div class="Films">
        <?php while ($film = mysqli_fetch_assoc($result)) { ?>
            <div class="film">
                <h3><?php echo $film['title']; ?></h3>
                <p> Genre: <?php echo $film['genreId']; ?></p>
                <a href="film_detail_php?id=<? echo $film['id'] ?>">
                    View Detail
                </a>
            </div>
        <?php } ?>
    </div>
</body>
</html>