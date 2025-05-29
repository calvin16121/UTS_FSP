<?php
session_start();
require_once("class/classVoucher.php");

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$voucher = new classVoucher();
$iduser = $_SESSION['iduser'];
$res = $voucher->getVoucherUser($iduser);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug - Voucherku</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<header id="header">
    <div style="display: flex; gap: 20px;">
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="promo.php">Promo</a>
        <a href="voucherku.php">Voucherku</a>
    </div>
    <a href="logout.php" style="position: absolute; right: 30px;">Log out</a>
</header>

<div id="content">
    <h1>Voucherku</h1>
    <div class="grid-template">
        <?php
        if (!empty($res) && count($res) > 0) {
            foreach ($res as $row) {
                echo "
                <div class='card'>
                    <div class='card-content'>
                        <h2>".htmlspecialchars($row["vnama"])."</h2>
                        <p><strong>Menu:</strong> ".htmlspecialchars($row["mnama"])."</p>
                        <p><strong>Jenis Menu:</strong> ".htmlspecialchars($row["mjnama"])."</p>
                        <p><strong>Diskon:</strong> ".htmlspecialchars($row["persen_diskon"])."%</p>
                    </div>
                    <div class='card-footer'>
                        <a href='gunakan_voucher.php?id=".urlencode($row["kode"])."'>
                            <button class='btn-claim'>Gunakan</button>
                        </a>
                    </div>
                </div>";
            }
        } else {
            echo "<p style='margin: 20px;'>Kamu belum memiliki voucher. Klaim dari halaman promo.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>