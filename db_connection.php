<?php
// db_connection.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Penjualan_Toko_Maju_Jaya_2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
