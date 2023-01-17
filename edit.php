<?php
include 'includes/autoload.php';
include 'includes/database.php';
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    header("Location: index.php");
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}
$poruka="";

$sql = "SELECT * FROM  blogs WHERE blogid = :blogid";
try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':blogid',$_GET['id']);
    $stmt->execute();
    $num=$stmt->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}
if ($num > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $blog['naslov']= htmlentities($row['naslov']);
    $blog['tekst']= htmlentities($row['tekst']);
    $blog['author']= htmlentities($row['author']);
}
if (isset($_POST['naslov'])&& $_POST['tekst']){
    if ($blog['author']!=$_SESSION['userid']){
        $poruka="Post nije Vas, nemate pravo na izmjene!";
    }else{


    $blog['naslov']=$_POST['naslov'];
    $blog['tekst']=$_POST['tekst'];
    $sql = "UPDATE blogs
                SET naslov = :naslov,
                    tekst = :tekst,
                    author=:author 
                    WHERE
                    blogid=:blogid";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':naslov', $blog['naslov']);
        $stmt->bindParam(':tekst', $blog['tekst']);
        $stmt->bindParam(':author', $_SESSION['userid']);
        $stmt->bindParam(':blogid', $_GET['id']);
        if ($stmt->execute()){

            $poruka='Izmjenili ste post!';
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} }

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <title>Blog</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="homepage.php">Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="noviPost.php">Novi Post </a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="POST">
            <span class="nav-link" href="#"><?php echo $_SESSION['ime'] . ' ' . $_SESSION['prezime'] ?></span>
            <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Log Out" name="logout">
        </form>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="font-weight-bold text-center mb-5">Kreiraj Novi Blog Post</h1>
    <form method="POST">
        <div class="form-group">
            <label for="naslov">Naslov</label>
            <input type="text" class="form-control" id="naslov" name="naslov" required value="<?php echo $blog['naslov'] ?>">
        </div>
        <div class="form-group">
            <label for="tekst">Tekst </label>
            <textarea class="form-control" id="tekst" name="tekst" rows="3" required><?php echo $blog['tekst']?></textarea>
        </div>
        <div class="d-flex justify-content-center mt-5">
            <input type="submit" class="btn btn-primary text-center" value="Sacuvaj">
        </div>
    </form>
    <h5 class="text-center mt-5 text-success"><?php echo $poruka?></h5>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</body>
</html>