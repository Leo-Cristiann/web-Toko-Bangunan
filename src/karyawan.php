<?php
include 'db_connection.php';

// Operasi CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nama_karyawan = $_POST['nama_karyawan'];
        $jabatan = $_POST['jabatan'];
        $kontak = $_POST['kontak'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO Karyawan (Nama_Karyawan, Jabatan, Kontak_Karyawan, Username, Password) VALUES ('$nama_karyawan', '$jabatan', '$kontak', '$username', '$password')";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id_karyawan = $_POST['id_karyawan'];
        $sql = "DELETE FROM Karyawan WHERE ID_Karyawan = $id_karyawan";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id_karyawan = $_POST['id_karyawan'];
        $nama_karyawan = $_POST['nama_karyawan'];
        $jabatan = $_POST['jabatan'];
        $kontak = $_POST['kontak'];
        $username = $_POST['username'];
        $sql = "UPDATE Karyawan SET Nama_Karyawan='$nama_karyawan', Jabatan='$jabatan', Kontak_Karyawan='$kontak', Username='$username' WHERE ID_Karyawan = $id_karyawan";
        $conn->query($sql);
    }
}

// Ambil data Karyawan
$result = $conn->query("SELECT * FROM Karyawan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - Toko Maju Jaya</title>
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

        .welcome-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .content-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            width: 100%;
        }

        .section-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .section-body {
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

        .form-group input {
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

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #2196F3;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .table-container {
            overflow-x: auto;
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
            background-color: #f8f9fa;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .main-content {
                margin-left: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
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
            <a href="barang.php"><i class="fas fa-box"></i>Barang</a>
            <a href="Pengiriman.php"><i class="fas fa-truck"></i>Pengiriman</a>
            <a href="karyawan.php" class="active"><i class="fas fa-users"></i>Karyawan</a>
            <a href="nota.php"><i class="fas fa-receipt"></i>Nota</a>
            <a href="supplier.php"><i class="fas fa-industry"></i>Supplier</a>
            <a href="pelanggan.php"><i class="fas fa-user-friends"></i>Pelanggan</a>
            <a href="transaksi_order.php"><i class="fas fa-shopping-cart"></i>Transaksi Order</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <h2>Sistem Penjualan Toko Maju Jaya</h2>
            <p>Selamat datang di sistem penjualan toko! Pilih menu di sidebar untuk mengelola data.</p>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2>Manajemen Karyawan</h2>
            </div>
            <div class="section-body">
                <div class="content-section">
                    <div class="section-header">
                        <h3>Tambah Karyawan Baru</h3>
                    </div>
                    <div class="section-body">
                        <form method="POST" class="form-grid">
                            <input type="hidden" name="id_karyawan" id="id_karyawan">
                            
                            <div class="form-group">
                                <label for="nama_karyawan">Nama Karyawan</label>
                                <input type="text" name="nama_karyawan" id="nama_karyawan" required>
                            </div>

                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" required>
                            </div>

                            <div class="form-group">
                                <label for="kontak">Kontak</label>
                                <input type="text" name="kontak" id="kontak" required>
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password">
                            </div>

                            <div class="form-group">
                                <button type="submit" name="add" class="btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="content-section">
                    <div class="section-header">
                        <h3>Data Karyawan</h3>
                    </div>
                    <div class="section-body">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Kontak</th>
                                        <th>Username</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['ID_Karyawan']) ?></td>
                                        <td><?= htmlspecialchars($row['Nama_Karyawan']) ?></td>
                                        <td><?= htmlspecialchars($row['Jabatan']) ?></td>
                                        <td><?= htmlspecialchars($row['Kontak_Karyawan']) ?></td>
                                        <td><?= htmlspecialchars($row['Username']) ?></td>
                                        <td>
                                            <button onclick="editKaryawan(<?= htmlspecialchars(json_encode($row)) ?>)" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id_karyawan" value="<?= $row['ID_Karyawan'] ?>">
                                                <button type="submit" name="delete" class="btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editKaryawan(data) {
            document.getElementById('id_karyawan').value = data.ID_Karyawan;
            document.getElementById('nama_karyawan').value = data.Nama_Karyawan;
            document.getElementById('jabatan').value = data.Jabatan;
            document.getElementById('kontak').value = data.Kontak_Karyawan;
            document.getElementById('username').value = data.Username;
            document.getElementById('password').removeAttribute('required');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        }
    </script>
</body>
</html>
