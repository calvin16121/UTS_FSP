<?php
session_start();

$mysqli = new mysqli("localhost","root","","fullstack");
if($mysqli->connect_errno){ die("Failed to connect t MySQL: ".$mysqli->connect_error);}
$message = "";

if(isset($_GET['kode'])){
    $id = $_GET['kode'];
    $stmt = $mysqli->prepare("UPDATE `fullstack`.`member` SET `isaktif` = '1' WHERE (`iduser` = ?);");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    header("Location: member.php");
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
    <a href="admin.php">admin page</a>
    <h2>Member yang belum aktif:</h2>
    <?php 
    $stmt = $mysqli->prepare("SELECT * FROM member WHERE isaktif=0");
    $stmt->execute();
    $res = $stmt->get_result();
    $jmlh = $res->num_rows;

    echo "<table>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Tanggal Lahir</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>";
    while($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>".htmlspecialchars($row['iduser'])."</td>
                <td>".htmlspecialchars($row['nama'])."</td>
                <td>".htmlspecialchars($row['tanggal_lahir'])."</td>
                <td><img src=".htmlspecialchars($row["url_foto"])." style='width:100px;'></td>
                <td> <a href='member.php?kode=" .htmlspecialchars($row['iduser']). "'>Aktivasi</a> </td>
            </tr>";
    }
    echo "</ul>";
    
    $limit=5;
    (isset($_GET['page']))? $page = $_GET['page'] : $page=1;
    $max_page = ceil($jmlh/$limit);
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
    $stmt->close();
    ?>
    </div>
</body>
</html>

<?php $mysqli->close();?>