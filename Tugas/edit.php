<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['nama']) || $_SESSION['nama'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$pesan = "";

$stmt_get = $conn->prepare("SELECT nama FROM users WHERE id = ?");
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$stmt_get->bind_result($nama_lama);
if (!$stmt_get->fetch()) {
    header("Location: dashboard.php");
    exit();
}
$stmt_get->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_baru = trim($_POST['nama']);
    $password_baru = $_POST['password'];

    if (!empty($nama_baru) && !empty($password_baru)) {
        $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
        
        $stmt_update = $conn->prepare("UPDATE users SET nama = ?, password = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $nama_baru, $hashed_password, $id);
        
        if ($stmt_update->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $pesan = "Kesalahan Server: Gagal memperbarui data.";
        }
        $stmt_update->close();
    } else {
        $pesan = "Nama dan password baru wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Pengguna</title>
</head>
<body>
    <h2>Edit Data Pengguna</h2>
    
    <?php if ($pesan != "") echo "<p style='color:red;'>$pesan</p>"; ?>

    <form method="POST" action="">
        <label>Nama Pengguna:</label><br>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($nama_lama); ?>" required><br><br>
        
        <label>Password Baru:</label><br>
        <input type="password" name="password" placeholder="Masukkan password baru" required><br><br>
        
        <button type="submit">Simpan Perubahan</button><br><br>
        <a href="dashboard.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>