<?php
session_start();

$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}

$message = "Welcome";
if($_SESSION["USER"]){
    $message = "Welcome, ".$_SESSION["USER"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug</title>
    <link rel="stylesheet" href="../index.css">
</head>
<body>
    <div id="content">
        <a href="../index.php">home</a>
        <h1>Admin</h1>
        <p><?=$message?></p>
        <a href="jenismenu.php">Kelola jenis menu</a>
        <br>
        <a href="menu.php">Kelola menu</a>
        <br>
        <a href="voucher.php">Kelola voucher</a>
        <br>
        <a href="member.php">Kelola member</a>
    </div>
</body>
</html>

<?php $mysqli->close();?>