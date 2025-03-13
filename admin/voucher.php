<?php
session_start();

$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}
$message = "";

if(isset($_POST['insert'])){
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'] ?? null;
    $menu = $_POST['menu'] ?? null;
    $start = $_POST['start'];
    $end = $_POST['end'];
    $kuota = $_POST['kuota'];
    $diskon = $_POST['diskon'];
    
    $stmt = $mysqli->prepare(
        "INSERT INTO `voucher` 
        (`kode_menu`, `kode_jenis`, `nama`, `mulai_berlaku`, `akhir_berlaku`, `kuota_max`, `kuota_sisa`, `persen_diskon`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param('iisssiii',$menu,$jenis, $nama, $start, $end, $kuota, $kuota, $diskon);
    $stmt->execute();
    $stmt->close();
    $message = "Data ".$nama." inserted successfully";
}

if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
    $stmt = $mysqli->prepare("DELETE FROM `voucher` WHERE (`kode` = ?);");
    $stmt->bind_param('i',$kode);
    $stmt->execute();
    $stmt->close();
    header("Location: voucher.php");
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
    <h1>Kelola Voucher</h1>
    <form action="voucher.php" method="post">
        <label for="nama">Nama Voucher: </label>
        <input type="text" name="nama">

        <br>
        <label for="jenis">Jenis Menu yang diskon: </label>
        <select name="jenis">
            <?php
            echo "<option value='none' selected disabled hidden>Select an Option</option>";
            $stmt = $mysqli->prepare("SELECT * FROM menu_jenis");
            $stmt->execute();
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()) { echo "<option value=".$row["kode"].">".$row['nama']."</option>";}
            $stmt->close();
            ?>
        </select>

        <br>
        <label for="menu">Menu yang diskon: </label>
        <select name="menu">
            <?php
            echo "<option value='none' selected disabled hidden>Select an Option</option>";
            $stmt = $mysqli->prepare("SELECT * FROM menu");
            $stmt->execute();
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()) { echo "<option value=".$row["kode"].">".$row['nama']."</option>";}
            $stmt->close();
            ?>
        </select>

        <br>
        <label for="start">Tanggal mulai: </label>
        <input type="date" name="start">

        <br>
        <label for="end">Tanggal berakhir: </label>
        <input type="date" name="end">

        <br>
        <label for="kuota">Kuota maks: </label>
        <input type="number" name="kuota" min="0">

        <br>
        <label for="diskon">Persen Diskon: </label>
        <input type="number" name="diskon" min="0" max="100">

        <br>
        <input type="submit" value="insert" name="insert">
    </form>
    <p><?=$message?></p>

    <h2>Voucher yang tersedia:</h2>
    <?php 
    $stmt = $mysqli->prepare(
        "SELECT v.nama AS vnama, m.nama AS mnama, mj.nama AS mjnama, v.*
                FROM voucher AS v 
                LEFT JOIN menu AS m 
                ON v.kode_menu = m.kode
                LEFT JOIN menu_jenis AS mj
                ON v.kode_jenis = mj.kode;
                ");
    $stmt->execute();
    $res = $stmt->get_result();

    echo "<table>
        <tr>
            <th>Nama</th>
            <th>Jenis Menu Diskon</th>
            <th>Menu Diskon</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Berakhir</th>
            <th>Kuota Maks</th>
            <th>Kuota Sisa</th>
            <th>Diskon</th>
            <th>Hapus</th>
            <th>Ubah</th>
        </tr>";
    while($row = $res->fetch_assoc()) {
        echo 
        "<tr>
            <td>".$row["vnama"]."</td>";
        
        echo (isset($row['mjnama']))?"<td>".$row["mjnama"]."</td>":"<td>---</td>";
        echo (isset($row['mnama']))?"<td>".$row["mnama"]."</td>":"<td>---</td>";

        echo "<td>".$row['mulai_berlaku']."</td>
            <td>".$row['akhir_berlaku']."</td>
            <td>".$row['kuota_max']."</td>
            <td>".$row['kuota_sisa']."</td>
            <td>".$row['persen_diskon']."</td>
            <td> <a href='voucher.php?kode=" . $row['kode'] . "'>Hapus Data</a> </td>
            <td> <a href='ubahvoucher.php?kode=" . $row['kode'] . "'>Ubah Data</a> </td>
        </tr>";
    }
    echo "</table>";
    ?>
    </div>
</body>
</html>

<?php $mysqli->close();?>