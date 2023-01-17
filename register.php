<?php
include 'includes/autoload.php';
include 'includes/database.php';
$provera = 0;
$userErr="";
$imeErr="";
$prezimeErr="";
$emailErr="";
$sifraErr="";
if (isset($_POST['username'])){
    $username = $_POST['username'];
    if (strlen($username)>4){
        $provera++;
    }else{
        $userErr="Korisnicko ime mora da ima bar 4 karaktera";
    }
}
if (isset($_POST['sifra'])){
    $sifra=$_POST['sifra'];
    if (strlen($sifra)>5){
        $provera++;
        $sifraHash = password_hash($sifra, PASSWORD_BCRYPT);


    }else{
        $sifraErr="Sifra mora da ima bar 6 karaktera";
    }
}
if (isset($_POST['ime'])){
    $ime=$_POST['ime'];
    if (strlen($ime)>0){
        $provera++;
    }else{
        $imeErr="Polje ime ne smije biti prazno";
    }
}

if (isset($_POST['prezime'])){
    $prezime=$_POST['prezime'];
    if (strlen($prezime)>0){
        $provera++;
    }else{
        $prezimeErr="Polje prezime ne smije biti prazno";
    }
}
if (isset($_POST['email'])){
    $email=$_POST['email'];
    if (strlen($email)>0){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $provera++;
        }else{
            $emailErr="Polje email nije ispravno";
        }
    }else{
        $emailErr="Polje email ne smije biti prazno";
    }
}



if ($provera==5){

    $sql = "INSERT INTO users
                SET username = :username,
                    sifra = :sifra,
                    ime=:ime,
                    prezime=:prezime,
                    email=:email";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':sifra', $sifraHash);
        $stmt->bindParam(':ime', $ime);
        $stmt->bindParam(':prezime', $prezime);
        $stmt->bindParam(':email', $email);
        if ($stmt->execute()){
            $poruka='Uspjesna registracija!';
         header("Location: index.php?poruka=".$poruka);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
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
<div class="mainReg">
    <p align="center">Registracija</p>
    <form method="POST">
        <small><?php echo $userErr?></small>
        <input class="user " type="text" align="center" placeholder="Korisnicko Ime" name="username" value="<?php if (isset($username)){ echo $username;}?>">
        <small><?php echo $imeErr?></small>
        <input class="user " type="text" align="center" placeholder="Ime" name="ime" value="<?php if (isset($ime)){ echo $ime;}?>">
        <small><?php echo $prezimeErr?></small>
        <input class="user " type="text" align="center" placeholder="Prezime" name="prezime" value="<?php if (isset($prezime)){ echo $prezime;}?>">
        <small><?php echo $emailErr?></small>
        <input class="user " type="text" align="center" placeholder="Email" name="email" value="<?php if (isset($email)){ echo $email;}?>">
        <small><?php echo $sifraErr?></small>
        <input class="user" type="password" align="center" placeholder="Sifra" name="sifra" value="<?php if (isset($sifra)){ echo $sifra;}?>">
        <input class="reg" type="submit" align="center" value="Registracija">
    </form>
    <a id="loginLink" href="index.php">Nazad na Prijavu</a>
</div>

</body>
</html>