<?php
include 'includes/autoload.php';
include 'includes/database.php';
session_start();
$provera = true;
$err = "";
if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['sifra'])) {
    $sifra = $_POST['sifra'];
}
isset($_POST['username']) ? $username = $_POST['username'] : $provera = false;
isset($_POST['sifra']) ? $sifra = $_POST['sifra'] : $provera = false;
if ($provera) {
    $sql = "SELECT * FROM users WHERE username = :username";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $num = $stmt->rowCount();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $ime = htmlentities($row["ime"]);
        $prezime = htmlentities($row["prezime"]);
        $sifraHash = htmlentities($row["sifra"]);
        $email = htmlentities($row["email"]);
        $id = htmlentities($row["userid"]);
        if (password_verify($sifra, $sifraHash)) {
            $_SESSION['username'] = $username;
            $_SESSION['ime'] = $ime;
            $_SESSION['prezime'] = $prezime;
            $_SESSION['email'] = $email;
            $_SESSION['userid'] = $id;
            header("Location: homepage.php");

        } else {
            $err = "Sifra nije ispravna";
        }
    } else {
        $err = "Korisnik ne postoji";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="main">
    <p align="center">Prijava</p>
    <form method="POST">
        <small style="padding-left: 30px"><?php echo $err ?></small>
        <input class="user" type="text" align="center" placeholder="Korisnicko ime" name="username">
        <input class="user" type="password" align="center" placeholder="Sifra" name="sifra">
        <div class="contain">
            <input class="log" type="submit" align="center" value="Prijava">
            <a class="log" href="register.php" align="center">Registracija</a>
        </div>
    </form>
    <?php

    $poruka = "";
    if (isset($_GET['poruka'])) {
        $poruka = $_GET['poruka'];
    }
    ?>
    <p class="poruka"><?php echo $poruka ?></p>
</div>

</body>
</html>