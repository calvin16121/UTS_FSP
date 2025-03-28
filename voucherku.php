<?
$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- header -->
    <header id="header">
    <div class="logo">
        <a href="index.php">LOGO</a>
    </div>
    <div>
        <a href="menu.php">Menu</a>
        <a href="promo.php">Promo</a>
        <a href="voucherku.php">Voucherku</a>
    </div>
    <div class="login">
        <a href="login.php">Login</a>
    </div>
    </header>

    <div id="content">
        <h1>Voucherku</h1>
    </div>
</body>
</html>

<? $mysqli->close();?>