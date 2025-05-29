<?php
session_start();
require_once("class/classDB.php");

if (!isset($_GET['kode_unik'])) {
    echo "Kode unik tidak ditemukan.";
    exit;
}

$kode_unik = $_GET['kode_unik'];
$db = new classDB();
$conn = $db->connect();

$sql = "UPDATE kepemlikan_voucher SET tanggal_terpakai = NOW() WHERE kode_unik = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kode_unik);

if ($stmt->execute()) {
    header("Location: voucherku.php");
    exit;
} else {
    echo "Gagal menggunakan voucher.";
}
?>
