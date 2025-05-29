<?php
session_start();
require_once("../class/classDB.php");

$db = new classDB();
$conn = $db->connect();

$sql = "SELECT kv.kode_voucher, v.nama AS nama_voucher, m.nama AS nama_member, kv.kode_unik, kv.tanggal_terpakai
        FROM kepemlikan_voucher kv
        JOIN voucher v ON kv.kode_voucher = v.kode
        JOIN member m ON kv.kode_member = m.kode
        ORDER BY kv.kode_voucher DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Klaim Voucher</title>
    <link rel="stylesheet" href="../css/index.css"> 
</head>
<body>
    <header>
        <a href="index.php">Dashboard Admin</a>
    </header>
    <h1>Data Klaim Voucher</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Nama Voucher</th>
            <th>Nama Member</th>
            <th>Kode Unik</th>
            <th>Status</th>
            <th>Tanggal Dipakai</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['nama_voucher'] ?></td>
            <td><?= $row['nama_member'] ?></td>
            <td><?= $row['kode_unik'] ?></td>
            <td><?= $row['tanggal_terpakai'] ? 'Digunakan' : 'Belum Digunakan' ?></td>
            <td><?= $row['tanggal_terpakai'] ?? '-' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
