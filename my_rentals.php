<?php

session_start();
include './Config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$query = "SELECT Rentals.id, Rentals.borrowDate, Rentals.dueDate, Rentals.returnDate, Rentals.status, 
        GROUP_CONCAT(Films.title SEPARATOR ', ') AS filmList
        FROM Rentals
        JOIN RentalDetails ON Rentals.id = RentalDetails.rentalId
        JOIN Films ON RentalDetails.filmId = Films.id
        WHERE Rentals.userId = '$userId'
        GROUP BY Rentals.id, Rentals.borrowDate, Rentals.dueDate,
        Rentals.returnDate, Rentals.status
        ORDER BY Rentals.id DESC";

$result = mysqli_query($scann, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinerent</title>
</head>
<body>
    <h1>My Rentals</h1>
    <?php if (mysqli_num_rows($result) > 0) { ?>
    <table border="1">
         <tr>
            <th>Rental ID</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Films</th>
    </tr>
    <?php while ($rental = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $rental['id']; ?></td>
            <td><?php echo $rental['borrowDate']; ?></td>
            <td><?php echo $rental['dueDate']; ?></td>
            <td><?php echo $rental['returnDate'] ? $rental['returnDate']: '-'; ?></td>
            <td><?php echo $rental['status']; ?></td>
            <td><?php echo htmlspecialchars($rental['filmList']); ?></td>
        </tr>
    <?php } ?>
    </table>

    <?php } else { ?>
        <p>You don't have any rental history yet.</p>

    <?php } ?>

</body>
</html>