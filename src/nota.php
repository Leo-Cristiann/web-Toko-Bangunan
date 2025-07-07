<?php
include 'db_connection.php';

// Modifikasi bagian operasi pengiriman di nota.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['kirim'])) {
        $id_nota = $_POST['id_nota'];
        
        // Ambil data pelanggan dari nota
        $query_pelanggan = "SELECT p.ID_Pelanggan, p.Nama_Pelanggan, p.Alamat, p.Nomor_Telepon 
                           FROM Nota n 
                           JOIN Pelanggan p ON n.Pelanggan_ID = p.ID_Pelanggan 
                           WHERE n.ID_Nota = $id_nota";
        $result_pelanggan = $conn->query($query_pelanggan);
        $data_pelanggan = $result_pelanggan->fetch_assoc();
        
        // Insert ke tabel Pengiriman dengan ID_Pelanggan
        $alamat = $data_pelanggan['Alamat'];
        $nomor_telepon = $data_pelanggan['Nomor_Telepon'];
        $id_pelanggan = $data_pelanggan['ID_Pelanggan'];
        
        $sql_pengiriman = "INSERT INTO Pengiriman (Alamat_Pengiriman, Nomor_Telepon, ID_Pelanggan) 
                          VALUES ('$alamat', '$nomor_telepon', $id_pelanggan)";
        
        if($conn->query($sql_pengiriman)) {
            // Update status nota
            $sql = "UPDATE Nota SET Status='Dikirim' WHERE ID_Nota = $id_nota";
            $conn->query($sql);
            
            echo "<script>alert('Data berhasil ditambahkan ke pengiriman!');
                  window.location.href='pengiriman.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data pengiriman!');</script>";
        }
    }
}

// Ambil data Nota dengan join ke tabel terkait
$sql = "SELECT n.ID_Nota, n.Tanggal, p.Nama_Pelanggan, n.Total_Harga, n.Status, n.ID_Transaksi
        FROM Nota n
        JOIN Pelanggan p ON n.Pelanggan_ID = p.ID_Pelanggan
        ORDER BY n.ID_Nota DESC";
$result = $conn->query($sql);
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nota - Toko Maju Jaya</title>
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

        .table-container {
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

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-kirim {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-cetak {
            background-color: #2196F3;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .detail-row {
            background-color: #f9f9f9;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .main-content {
                margin-left: 0;
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
            <a href="karyawan.php"><i class="fas fa-users"></i>Karyawan</a>
            <a href="nota.php" class="active"><i class="fas fa-receipt"></i>Nota</a>
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
                <h2>Data Nota</h2>
            </div>
            <div class="section-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Nota</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Detail Barang</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): 
                                // Ambil detail barang untuk setiap transaksi
                                $id_transaksi = $row['ID_Transaksi'];
                                $detail_query = "SELECT b.Nama_Barang, dt.Jumlah_Barang, dt.Harga_Barang
                                               FROM Detail_Transaksi dt
                                               JOIN barang b ON dt.ID_Barang = b.ID_Barang
                                               WHERE dt.ID_Transaksi = $id_transaksi";
                                $detail_result = $conn->query($detail_query);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ID_Nota']) ?></td>
                                <td><?= htmlspecialchars($row['Tanggal']) ?></td>
                                <td><?= htmlspecialchars($row['Nama_Pelanggan']) ?></td>
                                <td>
                                    <?php while ($detail = $detail_result->fetch_assoc()): ?>
                                        <?= htmlspecialchars($detail['Nama_Barang']) ?> 
                                        (<?= htmlspecialchars($detail['Jumlah_Barang']) ?> x 
                                        Rp<?= number_format($detail['Harga_Barang'], 0, ',', '.') ?>)<br>
                                    <?php endwhile; ?>
                                </td>
                                <td>Rp<?= number_format($row['Total_Harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['Status'] ?? 'Pending') ?></td>
                                <td class="action-buttons">
                                    <?php if (($row['Status'] ?? 'Pending') !== 'Dikirim'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id_nota" value="<?= $row['ID_Nota'] ?>">
                                            <button type="submit" name="kirim" class="btn btn-kirim">
                                                <i class="fas fa-truck"></i> Kirim
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <button onclick="cetakNota(<?= $row['ID_Nota'] ?>)" class="btn btn-cetak">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cetakNota(idNota) {
            window.open('cetak_nota.php?id=' + idNota, '_blank');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        }
    </script>
</body>
</html>
