<?php

$host = 'localhost';
$user = 'root';
$pass = "";
$db = 'cinerent';

$scann = mysqli_connect($host,$user,$pass,$db);

if (!$scann) {
    die("Connection Failed: ". mysqli_connect_error());
}
?>