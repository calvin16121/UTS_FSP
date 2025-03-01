<?php
session_start();

$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}
$message = "";
$kode = $_GET['kode'];
$stmt = $mysqli->prepare("SELECT * FROM `menu_jenis` WHERE (`kode` = ?);");
$stmt->bind_param('i',$kode);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$nama = $row['nama'];

if(isset($_POST['update'])){
    $nama = $_POST['nama'];
    $stmt = $mysqli->prepare("UPDATE `menu_jenis` SET `nama` = ? WHERE (`kode` = ?);");
    $stmt->bind_param('si',$nama,$kode);
    $stmt->execute();
    header("Location: jenismenu.php");
    exit;
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
    <div>
    <a href="admin.php">admin page</a>
    <h1>Update jenis menu: <?=$nama?></h1>
    <form action="ubahjenismenu.php?kode=<?=$kode?>" method="post">
        <label for="nama">Masukan Jenis Menu: </label>
        <input type="text" name="nama" value="<?=$nama?>">
        <input type="submit" value="update" name="update">
    </form>
    <p><?=$message?></p>
    </div>
</body>
</html>

<?php $mysqli->close();?>