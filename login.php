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
</head>

<body>
    <h1>Login</h1>
    <p><?php echo (!empty($err) ? $err  : "Silahkan login ke akun anda!"); ?></p>
    <table>
        <form method="POST">
            <tr>
                <td>Username</td>
                <td>:</td>
                <td><input type="text" name="username" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td>:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td><input type="submit" value="Login"></td>
                <td></td>
                <td><input type="submit" name="register" value="Register"></td>
            </tr>
        </form>
    </table>
</body>

</html>