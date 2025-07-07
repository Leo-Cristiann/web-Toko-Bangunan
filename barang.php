<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = $_POST['Nama_Barang'] ?? '';
    $deskripsi = $_POST['Deskripsi'] ?? '';
    $satuan = $_POST['Satuan'] ?? '';
    $harga_jual = $_POST['Harga_Jual'] ?? 0;
    $stok_barang = $_POST['Stok_Barang'] ?? 0;
    $id_supplier = $_POST['ID_Supplier'] ?? 0;

    if (isset($_POST['add'])) {
        if (!empty($nama_barang) && !empty($deskripsi) && !empty($satuan) && $harga_jual > 0 && $stok_barang >= 0 && $id_supplier > 0) {
            $stmt = $conn->prepare("INSERT INTO barang (Nama_Barang, Deskripsi, Satuan, Harga_Jual, Stok_Barang, ID_Supplier) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiii", $nama_barang, $deskripsi, $satuan, $harga_jual, $stok_barang, $id_supplier);
            
            if ($stmt->execute()) {
                echo "<script>alert('Data barang berhasil ditambahkan!');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Harap isi semua field dengan benar!');</script>";
        }
    }

    if (isset($_POST['edit'])) {
        $id_barang = $_POST['ID_Barang'];
        $stmt = $conn->prepare("UPDATE barang SET Stok_Barang = ?, Harga_Jual = ? WHERE ID_Barang = ?");
        $stmt->bind_param("iii", $stok_barang, $harga_jual, $id_barang);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data barang berhasil diperbarui!');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Barang - Toko Maju Jaya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Include base styles from db_connection.php */
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

        /* Sidebar styles (same as db_connection.php) */
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

        .logo {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-color);
            color: white;
        }

        .logo i {
            font-size: 24px;
        }

        .logo span {
            font-size: 18px;
            font-weight: bold;
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

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
            background-color: #f0f2f5;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        header {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-color);
            display: none;
        }

        .content-wrapper {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .content-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            width: 100%;
        }

        .welcome-header {
            display: flex;
            flex-direction: column; /* Atur layout menjadi vertikal */
            align-items: flex-start; /* Teks rata ke kiri */
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }


        .flex-container {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }


        .section-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .section-body {
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .card-body {
            padding: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        /* Table styles */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .edit-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .edit-inputs {
            display: flex;
            gap: 5px;
        }

        .edit-inputs input {
            width: 80px;
            padding: 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-edit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            cursor: pointer;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-250px);
                z-index: 1000;
            }

            .sidebar.collapsed {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .edit-inputs input {
                width: 60px;
            }
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
            <a href="barang.php" class="active"><i class="fas fa-box"></i>Barang</a>
            <a href="Pengiriman.php"><i class="fas fa-truck"></i>Pengiriman</a>
            <a href="karyawan.php"><i class="fas fa-users"></i>Karyawan</a>
            <a href="nota.php"><i class="fas fa-receipt"></i>Nota</a>
            <a href="supplier.php"><i class="fas fa-industry"></i>Supplier</a>
            <a href="pelanggan.php"><i class="fas fa-user-friends"></i>Pelanggan</a>
            <a href="transaksi_order.php"><i class="fas fa-shopping-cart"></i>Transaksi Order</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="flex-container">
            <div class="welcome-header">
                <h2>Sistem Penjualan Toko Maju Jaya</h2>
                <p>Selamat datang di sistem penjualan toko! Pilih menu di sidebar untuk mengelola data.</p>
            </div>    
        </div>    
        <div class="content-section">
                <div class="section-header">
                <h2>Manajemen Barang</h2>
            </div>
            <div class="section-body">
            <div class="content-section">
                <div class="section-header">
                    <h3>Tambah Barang Baru</h3>
                </div>
                <div class="section-body">
                    <form method="POST" action="barang.php" class="form-grid">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" id="nama_barang" name="Nama_Barang" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" name="Deskripsi" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="satuan">Satuan</label>
                            <input type="text" id="satuan" name="Satuan" required>
                        </div>
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="number" id="harga_jual" name="Harga_Jual" required>
                        </div>
                        <div class="form-group">
                            <label for="stok_barang">Stok Barang</label>
                            <input type="number" id="stok_barang" name="Stok_Barang" required>
                        </div>
                        <div class="form-group">
                            <label for="id_supplier">ID Supplier</label>
                            <input type="number" id="id_supplier" name="ID_Supplier" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="add" class="btn-primary">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h3>Data Barang</h3>
                </div>
                    <div class="section-body">
            <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Deskripsi</th>
                                    <th>Satuan</th>
                                    <th>Harga Jual</th>
                                    <th>Stok Barang</th>
                                    <th>ID Supplier</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ID_Barang']) ?></td>
                                    <td><?= htmlspecialchars($row['Nama_Barang']) ?></td>
                                    <td><?= htmlspecialchars($row['Deskripsi']) ?></td>
                                    <td><?= htmlspecialchars($row['Satuan']) ?></td>
                                    <td><?= htmlspecialchars($row['Harga_Jual']) ?></td>
                                    <td><?= htmlspecialchars($row['Stok_Barang']) ?></td>
                                    <td><?= htmlspecialchars($row['ID_Supplier']) ?></td>
                                    <td>
                                        <form method="POST" action="barang.php" class="edit-form">
                                            <input type="hidden" name="ID_Barang" value="<?= $row['ID_Barang'] ?>">
                                            <div class="edit-inputs">
                                                <input type="number" name="Stok_Barang" value="<?= $row['Stok_Barang'] ?>" placeholder="Stok" required>
                                                <input type="number" name="Harga_Jual" value="<?= $row['Harga_Jual'] ?>" placeholder="Harga" required>
                                            </div>
                                            <button type="submit" name="edit" class="btn-edit">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        }
    </script>
</body>
</html>