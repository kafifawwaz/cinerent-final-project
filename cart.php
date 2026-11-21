<?php

session_start();
include './Config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id =$_SESSION('user_id');
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filmid = mysqli_real_escape_string($scann, $_POST['filmId']);

    if (isset($_POST['add_to_cart'])) {
        // cek dlu stok si film ada apa ngga
        $stockQuery = "SELECT stock FROM Films WHERE id = '$filmid'"; 
        $stockResult = mysqli_query($scann, $stockQuery);
        $filmData = mysqli_fetch_assoc($stockResult);
        
        
        if ($filmData && $filmData['stock'] > 0) {
            $checkCart = "SELECT * FROM Cart WHERE userId = '$user_id' AND filmId = '$filmid'";
            $cartResult = mysqli_query($scann, $checkCart);

            if (mysqli_num_rows($cartResult) == 0) {
                $insertQuery = "INSERT INTO Cart (userId, filmId) VALUES ('$user_id', '$filmid')";
                mysqli_query($scann, $insertQuery);
                $message = "Film added to cart";
            } else {
                $message = "Film already in cart";
            }
        } else {
            $message = "Film is out of stock";
        }
    }
}
?> 