<?php

session_start();
include './Config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$usernameError = "";
$emailError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $gender = $_POST['gender'] ?? '';
    $dateOfBirth = trim($_POST['dateOfBirth']);

    if (empty($username)) {
        $usernameError = "Username is required.";
    } elseif (strlen($username) < 4 || strlen($username) > 20){
        $usernameError = "Username must be 4 - 20 characters. ";
    } else {
        $checkUsernameQuery = "SELECT id FROM Users WHERE username = ? AND id !=?";
        $checkUsername = mysqli_prepare($scann, $checkUsernameQuery);

        mysqli_stmt_bind_param($checkUsername, "si", $username, $userId);
        mysqli_stmt_execute($checkUsername);

        $checkUsernameResult = mysqli_stmt_get_result($checkUsername);

        if (mysqli_num_rows($checkUsernameResult) > 0){
            $usernameError = "Username already exists";
        }
    }

    if (empty($email)) {
        $emailError = "Email is required";
    } elseif (substr($email, -10) != '@gmail.com') {
        $emailError = "Email must end wiith @gmail.com";
    } else {
        $checkEmailQuery = "SELECT id FROM Users WHERE email = ? AND id != ?";

        $checkEmail = mysqli_prepare($scann, $checkEmailQuery);

        mysqli_stmt_bind_param($checkEmail, "si", $email, $userId);
        mysqli_stmt_execute($checkEmail);

        $checkEmailResult = mysqli_stmt_get_result($checkEmail);

        if(mysqli_num_rows($checkEmailResult) > 0) {
            $emailError = "Email already exists";
        }
    }
}

$query = "SELECT * FROM Users WHERE id = '$userId'";
$result = mysqli_query($scann, $query);

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinerent</title>
</head>
<body>
    <h1>My Profile</h1>
    <h2>Update Profile Info</h2>

    <form method ="POST">

    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username"
        value="<?php echo htmlspecialchars($user['username']); ?>">
        <p class="username-error-message">
            <?php echo $usernameError; ?>
        </p>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"
        value="<?php echo htmlspecialchars($user['email']); ?>">
        <p class="email-error-message">
            <?php echo $emailError; ?>
        </p>
    </div>

    <div>
        <label>Gender</label>
        <div class ="radio-group">

            <input type="radio" name="gender" id="male" value="Male"
            <?php if ($user['gender'] == 'Male') echo 'checked'; ?>>
            <label for="male">Male</label>

            <input type="radio" name="gender" id="female" value="Female"
            <?php if ($user['gender'] == 'Female') echo 'checked'; ?>>
            <label for="female">Female</label>
        </div>
        <p class="error-message" id="gender-error"></p>
    </div>

    <div>
         <label for="dateOfBirth">Date Of Birth</label>
         <input type="date" id="dateOfBirth" name="dateOfBirth"
         value="<?php echo $user['dateOfBirth']; ?>">
          <p class="error-message" id="DOB-error"></p>
    </div>

    <button type="submit">Update Profile</button>

    </form>
    
</body>
</html>