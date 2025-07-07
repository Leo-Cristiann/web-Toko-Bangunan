<?php
include 'db_connection.php';

if (!isset($_GET['id'])) {
    die('ID Nota tidak ditemukan');
}

$id_nota = $_GET['id'];

// Ambil data nota beserta detail pelanggan
$sql = "SELECT n.*, p.Nama_Pelanggan 
        FROM Nota n
        JOIN Pelanggan p ON n.Pelanggan_ID = p.ID_Pelanggan
        WHERE n.ID_Nota = $id_nota";
$result = $conn->query($sql);
$nota = $result->fetch_assoc();

// Ambil detail barang dari transaksi
$detail_query = "SELECT b.Nama_Barang, dt.Jumlah_Barang, dt.Harga_Barang,
                 (dt.Jumlah_Barang * dt.Harga_Barang) as Subtotal
                 FROM Detail_Transaksi dt
                 JOIN barang b ON dt.ID_Barang = b.ID_Barang
                 WHERE dt.ID_Transaksi = {$nota['ID_Transaksi']}";
$detail_result = $conn->query($detail_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota #<?= $id_nota ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .nota-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nota-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .nota-info {
            margin-bottom: 20px;
        }
        .nota-info table {
            width: 100%;
        }
        .nota-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .nota-table th, .nota-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .nota-table th {
            background-color: #f5f5f5;
        }
        .nota-total {
            text-align: right;
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        .print-button button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        @media print {
            body {
                background: none;
            }
            .nota-container {
                box-shadow: none;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <div class="nota-header">
            <h2>TOKO MAJU JAYA</h2>
            <p>Jl. Ketintang No.2, Kota Surabaya</p>
            <p>Telp: (021) 1234567</p>
        </div>

        <div class="nota-info">
            <table>
                <tr>
                    <td width="150">No. Nota</td>
                    <td>: <?= $nota['ID_Nota'] ?></td>
                    <td width="150">Tanggal</td>
                    <td>: <?= date('d/m/Y', strtotime($nota['Tanggal'])) ?></td>
                </tr>
                <tr>
                    <td>Nama Pelanggan</td>
                    <td>: <?= $nota['Nama_Pelanggan'] ?></td>
                    <td>Status</td>
                    <td>: <?= $nota['Status'] ?></td>
                </tr>
            </table>
        </div>

        <table class="nota-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($detail = $detail_result->fetch_assoc()): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $detail['Nama_Barang'] ?></td>
                    <td><?= $detail['Jumlah_Barang'] ?></td>
                    <td>Rp <?= number_format($detail['Harga_Barang'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($detail['Subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="nota-total">
            <h3>Total: Rp <?= number_format($nota['Total_Harga'], 0, ',', '.') ?></h3>
        </div>

        <div class="print-button">
            <button onclick="window.print()">Cetak Nota</button>
            <button onclick="window.location.href='nota.php'">Kembali</button>
        </div>
    </div>
</body>
</html>