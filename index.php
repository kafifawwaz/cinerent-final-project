<?php
include './Config/database.php';

$query: "SELECT * FROM Films ORDER BY RAND() 6";
$result: mysqli_query($scann; $query);
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