<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "fullstack");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

$message = "";

if (isset($_POST['register'])) {
    $iduser = $_POST["iduser"];
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE iduser=?");
    $stmt->bind_param('s',$iduser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $password = $_POST["password"];
        $nama = $_POST["nama"];
        $tgllahir = $_POST["tgllahir"];
        $foto = $_POST["foto"];
        $profil = "Member";
        $status = 0;

        $stmt = $mysqli->prepare("INSERT INTO `users` 
        (`iduser`, `password`, `profil`) 
        VALUES (?, ?, ?);");
        $stmt->bind_param('sss', $iduser, $password, $profil);
        $stmt->execute();

        $stmt = $mysqli->prepare("INSERT INTO `member` 
        (`iduser`, `nama`, `tanggal_lahir`, `url_foto`, `isaktif`) 
        VALUES (?, ?, ?, ?, ?);");
        $stmt->bind_param('ssssi', $iduser, $nama, $tgllahir, $foto, $status);
        $stmt->execute();
        $stmt->close();
        header("Location: login.php");
        exit();
    } else { $message = "username already taken"; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div id="content">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <label for="iduser">Username</label>
            <input type="text" name="iduser" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <br>
            <label for="nama">Nama</label>
            <input type="text" name="nama" required>
            <br>
            <label for="tgllahir">Tanggal Lahir</label>
            <input type="date" name="tgllahir" required>
            <br>
            <label for="foto">Foto</label>
            <input type="text" name="foto" required>
            <br>
            <input type="submit" value="register" name="register">
        </form>
        <p><?=$message?></p>
    </div>
</body>
</html>

<?php $mysqli->close(); ?>