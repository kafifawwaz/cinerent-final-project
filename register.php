<?php 
session_start();
include './Config/database.php';

$error = '';
$succsess = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = mysqli_real_escape_string($scann, $_POST['username']);
  $email = mysqli_real_escape_string($scann, $_POST['email']);
  $password = $_POST['password'];
  $gender = mysqli_real_escape_string($scann, $_POST['gender']);
  $dateOfBirth = mysqli_real_escape_string($scann, $_POST['dateOfBirth']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username    = mysqli_real_escape_string($scann, $_POST['username']);
    $email       = mysqli_real_escape_string($scann, $_POST['email']);
    $password    = $_POST['password'];
    $gender      = mysqli_real_escape_string($scann, $_POST['gender']);
    $dateOfBirth = mysqli_real_escape_string($scann, $_POST['dateOfBirth']);

    // validate
    if (empty($username) || empty($email) || empty($password) || empty($dateOfBirth)) {
        $error = "Must be filled";
    } else {
        // hashpassword
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // query insert 
        $query = "INSERT INTO Users (username, email, password, gender, dateOfBirth, role) 
        VALUES ('$username', '$email', '$hashedPassword', '$gender', '$dateOfBirth', 'member')";
        
        if (mysqli_query($scann, $query)) {
            $success = "Registration successfull";
        } else {
            $error = "Registration failed" .mysqli_error($scann);
        }
    }
  }              
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Cinerent</title>
  <link rel="stylesheet" href="./assets/css/register.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/header.css">
</head>
<body>
    <?php 
  include './includes/header.php'; 
  ?>
  <div class="register-wrap">
    <form class="registerForm">
      <h1>CineRent <br>Create Account</h1>
      <p>Join CineRent and start renting today</p>
      <div>
        <label for="username">Username</label>
        <input type="text" placeholder="4-20 characters" id="username" name="username">
      </div>
      <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="example@gmail.com">
      </div>
      <div class="wrap">
        <div>
          <label for="password">Password</label>
          <input type="password" placeholder="Min. 8 characters" id="password" name="password">
        </div>
        <div>
          <label for="password">Confirm Password</label>
          <input type="password" placeholder="Repeat Password" id="password" name="password">
        </div>
      </div>
      <div class="wrap">
        <div>
          <label for="gender">Gender</label>
          <select name="gender" id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
  
        <div>
          <label for="dob">Date of Birth</label>
          <input type="date" name="dob" id="dob">
        </div>
      </div>
      <div>
      </div>
  
  
      
      <div>
        <input type="checkbox" name="agreement" id="agreement">
        <label for="agreement">I agree with all terms & condition</label>
      </div>
  
      <button type="submit" class="primary-button">Register</button>
    </form>
  </div>
</body>
</html>