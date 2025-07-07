<?php
session_start();
include 'db_connection.php'; // File koneksi database

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['ID_Karyawan'])) {
    header('Location: index.php');
    exit();
}

// Fungsi untuk sanitasi input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Proses login
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Query untuk memeriksa karyawan berdasarkan username
    $query = "SELECT ID_Karyawan, Password FROM karyawan WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['Password'])) {
            // Simpan data ke session
            $_SESSION['ID_Karyawan'] = $user['ID_Karyawan'];
            header('Location: index.php'); // Redirect ke dashboard
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Kasir</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
            text-align: center;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            color: #4CAF50;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
