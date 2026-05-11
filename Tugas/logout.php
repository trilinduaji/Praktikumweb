<?php
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session dari server
session_destroy();

// Arahkan kembali ke halaman login
header("Location: auth.php");
exit();
?>