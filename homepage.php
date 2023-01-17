<?php
include 'includes/autoload.php';
include 'includes/database.php';
session_start();
$poruka = "";
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_POST['logout'])) {
    session_unset();
    header("Location: index.php");
}

if (isset($_GET['del'])) {
    $sql = "DELETE  FROM  blogs WHERE blogid = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_GET['del']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$listaBlogova = [];
$sql = "SELECT * FROM  blogs b INNER JOIN users u ON b.author = u.userid";
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $num = $stmt->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}

if ($num > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $blog['naslov'] = htmlentities($row['naslov']);
        $blog['tekst'] = htmlentities($row['tekst']);
        $blog['id'] = htmlentities($row['blogid']);
        $blog['author'] = htmlentities($row['username']);
        $blog['authorID'] = htmlentities($row['author']);
        array_push($listaBlogova, $blog);
    }
} else {
    $poruka = "Trenutno nema blogova";
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
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
<div class="container-fluid">
    <div class="jumbotron bg-white d-flex flex-column justify-content-center align-items-center" id="home">
        <h1 class="mb-5">IT Novosti</h1>
        <p class="mb-5">Projekat za Internet Tehnologije</p>
    </div>
</div>
<p class="text-danger text-center"><?php echo $poruka ?></p>

<div id="main" class="content" role="main">
    <div class="container">
        <?php foreach ($listaBlogova as $value) {
            ?>
            <article class="my-5">
                <h2 class="text-center">
                    <a href="post.php?id=<?php echo $value['id'] ?>"><?php echo $value['naslov'] ?></a>
                </h2>
                <span>Author: <?php echo $value['author'] ?></span>
                <div class="text-center mt-2">
                    <div class="mb-3">
                        <p class="lead"><?php echo substr($value['tekst'], 0, 400) ?></p>

                        <?php if ($value['authorID'] == $_SESSION['userid']) { ?>
                            <button class="btn btn-primary mx-5"><a href='homepage.php?del=<?php echo $value["id"] ?>'
                                                                    class="text-white">Obrisi</a></button>
                            <button class="btn btn-primary my-1"><a href='edit.php?id=<?php echo $value["id"] ?>'
                                                                    class="text-white">Izmjeni</a></button>
                        <?php } ?>
                    </div>
                    <a class="my-5" href="post.php?id=<?php echo $value['id'] ?>">Procitaj Vise</a>
                </div>
            </article>
        <?php } ?>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>

</body>
</body>
</html>
