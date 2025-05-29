<?php
session_start();
require_once("class/classVoucher.php");

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$voucher = new classVoucher();
$iduser = $_SESSION['iduser'];
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

<!-- Header -->
<header id="header">
    <div style="display: flex; gap: 20px;">
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="promo.php">Promo</a>
        <a href="voucherku.php">Voucherku</a>
    </div>
    <a href="logout.php" style="position: absolute; right: 30px;">Log out</a>
</header>

<!-- Content -->
<div id="content">
    <h1>Voucherku</h1>
    <div class="grid-template">
        <?php
        $res = $voucher->getVoucherUser($iduser);

        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                echo "
                <div class='card'>
                    <div class='card-content'>
                        <h1 style='margin:30px 10px;'>".htmlspecialchars($row["vnama"])."</h1>
                        <h3 style='margin:10px;'>Menu: ".htmlspecialchars($row["mnama"])."</h3>
                        <h3 style='margin:10px;'>Jenis Menu: ".htmlspecialchars($row["mjnama"])."</h3>
                        <h3 style='margin:10px;'>Diskon: ".htmlspecialchars($row["persen_diskon"])."%</h3>
                    </div>
                    <div class='card-footer'>
                        <a href='gunakan_voucher.php?id=".htmlspecialchars($row["vid"])."'>
                            <button class='btn-claim'>Gunakan</button>
                        </a>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p style='margin: 20px;'>Kamu belum memiliki voucher. Klaim dari halaman promo.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
