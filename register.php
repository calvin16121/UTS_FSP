<?php
require_once("class/classMember.php");
session_start();
$member = new classMember();

$message = "";

if (isset($_POST['register'])) {
    $iduser = htmlentities($_POST["iduser"]);

    if ($member->checkIDUser($iduser)) {
        $hash_password = password_hash(htmlentities($_POST["password"]),PASSWORD_DEFAULT);
        $nama = htmlentities($_POST["nama"]);
        $tgllahir =  htmlentities($_POST["tgllahir"]);
        $foto =  htmlentities($_POST["foto"]);
        $profil = "Member";
        $status = 0;

        if(!is_null($member->insertMember($iduser, $hash_password, $profil,$nama, $tgllahir, $foto, $status)))
        {header("Location: login.php");exit();}
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