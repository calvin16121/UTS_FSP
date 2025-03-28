<?php
session_start();
require_once("../class/classMenu.php");
require_once("../class/classJenisMenu.php");

$message = "";
$menu = new classMenu();
$jenisMenu = new classJenisMenu();

$message = "";
if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
    $res = $menu->getMenuKode($kode);
    $row = $res->fetch_assoc();
    $nama = $row['nama'];
    $jenis = $row['kode_jenis'];
    $harga = $row['harga_jual'];
    $gambar = $row['url_gambar'];
}

if(isset($_POST['update'])){
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $harga = $_POST['harga'];
    $gambar_old = $_POST['gambar_old'];
    $gambar_new = $_FILES['gambar_new'];

    $menu->updateMenu($kode, $nama, $jenis, $harga, $gambar_old, $gambar_new);
    header("Location: menu.php");
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
    <h1>Update menu: <?=$nama?></h1>
    <form action="ubahmenu.php"  method="post" enctype="multipart/form-data">
        <input type="hidden" name="kode" value="<?=$kode?>">
        <input type="hidden" name="gambar_old" value="<?=$gambar?>">

        <label for="nama">Nama Menu: </label>
        <input type="text" name="nama" value="<?=$nama?>" required>
        <br>
        <label for="nama">Jenis Menu: </label>
        <select name="jenis">
            <?php
            echo "<option value='none' selected disabled hidden>Select an Option</option>";
            $res = $jenisMenu->getJenisMenu();
            while($row = $res->fetch_assoc()) 
            { echo ($row["kode"]==$jenis)?
            "<option value=".$row["kode"]." selected>".$row['nama']."</option>":
            "<option value=".$row["kode"].">".$row['nama']."</option>";}
            ?>
        </select>
        <br>
        <label for="harga">Harga Jual: </label>
        <input type="number" name="harga" value="<?=$harga?>" required>
        <br>
        <br>
        <p>Gambar sekarang:</p>
        <img src="../<?=$gambar?>" alt="" style="width:100px;">
        <br>
        <br>
        <label for="gambar">Gambar baru</label>
        <input type="file" name="gambar_new" accept="image/jpeg, image/png">
        <br>
        <br>
        <input type="submit" value="update" name="update">
    </form>
    <p><?=$message?></p>
    </div>
</body>
</html>