<?php
session_start();
require_once("../class/classMenu.php");
require_once("../class/classJenisMenu.php");

$message = "";
$menu = new classMenu();
$jenisMenu = new classJenisMenu();

// buat masukin menu
if(isset($_POST['insert'])){
    $jenis = $_POST['jenis'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar'];

    if (!is_null($menu->insertMenu($jenis,$nama,$harga, $gambar))) {
        echo "Data $nama inserted successfully!";
    }
}

// buat hapus menu
if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
    $menu->deleteMenu($kode);
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
            $res = $jenisMenu->getJenisMenu();
            while($row = $res->fetch_assoc()) { echo "<option value=".$row["kode"].">".$row['nama']."</option>";}
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
    $jmlh = $menu->getTotalData();

    $limit = 5;
    (isset($_GET['page']))? $page = $_GET['page'] : $page=1;
    $offset = ($page-1)*$limit;
    $res = $menu->getMenu($offset,$limit);

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
    
    $max_page = ceil($jmlh/$limit);
    echo "<div>";
    if($page!=1){
        echo "<a href="."menu.php?page=1> first </a>
        <a href="."menu.php?page=".($page-1)."> prev </a>";
    }
    for($i=1;$i<=$max_page;$i++){
        echo ($i!=$page)?"<a href="."menu.php?page=".$i."> ".$i." </a>":"<a> ".$i." </a>";
    }
    if($page!=$max_page){
        echo "<a href="."menu.php?page=".($page+1)."> next </a>
        <a href="."menu.php?page=".$max_page."> last </a>";
    }
    echo "</div>";
    ?>
    </div>
</body>
</html>