<?php
require_once("../class/classJenisMenu.php");
session_start();
$jenisMenu = new classJenisMenu();

$message = "";

if(isset($_POST['insert'])){
    $nama = $_POST['nama'];
    if(!is_null($jenisMenu->insertJenisMenu($nama))) 
    {$message = "Data ".$nama." inserted successfully";}
}

if(isset($_GET['kode'])){
    $kode = $_GET['kode'];
    $jenisMenu->deleteJenisMenu($kode);
    header("Location: jenismenu.php");
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
    <h1>Kelola jenis menu</h1>
    <form action="jenismenu.php" method="post">
        <label for="nama">Masukan Jenis Menu: </label>
        <input type="text" name="nama">
        <input type="submit" value="insert" name="insert">
    </form>
    <p><?=$message?></p>

    <h2>Jenis menu yang tersedia:</h2>
    <?php
    $jmlh = $jenisMenu->getTotalData();

    $limit = 5;
    (isset($_GET['page']))? $page = $_GET['page'] : $page=1;
    $offset = ($page-1)*$limit;
    $res = $jenisMenu->getJenisMenu($offset,$limit);

    echo "<table>
        <tr>
            <th>Nama</th>
            <th>Hapus</th>
            <th>Ubah</th>
        </tr>";
    while($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>".$row['nama']."</td>
                <td> <a href='jenismenu.php?kode=" . $row['kode'] . "'>Hapus Data</a> </td>
                <td> <a href='ubahjenismenu.php?kode=" . $row['kode'] . "'>Ubah Data</a> </td>
            </tr>";
    }
    echo "</ul>";
    
    $max_page = ceil($jmlh/$limit);
    
    if($max_page > 1){
    echo "<div>";
        if($page!=1){
            echo "<a href="."jenismenu.php?page=1> first </a>
            <a href="."jenismenu.php?page=".($page-1)."> prev </a>";
        }
        for($i=1;$i<=$max_page;$i++){
            echo ($i!=$page)?"<a href="."jenismenu.php?page=".$i."> ".$i." </a>":"<a> ".$i." </a>";
        }
        if($page!=$max_page){
            echo "<a href="."jenismenu.php?page=".($page+1)."> next </a>
            <a href="."jenismenu.php?page=".$max_page."> last </a>";
        }
        echo "</div>";
    }
    ?>
    </div>
</body>
</html>