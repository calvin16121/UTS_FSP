<?php
session_start();
require_once("class/classVoucher.php");
$voucher = new classVoucher();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- header -->
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
        <h1>Promo</h1>

        <div class="grid-template">
            <?php
            $res = $voucher->getVoucher();
            while($row = $res->fetch_assoc()){
                echo "
                <div class='card'>
                <div class='card-content'>
                    <h1 style='margin:30px 10px;'>".$row["vnama"]."</h1>
                    <h3 style='margin:10px;''>menu: ".$row["mnama"]."</h3>
                    <h3 style='margin:10px;''>jenis menu: ".$row["mjnama"]."</h3>
                    <h3 style='margin:10px;''>diskon: ".$row["persen_diskon"]."%</h3>
                </div>
                <div class='card-footer'>
                    <a href='promo.php'>
                        <button class='btn-claim'>Claim Voucher</button>
                    </a>
                </div>
                </div>
            ";}
            ?>
        </div>
    </div>
</body>
</html>