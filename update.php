<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id_siswa'])) {
    die("Gak valid, eweuh id_siswa na.");
}

$id_siswa = $_GET['id_siswa'];

$query_siswa = "SELECT * FROM siswa WHERE id_siswa = $id_siswa";
$result_siswa = mysqli_query($conn, $query_siswa);
if ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
    $nama_siswa = $row_siswa['nama'];
    $id_kelas = $row_siswa['id_kelas'];
} else {
    die("Gak ada data.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];

    $update_query = "UPDATE siswa SET nama = '$nama', id_kelas = $id_kelas WHERE id_siswa = $id_siswa";
    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}

$result_kelas = mysqli_query($conn, "SELECT * FROM kelas");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Update Siswa</h2>

    <form method="POST">
        <label>Nama: <input type="text" id="nama" name="nama" value="<?php echo $nama_siswa ?>" required></label><br>

        <label>Kelas: <select id="id_kelas" name="id_kelas" required>
                <?php
                while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                    $selected = ($row_kelas['id_kelas'] == $id_kelas) ? 'selected' : '';
                    echo "<option value='{$row_kelas['id_kelas']}' $selected>{$row_kelas['kelas']}</option>";
                }
                ?>
            </select></label><br><br>

        <button type="submit">Update</button>
    </form>

    <br><a href="index.php">Kembali ke rumah? home.</a>
</body>

</html>