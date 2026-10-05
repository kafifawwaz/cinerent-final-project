<?php
include './Config/database.php';
$query = "SELECT * FROM Films";
$qresult = mysqli_query($scann, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Film Catalog</h1>

    <div class="main">
        <?php while($Film = mysqli_fetch_assoc($result)) { ?>
            <div class="movie"
            <h3> <?php echo htmlspecialchars($film['title']); ?></h3>
                <p>Director: <?php echo htmlspecialchars($film['dierctor']); ?> </p>
                <p>Relase Year: <?php echo $film['releaseYear']; ?> </p>
                <p>Stock: <?php echo $film['stock']; ?> </p>
                <a href= "film_detail.php?id=<?php echo $film['id']; ?>">
                    View <Details> </Details>
                </a>
            </div>
        <?php } ?>
    </div>
</body>
</html>

