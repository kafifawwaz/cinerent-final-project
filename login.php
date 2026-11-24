<?php
session_start();
include './Config/database.php';

// Auto login karna remember cookie by 'user-id' 
if (!isset($_SESSION['user_id']) && isset ($_COOKIE['user_id'])) {
    $cookieUserId = mysqli_real_escape_string($scann, $_COOKIE['user_id']);
    $cookieQuery = "SELECT id, username, role FROM Users WHERE id =" . $cookieUserId;
    $cookieHasil = mysqli_query($scann, $cookieQuery);

    if ($cookieHasil && mysqli_num_rows($cookieHasil) === 1) {
        $userBaris = mysqli_fetch_assoc($cookieHasil);
        $_SESSION['user_id'] = $userBaris['id'];
        $_SESSION['username'] = $userBaris['username'];
        $_SESSION['role'] = $userBaris['role'];
    }
}

// ini kalo udh login lgsg ke dahsboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// variable dummy
$error = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // validate klo input kosong
    if (empty($email) || empty($password)) {
        $error = "Email and Password must be filed"; 
    } else {
        $safeEmail = mysqli_real_escape_string($scann, $email);
        $query = "SELECT id, username, password, role FROM Users WHERE email = '$safeEmail' LIMIT 1";
        $hasil = mysqli_query($scann, $query);
        if ($hasil && mysqli_num_rows($hasil) === 1) {
            $user = mysqli_fetch_assoc($hasil);
            // verif password yang di hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // save cookies 7 hari kalau remember me
                if ($remember) {
                    setcookie('user_id', $user['id'], time() + (86400 * 7), "/");
                    // 86400 = detik dalam hari x 7 hari 
                }
                header("Location: index.php");
                exit;
            } else {
                $error = "Wrong email or password";
            }
        } else {
            $error = "Wrong email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CineRent - Login</title>
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/login.css">
</head>
<body>
    <?php include './includes/header.php'; ?>

    <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form action="login.php" method="POST" class="signin-form">
        <div class="header">
            <h1 class="login-h2">Cinerent</h1>
            <h2 class="login-h3">Sign In</h2>
            <p class="login-p">Access your CineRent account</p>
        </div>
        <div class="input-wrap">
            <label>Email</label>
            <input type="email" name="email" placeholder="your@gmail.com" required><br><br>
        </div>
        <div class="input-wrap">
            <label>Password</label>
            <input type="password" name="password" required placeholder="********">
        </div>

        <div class="input-wrap">
            <label>
                <input type="checkbox" name="remember"> Remember me for 7 days
            </label>
        </div>

        <button type="submit" class="primary-button">Sign in</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>

    <?php include './includes/footer.php'; ?>
</body>
</html>
