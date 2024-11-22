<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $conn->query("INSERT INTO user (username, password) VALUES ('" . $_POST['username'] . "', '" . $_POST['password'] . "')");
    } else {
        $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "'");
        if (mysqli_num_rows($query) > 0) {
            $_SESSION['user'] = $_POST['username'];
            header("Location: index.php");
            exit;
        } else {
            $err = "<i style='color: red'>Username atau Password salah.</i>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Login</h1>
    <p><?php echo (!empty($err) ? $err  : "Silahkan login ke akun anda!"); ?></p>
    <form method="POST">
    <div>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" required>
    </div>

    <div>
        <label for="password">Password:&nbsp;</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <input type="submit" value="Login">
        <input type="submit" name="register" value="Register">
    </div>
</form>
</body>

</html>