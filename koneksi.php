<?php
$conn = mysqli_connect("localhost","root","","db_sekolah");
if ($conn->connect_error) {
    die("Koneksi gagal: ". $conn->connect_error);
}