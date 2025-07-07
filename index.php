<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Bangunan - Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-hard-hat"></i>
            <span>Toko Bangunan</span>
        </div>
        <nav>
            <a href="barang.php"><i class="fas fa-box"></i>Barang</a>
            <a href="Pengiriman.php"><i class="fas fa-truck"></i>Pengiriman</a>
            <a href="karyawan.php"><i class="fas fa-users"></i>Karyawan</a>
            <a href="nota.php"><i class="fas fa-receipt"></i>Nota</a>
            <a href="supplier.php"><i class="fas fa-industry"></i>Supplier</a>
            <a href="pelanggan.php"><i class="fas fa-user-friends"></i>Pelanggan</a>
            <a href="transaksi_order.php"><i class="fas fa-shopping-cart"></i>Transaksi Order</a>
        </nav>
    </div>

    <div class="main-content">
        <header>
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-content">
                <h1>Dashboard Sistem Kasir</h1>
                <div class="user-menu">
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="quick-stats">
                <div class="stat-card">
                    <i class="fas fa-box"></i>
                    <h3>Total Produk</h3>
                    <p class="stat-number">0</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Transaksi Hari Ini</h3>
                    <p class="stat-number">0</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>Total Pelanggan</h3>
                    <p class="stat-number">0</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-truck"></i>
                    <h3>Pengiriman Aktif</h3>
                    <p class="stat-number">0</p>
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