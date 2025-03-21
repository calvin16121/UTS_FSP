<?php
session_start();

$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}
$message = "";
if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
    $stmt = $mysqli->prepare("SELECT * FROM `voucher` WHERE (`kode` = ?);");
    $stmt->bind_param('i',$kode);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $nama = $row['nama'];
    $jenis = $row['kode_jenis'] ?? "";
    $menu = $row['kode_menu'] ?? "";
    $start = $row["mulai_berlaku"];
    $end = $row["akhir_berlaku"];
    $kuota = $row["kuota_max"];
    $diskon = $row["persen_diskon"];
    
    $start = date('Y-m-d', strtotime($start));
    $end = date('Y-m-d', strtotime($end));
    
    $stmt->close();
}

if(isset($_POST['update'])){
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'] ?? "";
    $menu = $_POST['menu'] ?? "";
    $start = $_POST["start"];
    $end = $_POST["end"];
    $kuota = $_POST["kuota"];
    $diskon = $_POST["diskon"];
    if($menu == ""){
        $stmt = $mysqli->prepare(
            "UPDATE `voucher` SET 
                    `kode_jenis` = ?, 
                    `nama` = ?, 
                    `mulai_berlaku` = ?, 
                    `akhir_berlaku` = ?, 
                    `kuota_max` = ?,
                    `kuota_sisa` = ?,
                    `persen_diskon` = ? 
                    WHERE (`kode` = ?);");
        $stmt->bind_param('isssiiii',$jenis, $nama, $start, $end, $kuota,$kuota, $diskon, $kode);
        $stmt->execute();
        $stmt->close();
    }
    else if($jenis == ""){
        $stmt = $mysqli->prepare(
            "UPDATE `voucher` SET 
                    `kode_menu` = ?, 
                    `nama` = ?, 
                    `mulai_berlaku` = ?, 
                    `akhir_berlaku` = ?, 
                    `kuota_max` = ?,
                    `kuota_sisa` = ?,
                    `persen_diskon` = ? 
                    WHERE (`kode` = ?);");
        $stmt->bind_param('isssiiii',$menu, $nama, $start, $end, $kuota,$kuota, $diskon, $kode);
        $stmt->execute();
        $stmt->close();
    }
    else{
        $stmt = $mysqli->prepare(
            "UPDATE `voucher` SET 
                    `kode_menu` = ?,
                    `kode_jenis` = ?,
                    `nama` = ?, 
                    `mulai_berlaku` = ?, 
                    `akhir_berlaku` = ?, 
                    `kuota_max` = ?,
                    `kuota_sisa` = ?,
                    `persen_diskon` = ? 
                    WHERE (`kode` = ?);");
        $stmt->bind_param('iisssiiii',$menu,$jenis, $nama, $start, $end, $kuota, $kuota, $diskon, $kode);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: voucher.php");
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
    <h1>Update Voucher: <?=$nama?></h1>
    <form action="ubahvoucher.php" method="post">
        <input type="hidden" name="kode" value="<?=$kode?>">

        <label for="nama">Nama Voucher: </label>
        <input type="text" name="nama" value="<?=$nama?>">

        <br>
        <label for="jenis">Jenis Menu yang diskon: </label>
        <select name="jenis">
            <?php
            if($jenis==""){echo "<option value='none' selected disabled hidden>Select an Option</option>";};
            $stmt = $mysqli->prepare("SELECT * FROM menu_jenis");
            $stmt->execute();
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()) { 
                echo ($row['kode']==$jenis)?
                "<option value=".$row["kode"]." selected>".$row['nama']."</option>":
                "<option value=".$row["kode"].">".$row['nama']."</option>";}
            $stmt->close();
            ?>
        </select>

        <br>
        <label for="menu">Menu yang diskon: </label>
        <select name="menu">
            <?php
            if($menu==""){echo "<option value='none' selected disabled hidden>Select an Option</option>";}
            $stmt = $mysqli->prepare("SELECT * FROM menu");
            $stmt->execute();
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()) { 
                echo ($row['kode']==$menu)?
                "<option value=".$row["kode"]." selected>".$row['nama']."</option>":
                "<option value=".$row["kode"].">".$row['nama']."</option>";}
            $stmt->close();
            ?>
        </select>

        <br>
        <label for="start">Tanggal mulai: </label>
        <input type="date" name="start" value="<?=htmlspecialchars($start)?>">

        <br>
        <label for="end">Tanggal berakhir: </label>
        <input type="date" name="end" value="<?=htmlspecialchars($end)?>">

        <br>
        <label for="kuota">Kuota maks: </label>
        <input type="number" name="kuota" min="0" value="<?=$kuota?>">

        <br>
        <label for="diskon">Persen Diskon: </label>
        <input type="number" name="diskon" min="0" max="100" value="<?=$diskon?>">

        <br>
        <input type="submit" value="update" name="update">
    </form>
    </div>
</body>
</html>

<?php $mysqli->close();?>