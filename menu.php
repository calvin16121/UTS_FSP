<?php
session_start();
require_once("class/classMenu.php");
$menu = new classMenu();
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
        <h1>Menu</h1>

        <div style="margin:40px 0;">
        <form method="GET" action="menu.php">
            <label for="cari_menu">Cari menu:</label>
            <input type="text" id="cari_menu" name="keyword" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
            <input type="submit" value="Cari">
        </form>
        </div>

        <div style="
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
        gap: 50px;
        justify-content: center;">
            <?php
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $res = $menu->getSearchMenu($keyword);
            while($row = $res->fetch_assoc()){
                echo "
                <div class='card'>
                <img src='".$row["url_gambar"]."'>
                <div class='card-content'>
                    <h1 style='margin:0px;'>".$row["nama_m"]."</h1>
                    <h3 style='margin:0px;''>".$row["nama_mj"]."</h3>
                    <h3 style='margin:0px;''>price: ".$row["harga_jual"]."</h3>
                </div>
                </div>
                ";}
            ?>
        </div>
    </div>
</body>
</html>