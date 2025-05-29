<?php
session_start();
require_once("class/classVoucher.php");

if (!isset($_SESSION['id'])) {
    echo "Silakan login terlebih dahulu.";
    exit;
}

if (!isset($_GET['voucher_id'])) {
    echo "ID voucher tidak tersedia.";
    exit;
}

$user_id = $_SESSION['id'];
$voucher_id = intval($_GET['voucher_id']);

$voucher = new classVoucher();
$result = $voucher->claimVoucher($user_id, $voucher_id);

echo "<script>alert('$result'); window.location.href='voucherku.php';</script>";
?>