<?php
session_start();
require_once("class/classUser.php");
$user = new classUser();

$message = "";
$iduser = "";
if(isset($_COOKIE["USER"])){$iduser=$_COOKIE["USER"];}

if (isset($_POST['login'])) {
    $iduser = $_POST["iduser"] ?? '';
    $password = $_POST["password"] ?? '';
    $remember = isset($_POST["remember"]);

    // Validate inputs
    if (empty($iduser) || empty($password)) {
        $message = "Username and password are required";
    } else {
        $result = $user->login($iduser);
            if($result['profil']=='Member' && $result['isaktif']==0) { $message = "Wait for admin to accept your registration"; }
            else if ($result) {
            if (password_verify($password, $result['password'])) {
                session_regenerate_id(true);
                
                $_SESSION["USER"] = $iduser;
                if ($remember) {setcookie("USER", $iduser, time() + (86400 * 30), "/"); }

                $redirect = ($result['profil'] == "Admin") ? "admin/index.php" : "index.php";
                header("Location: $redirect");
                exit();
            } else { $message = "Incorrect username or password"; }
        } else { $message = "Incorrect username or password"; }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug - Login</title>
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
            max-width: 400px;
            text-align: center;
        }

        h1 {
            margin-bottom: 24px;
            color: #683416;
        }

        form {
            text-align: left;
        }

        form div {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            outline: none;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
            margin-right: 8px;
        }

        input[type="submit"] {
            width: 100%;
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

        .register-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #683416;
            font-weight: 500;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .message {
            margin-top: 15px;
            color: red;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="content">
        <h1>Welcome to Koffee StartBug</h1>
        <form action="login.php" method="post">
            <div>
                <label for="iduser">Username</label>
                <input type="text" name="iduser" id="iduser" value="<?=$iduser?>" required placeholder="Enter your username">
            </div>
            
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password">
            </div>
            
            <div>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="display:inline;">Remember Me</label>
            </div>
            
            <input type="submit" value="Login" name="login">
        </form>
        <a href="register.php" class="register-link">Don't have an account? Register here</a>
        <p class="message"><?=$message?></p>
    </div>
</body>
</html>