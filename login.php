<?php
session_start();
include './Config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        $error = "Email and Password must be filed";
    } else {
        $safeEmail = mysqli_real_escape_string($scann, $email);
        $query = "SELECT * FROM Users WHERE email = '$safeEmail' LIMIT 1";
        $result = mysqli_query($scann, $query);
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                // set session active
                $_SESSION['user_id'] = $user['id'];         
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                // fitur "remember me" / cookies
                if (isset($_POST['remember'])) {
                    setcookie('user_id', $user['id'], time() + (86400 * 7), "/");
                }
                // redirect ke home page lagi (index.php)
                header("Location: index.php");
                exit; 
            } else {
                $error = "Wrong Password";
            }
        } else {
            $error = "Not registered";
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
    <form action="login.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" placeholder="your@gmail.com" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>
            <input type="checkbox" name="remember"> Remember me for 7 days
        </label><br><br>    

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