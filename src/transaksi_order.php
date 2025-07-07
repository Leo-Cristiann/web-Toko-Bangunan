<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['ID_Karyawan'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_karyawan = $_SESSION['ID_Karyawan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $barang = $_POST['barang'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d'); // Mengambil tanggal hari ini

    // Insert data pelanggan
    $query_pelanggan = "INSERT INTO Pelanggan (Nama_Pelanggan) VALUES ('$nama_pelanggan')";
    mysqli_query($conn, $query_pelanggan);
    $id_pelanggan = mysqli_insert_id($conn);

    // Insert ke tabel Transaksi
    $query_transaksi = "INSERT INTO Transaksi (ID_Karyawan, Total_Harga, Tanggal) VALUES ('$id_karyawan', 0, '$tanggal')";
    mysqli_query($conn, $query_transaksi);
    $id_transaksi = mysqli_insert_id($conn);
    $total_harga = 0;

    // Insert ke tabel Detail_Transaksi dan hitung total harga
    foreach ($barang as $key => $id_barang) {
        $jumlah_barang = (int)$jumlah[$key];

        // Ambil harga barang dari database
        $query_barang = "SELECT Harga_JUAL, Nama_Barang FROM barang WHERE ID_Barang = '$id_barang'";
        $result_barang = mysqli_query($conn, $query_barang);
        $data_barang = mysqli_fetch_assoc($result_barang);
        $harga_barang = (float)$data_barang['Harga_JUAL'];

        $sub_total = $jumlah_barang * $harga_barang;
        $total_harga += $sub_total;

        // Insert detail transaksi
        $query_detail = "INSERT INTO Detail_Transaksi (ID_Transaksi, ID_Barang, Jumlah_Barang, Harga_Barang) 
                         VALUES ('$id_transaksi', '$id_barang', '$jumlah_barang', '$harga_barang')";
        mysqli_query($conn, $query_detail);
    }

    // Update total harga di tabel Transaksi
    $query_update_transaksi = "UPDATE Transaksi SET Total_Harga = '$total_harga' WHERE ID_Transaksi = '$id_transaksi'";
    mysqli_query($conn, $query_update_transaksi);

    // Insert ke tabel Nota
    $query_nota = "INSERT INTO Nota (Tanggal, Pelanggan_ID, Total_Harga, ID_Transaksi) 
                   VALUES ('$tanggal', '$id_pelanggan', '$total_harga', '$id_transaksi')";
    mysqli_query($conn, $query_nota);

    echo "<p>Transaksi berhasil ditambahkan dengan ID: $id_transaksi</p>";
    header('Location: nota.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Order - Toko Maju Jaya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #333;
            --background-color: #f4f4f9;
            --text-color: #333;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-250px);
        }

        .sidebar nav {
            padding: 20px 0;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 10px;
        }

        .sidebar nav a:hover, .sidebar nav a.active {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
        }

        .sidebar nav a i {
            width: 20px;
            text-align: center;
        }

        .logo {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-color);
            color: white;
        }

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
            background-color: #f0f2f5;
        }

        .content-wrapper {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-section {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        button[type="button"] {
            background-color: #2196F3;
            color: white;
        }

        .item-container {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .customer-info {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-store"></i>
            <span>Toko Maju Jaya</span>
        </div>
        <nav>
            <a href="barang.php"><i class="fas fa-box"></i>Barang</a>
            <a href="Pengiriman.php"><i class="fas fa-truck"></i>Pengiriman</a>
            <a href="karyawan.php"><i class="fas fa-users"></i>Karyawan</a>
            <a href="nota.php"><i class="fas fa-receipt"></i>Nota</a>
            <a href="supplier.php"><i class="fas fa-industry"></i>Supplier</a>
            <a href="pelanggan.php"><i class="fas fa-user-friends"></i>Pelanggan</a>
            <a href="transaksi_order.php" class="active"><i class="fas fa-shopping-cart"></i>Transaksi Order</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="content-wrapper">
            <div class="welcome-header">
                <h2>Sistem Penjualan Toko Maju Jaya</h2>
                <p>Selamat datang di sistem penjualan toko! Pilih menu di sidebar untuk mengelola data.</p>
            </div>
        </div>

        <div class="content-wrapper">
            <h2>Transaksi Order Baru</h2>
            <form method="POST" action="" class="form-section">
                <div class="customer-info">
                    <h3>Informasi Pelanggan</h3>
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan:</label>
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea id="alamat" name="alamat" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon:</label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" required>
                    </div>
                </div>
                
                <h3>Detail Barang</h3>
                <div id="itemContainer">
                    <div class="item-container">
                        <div class="form-group">
                            <label>Pilih Barang:</label>
                            <select name="barang[]" required>
                                <?php
                                $query_barang = "SELECT ID_Barang, Nama_Barang FROM barang";
                                $result_barang = mysqli_query($conn, $query_barang);
                                while ($row = mysqli_fetch_assoc($result_barang)) {
                                    echo "<option value='{$row['ID_Barang']}'>{$row['Nama_Barang']}</option>";
                                }
                                ?>
                            </select>
                            
                            <label>Jumlah Barang:</label>
                            <input type="number" name="jumlah[]" min="1" required>
                        </div>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="button" onclick="tambahItem()">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </button>
                    <button type="submit">
                        <i class="fas fa-check"></i> Proses Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tambahItem() {
            const container = document.getElementById('itemContainer');
            const newItem = container.children[0].cloneNode(true);
            
            // Reset input values
            const inputs = newItem.getElementsByTagName('input');
            const selects = newItem.getElementsByTagName('select');
            
            for (let input of inputs) {
                input.value = '';
            }
            for (let select of selects) {
                select.selectedIndex = 0;
            }
            
            container.appendChild(newItem);
        }
    </script>
</body>
</html>
