<?php
session_start();
require_once("class/classMenu.php");
$menu = new classMenu();

$message = "Welcome";
if($_SESSION["USER"]){ $message = "Welcome, ".$_SESSION["USER"]; }
else{ $message = ""; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
    .card {
        width: 320px;
        height: 320px;
        background: #fff;
        border-radius: 20px;
        margin: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .card img {
        width: 100%;
        height: 65%;
        object-fit: cover;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .card-content {
        padding: 15px;
        color: black;
    }
    </style>
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
    
    <div style="
        height: 100vh;
        width: 100%;
        position: fixed;
        left: 0;
        top: 0;
        background-image: url('https://i.pinimg.com/736x/1c/cc/8c/1ccc8c68fd5d9f7b283b8cd64c5dc567.jpg');
        background-size: cover;
        background-position: center;
        z-index: -1;">
    </div>
    
    <div style="
        background-color: rgba(0,0,0,0.6);
        height: 100vh;
        width: 100%;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 0;">
    </div>
    
    <div style="
        position: relative;
        z-index: 1;
        padding: 60px 20px 20px;
        color: antiquewhite;">
        <h1 style="font-size: 80px;">Home</h1>
        <p style="font-size: 40px;"><?=$message?></p>

        <div style="
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
        gap: 50px;
        justify-content: center;">
            <?php
            $res = $menu->getRandomMenu();
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