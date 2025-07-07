<?php
include 'db_connection.php';

// Operasi CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nama_supplier = $_POST['nama_supplier'];
        $email_supplier = $_POST['email_supplier'];
        $nama_barang = $_POST['nama_barang'];
        $alamat = $_POST['alamat'];
        $sql = "INSERT INTO Supplier (Nama_Supplier, Email_Supplier, Nama_Barang_yang_Disediakan, Alamat) VALUES ('$nama_supplier', '$email_supplier', '$nama_barang', '$alamat')";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id_supplier = $_POST['id_supplier'];
        $sql = "DELETE FROM Supplier WHERE ID_Supplier = $id_supplier";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id_supplier = $_POST['id_supplier'];
        $nama_supplier = $_POST['nama_supplier'];
        $email_supplier = $_POST['email_supplier'];
        $nama_barang = $_POST['nama_barang'];
        $alamat = $_POST['alamat'];
        $sql = "UPDATE Supplier SET Nama_Supplier='$nama_supplier', Email_Supplier='$email_supplier', Nama_Barang_yang_Disediakan='$nama_barang', Alamat='$alamat' WHERE ID_Supplier = $id_supplier";
        $conn->query($sql);
    }
}

// Ambil data Supplier
$result = $conn->query("SELECT * FROM Supplier");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier - Toko Maju Jaya</title>
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
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
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
        }

        button[name="add"] {
            background-color: #4CAF50;
            color: white;
        }

        button[name="update"] {
            background-color: #2196F3;
            color: white;
        }

        button[name="delete"] {
            background-color: #f44336;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 500;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
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
            <a href="supplier.php" class="active"><i class="fas fa-industry"></i>Supplier</a>
            <a href="pelanggan.php"><i class="fas fa-user-friends"></i>Pelanggan</a>
            <a href="transaksi_order.php"><i class="fas fa-shopping-cart"></i>Transaksi Order</a>
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
            <h2>Tambah Supplier Baru</h2>
            <form method="POST" class="form-section">
                <input type="hidden" name="id_supplier" id="id_supplier">
                
                <div class="form-group">
                    <label for="nama_supplier">Nama Supplier:</label>
                    <input type="text" name="nama_supplier" id="nama_supplier" required>
                </div>

                <div class="form-group">
                    <label for="email_supplier">Email Supplier:</label>
                    <input type="email" name="email_supplier" id="email_supplier" required>
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang yang Disediakan:</label>
                    <input type="text" name="nama_barang" id="nama_barang" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <input type="text" name="alamat" id="alamat" required>
                </div>

                <div class="button-group">
                    <button type="submit" name="add">+ Tambah Supplier</button>
                    <button type="submit" name="update">✏ Ubah</button>
                </div>
            </form>
        </div>

        <div class="content-wrapper">
            <h2>Data Supplier</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nama Barang</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['ID_Supplier'] ?></td>
                            <td><?= $row['Nama_Supplier'] ?></td>
                            <td><?= $row['Email_Supplier'] ?></td>
                            <td><?= $row['Nama_Barang_yang_Disediakan'] ?></td>
                            <td><?= $row['Alamat'] ?></td>
                            <td>
                                <button onclick='editSupplier(<?= json_encode($row) ?>)'>✏ Edit</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_supplier" value="<?= $row['ID_Supplier'] ?>">
                                    <button type="submit" name="delete">🗑 Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editSupplier(data) {
            document.getElementById('id_supplier').value = data.ID_Supplier;
            document.getElementById('nama_supplier').value = data.Nama_Supplier;
            document.getElementById('email_supplier').value = data.Email_Supplier;
            document.getElementById('nama_barang').value = data.Nama_Barang_yang_Disediakan;
            document.getElementById('alamat').value = data.Alamat;
        }
    </script>
</body>
</html>
