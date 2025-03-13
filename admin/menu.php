<?php
session_start();

$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}
$message = "";

if(isset($_POST['insert'])){
    $kode_jenis = $_POST['jenis'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar'];

    $stmt = $mysqli->prepare("INSERT INTO `menu` (`kode_jenis`,`nama`,`harga_jual`) VALUES (?,?,?);");
    $stmt->bind_param('isd',$kode_jenis,$nama, $harga);
    if ($stmt->execute()) {
        $last_id = $stmt->insert_id;
        $ext = pathinfo($gambar['name'],PATHINFO_EXTENSION);
        $url = "images/".$last_id.".".$ext;

        $stmt2 = $mysqli->prepare("UPDATE `menu` SET `url_gambar` = ? WHERE `kode` = ?;");
        $stmt2->bind_param('si',$url,$last_id);
        $stmt2->execute();
        $stmt2->close();

        move_uploaded_file($gambar['tmp_name'],"../".$url);
        echo "Data $nama inserted successfully!";
    } else { echo "Error: " . $stmt->error; }
    $stmt->close();
}

if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
    if(file_exists("../images/".$kode.".png")){unlink("../images/".$kode.".png");}
    elseif(file_exists("../images/".$kode.".jpeg")){unlink("../images/".$kode.".jpeg");}
    $stmt = $mysqli->prepare("DELETE FROM `menu` WHERE (`kode` = ?);");
    $stmt->bind_param('i',$kode);
    $stmt->execute();
    $stmt->close();
    header("Location: menu.php");
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
    <h1>Kelola menu</h1>
    <form action="menu.php" method="post" enctype="multipart/form-data">
        <label for="nama">Nama Menu: </label>
        <input type="text" name="nama" required>
        <br>
        <label for="nama">Jenis Menu: </label>
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
        <label for="harga">Harga Jual: </label>
        <input type="number" name="harga" required>
        <br>
        <label for="gambar">Gambar</label>
        <input type="file" name="gambar" accept="image/jpeg, image/png">
        <br>
        <input type="submit" value="insert" name="insert">
    </form>
    <p><?=$message?></p>

    <h2>Menu yang tersedia:</h2>
    <?php 
    $stmt = $mysqli->prepare(
        "SELECT m.kode, m.nama as nama_m,mj.nama AS nama_mj,m.harga_jual,m.url_gambar 
                FROM menu_jenis AS mj 
                INNER JOIN menu AS m 
                ON m.kode_jenis = mj.kode;
                ");
    $stmt->execute();
    $res = $stmt->get_result();

    echo "<table>
        <tr>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Harga</th>
            <th>Gambar</th>
            <th>Hapus</th>
            <th>Ubah</th>
        </tr>";
    while($row = $res->fetch_assoc()) {
        echo 
        "<tr>
            <td>".$row["nama_m"]."</td>
            <td>".$row["nama_mj"]."</td>
            <td>".$row["harga_jual"]."</td>";

        echo
        (file_exists("../".$row['url_gambar']))?
        "<td><img src='../".$row["url_gambar"]."' style='width:100px;'></td>":
        "<td>". "ceritanya gambar" ."</td>";
            
        echo "
        <td> <a href='menu.php?kode=" . $row['kode'] . "'>Hapus Data</a> </td>
        <td> <a href='ubahmenu.php?kode=" . $row['kode'] . "'>Ubah Data</a> </td>
        </tr>";
    }
    echo "</table>";
    $stmt->close();
    ?>
    </div>
</body>
</html>

<?php $mysqli->close();?>