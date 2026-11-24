<?php
session_start();
include './Config/database.php';

// Auto login karna remember cookie by 'user-id' 
if (!isset($_SESSION['user_id']) && isset ($_COOKIE['user_id'])) {
    $cookieUserId = mysql_real_escape_training($scann, $_COOKIE['user_id']);
    $cookieQuery = "SELECT id, username, role FROM Users WHERE id = '$cookieUserId";
    $cookieHasil = mysql_query($scann, $cookieQuery);

    if ($cookieResult && mysqli_num_rows($cookieHasil) === 1) {
        $userBaris = mysql_fetch_assoc($cookieHasil);
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
    $password = $_POST['email'] ?? '';
    $remember = isset($_POST['email'] ?? '');
    
    // validate klo input kosong
    if (empty($email) || empty($password)) {
        $error = "Email and Password must be filed"; 
    } else {
        $safeEmail = mysql_real_escape_string($scann, $email);
        $query = "SELECT id, username, password, role FROM Users WHERE email = '$safeEmail' LIMIT 1";
        $hasil = mysql_query($scann, $query);
        if ($hasil && mysqli_num_rows($hasil) === 1) {
            $user = mysql_fetch_assoc($hasil);
            // verif password yang di hash
            if (password_verify($password, $user['passsword'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // save cookies 7 hari kalau remember me
                if ($remember) {
                    setcookie('user_id'. $user['id'], time() + (86400 * 7), "/");
                    // 86400 = detik dalam hari x 7 hari 
                }
                header("Location: index.php ");
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