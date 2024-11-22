<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$detail = isset($_GET['detail']) && $_GET['detail'] == '1';
$filter_column = $_GET['filter_column'] ?? '';
$filter_value = $_GET['filter_value'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$query = "SELECT siswa.id_siswa, siswa.nama, siswa.tanggal_lahir, kelas.kelas
          FROM siswa
          INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas";

if (isset($_GET['filter_like']) && $filter_column && $filter_value) {
    $query .= " WHERE $filter_column LIKE '%$filter_value%'";
} elseif (isset($_GET['filter_between']) && $start_date && $end_date) {
    $query .= " WHERE siswa.tanggal_lahir BETWEEN '$start_date' AND '$end_date'";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM siswa WHERE id_siswa = " . $_GET['delete']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function generateTable($result, $action)
{
    if ($result->num_rows > 0) {
        $columns = $result->fetch_fields();
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>{$column->name}</th>";
        }
        echo $action ? "<th colspan='2'>Action</th>" : "";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>{$value}</td>";
            }
            if ($action) {
                echo "<td><a href='update.php?id_siswa={$row['id_siswa']}'>Update</a></td>";
                echo "<td><a href='?delete={$row['id_siswa']}' onclick='return confirm(\"Apakah anda yakin ingin menghapus data ini? :o\")'>Delete</a></td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='100%'>Data kosong.</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home Page</title>
</head>

<body>
    User: <?= $_SESSION['user'] ?><form method="POST"><button type="submit" name="logout">Logout</button></form><br>

    <form method="GET">
        <label for="detail">Maw liat detailnya?</label>
        <input type="checkbox" id="detail" name="detail" value="1" <?= $detail ? 'checked' : ''; ?> onchange="this.form.submit()">
    </form>

    <form method="GET">
        <label for="filter_column">Pilih kolom yang maw di filter:</label>
        <select id="filter_column" name="filter_column">
            <option value="nama" <?= $filter_column == 'nama' ? 'selected' : ''; ?>>Nama</option>
            <option value="tanggal_lahir" <?= $filter_column == 'tanggal_lahir' ? 'selected' : ''; ?>>Tanggal Lahir</option>
            <option value="kelas" <?= $filter_column == 'kelas' ? 'selected' : ''; ?>>Kelas</option>
        </select>
        <input type="text" id="filter_value" name="filter_value" value="<?= htmlspecialchars($filter_value); ?>">
        <input type="submit" name="filter_like">
    </form>

    <form method="GET">
        Tanggal Lahir
        <label for="start_date">dari:</label>
        <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date); ?>">
        <label for="end_date">sampai:</label>
        <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date); ?>">
        <input type="submit" name="filter_between">
    </form>

    <input type="submit" value="Clear Filter" onclick="window.location.href='?'; return false;">

    <?php if (!$detail): ?>
        <h2>Data Seluruhnya</h2>
        <table border='1'>
            <?php
            $result = $conn->query($query);
            generateTable($result, true);
            ?>
        </table>
    <?php else: ?>
        <h2>Data Siswa</h2>
        <table border='1'>
            <?php
            $result_siswa = $conn->query("SELECT * FROM siswa");
            generateTable($result_siswa, false);
            ?>
        </table>

        <h2>Data Kelas</h2>
        <table border='1'>
            <?php
            $result_kelas = $conn->query("SELECT * FROM kelas");
            generateTable($result_kelas, false);
            ?>
        </table>
    <?php endif; ?>
</body>

</html>