<?php 
session_start();
include './Config/database.php';

$error = '';
$succsess = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  $gender = $_POST['gender'] ?? '';
  $dateOfBirth = $_POST['dateOfBirth'];

  if(empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($gender) || empty($dateOfBirth)) {
    $error = "All fields are required";
  } else if(strlen($username) < 4 || strlen($username) > 20) {
    $error = "Username must be between 4 and 20 characters";
  } else if(!str_ends_with($email, '@gmail.com')) {
    $error = "Email must end with '@gmail.com'";
  } else if(strlen($password) < 8) {
    $error = "Password must be 8 characters";
  } else if($password !== $confirmPassword) {
    $error = "The password does not match";
  } else if($gender !== 'Male' && $gender !== 'Female') {
    $error = "Choose gender";
  } else if($strtotime($dateOfBirth) >= strtotime(date('Y-m-d'))) {
    $error = "Date of birth must be dates from past";
  } else {
    // amankan user input sebelum query ke dabes
    $safeUsername = mysqli_real_escape_string($scann, $username);
    $safeEmail = mysqli_real_escape_string($scann, $email);
    $safeGender = mysqli_real_escape_string($scann, $gender);
    $safeDoB = mysqli_real_escape_string($scann, $dateOfBirth);

    $checkQuery = "SELECT id FROM Users WHERE username = '$safeUsername'
                  OR email = '$safeEmail' LIMIT 1";
    $checkResult = mysqli_query($scann, $checkQuery);

    if(mysqli_num_rows($checkResult) > 0) {
      $error = "Username or Email already use";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $insertQuery = "INSERT INTO Users (username, email, password, gender, dateOfBirth, role)
                      VAlUES ('$safeUsername', '$safeEmail', '$hashedPassword', '$safeGender', 'safeDoB', 'member')";
      if (mysqli_query($scann,$insertQuery)) {
        $_SESSION['user_id'] = mysql_insert_id($scann);
        $_SESSION['username'] = $safeUsername;
        $_SESSION['role'] = 'member';

        header("Location: catalog.php");
        exit;
      } else {
        $error = "Something went wrong " . mysqli_error($scann);
      }
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