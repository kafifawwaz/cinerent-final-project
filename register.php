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
  } else if(strtotime($dateOfBirth) >= strtotime(date('Y-m-d'))) {
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
  <title>Register - CInerent</title>
  <link rel="stylesheet" href="./assets/css/register.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/header.css">
  <link rel="stylesheet" href="./assets/css/footer.css">
</head>
<body>
  <?php include './includes/header.php'; ?>

  <div class="register-wrap">
    <form action="register.php" method="POST" class="registerForm">
      <h1>Cinerent</h1>
      <h2>Create Account</h2>
      <p class="join">Join CineRent and start renting today</p>

      <!-- error message nya nih -->
      <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo $error;?></p>
      <?php endif;?>  

      <div class="input-wrap">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="4-20 characters" value="<?php echo htmlspecialchars($username ?? ''); ?>">
      </div>

      <div class="input-wrap">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($email ?? ''); ?>">
      </div>

      <div class="wrap">
        <div class="input-wrap">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Min. 8 characters" value>
        </div>
        <div class="input-wrap">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password"> 
        </div>
      </div>

      <div class="wrap">
        <div class="input-wrap">
          <label for="gender">Gender</label>
          <select name="gender" id="gender">
            <option value="">-- Select --</option>
            <option value="Male" <?php echo (($gender ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (($gender ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
          </select>
        </div>

        <div class="input-wrap">
          <label for="dob">Date of Birth</label>
          <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($dob ?? ''); ?>">
        </div>
      </div>  

      <button type="submit" class="primary-button">Create Account</button>

      <p class="login-redirect">
        Already have an account? <a href="login.php">Sign in</a>
      </p>
    </form>  
  </div>

  <?php include './includes/footer.php'; ?>
</body>
</html>