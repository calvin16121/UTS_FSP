<?php
require_once("../class/classJenisMenu.php");
session_start();
$jenisMenu = new classJenisMenu();
$message = "";

$kode = $_GET['kode'];
$nama = $jenisMenu->getJenisMenuKode($kode);

if(isset($_POST['update'])){
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jenisMenu->updateJenisMenu($nama, $kode);
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
        <input type="hidden" name="kode" value="<?=$kode?>">
        <label for="nama">Masukan Jenis Menu: </label>
        <input type="text" name="nama" value="<?=$nama?>">
        <input type="submit" value="update" name="update">
    </form>
    <p><?=$message?></p>
    </div>
</body>
</html>
