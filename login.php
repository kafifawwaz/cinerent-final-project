<?php
session_start();
include './Config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($scann, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } else {
        $query = "SELECT * FROM Users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($scann, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];         
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: index.php");
                exit; // langsung ke dashboard setelah login
            } else {
                $error = "Wrong Password!";
            }
        } else {
            $error = "Email not registered!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CineRent - Login</title>
</head>
<body>
    <h2>Login ke CineRent</h2>

    <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <!-- Form Login -->
    <form action="login.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Register</a></p>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Cinerent</title>
</head>
<body>
  <?php include './includes/header.php'; ?>

  <form action="registerForm">
    <h1>Login</h1>
  </form>
</body>
</html>