<?php
include 'includes/autoload.php';
include 'includes/database.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}
if (isset($_POST['logout'])) {
    session_unset();
    header("Location: index.php");
}
$sql = "SELECT * FROM  blogs b INNER JOIN users u ON b.author = u.userid WHERE blogid = :blogid";
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
    $blog['author']= htmlentities($row['username']);
    $blog['email']= htmlentities($row['email']);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="css/homepage.css">
    <title>Blog</title>
</head>
<body>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="homepage.php">IT</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="noviPost.php">Novi Post </a>
            </li>
            <li class="nav-item">
                <span class="nav-link" href="#"><?php echo $_SESSION['ime'] . ' ' . $_SESSION['prezime'] ?></span>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="POST">
            <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Log Out" name="logout">
        </form>
    </div>
</nav>
<div class="container-fluid">
    <div class="jumbotron bg-white d-flex flex-column justify-content-center align-items-center" id="home">
        <h1 class="mb-5">Blog</h1>
        <p class="mb-5">Projekat za Internet Tehnologije</p>
    </div>
</div>

<div>
    <div class="container">
            <article class="my-5">
                <h2 class="text-center mb-5">
                    <a href="/blog/<?php echo $blog['id'] ?>"><?php echo $blog['naslov']?></a>
                </h2>
                <div class="text-center mt-2">
                        <p class="lead" style="word-spacing: 5px; line-height: 1.8;"><?php echo $blog['tekst']?></p>
                </div>
            </article>
        <p>Autor: <?php echo $blog['author'] ?></p>
        <p>Kontakt: <?php echo $blog['email'] ?></p>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>

</body>
</body>
</html>
