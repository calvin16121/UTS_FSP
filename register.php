<?php
require_once("class/classUser.php");
session_start();
$member = new classUser();

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
    <title>Koffee StartBug - Register</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #fdf6f0;
            color: #4a2e1e;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #content {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(104, 52, 22, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            margin-bottom: 24px;
            color: #683416;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        input[type="text"],
        input[type="password"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            outline: none;
        }

        input[type="submit"] {
            padding: 12px;
            background-color: #683416;
            color: wheat;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #5a2d15;
        }

        p {
            margin-top: 15px;
            color: red;
            font-size: 14px;
            text-align: center;
            font-weight: bold;
        }

        @media (max-width: 500px) {
            #content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div id="content">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <div>
                <label for="iduser">Username</label>
                <input type="text" name="iduser" id="iduser" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div>
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required>
            </div>

            <div>
                <label for="tgllahir">Tanggal Lahir</label>
                <input type="date" name="tgllahir" id="tgllahir" required>
            </div>

            <div>
                <label for="foto">Link Foto</label>
                <input type="text" name="foto" id="foto" required placeholder="https://...">
            </div>
            
            <input type="submit" value="Register" name="register">
        </form>
        <p><?=$message?></p>
    </div>
</body>
</html>