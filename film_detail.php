<?php
include './Config/database.php';
$id = filter_input(INPUT_GET,'id', FILTER_VALIDATE_INT);

if($id){
    $query = "SELECT * FROM Films WHERE ID= $id = ?";
    $stmt = mysqli_prepare($scann, $query);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $qresult = mysqli_stmt_get_result($stmt);
    $film = mysqli_fetch_assoc($result);

} else {
    $film = null;
}
?>