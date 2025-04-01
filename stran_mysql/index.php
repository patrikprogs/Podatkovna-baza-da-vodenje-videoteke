<?php
// začnemo session da lahko previrjamo ali smmo vpisani ali ne
session_start();
require 'config.php';

//preverimo če smovpisani določimo uporabniško ime in User ID
$isLoggedIn = isset($_SESSION['username']);
$username = $_SESSION['username'] ?? '';
$userId = $_SESSION['user_id'] ?? 0;

// preverimo če uploads mapa obstaja (__DIR__ = directory)
$uploadDir = __DIR__ . "/uploads/";

if (!is_dir($uploadDir)) {mkdir($uploadDir, 0777, true); } // če pot za mapo ne obstaja naredimo mapo


// Za video nalaganje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $videoFile = $_FILES['video'];
    $videoName = $_POST['video_name'] ?? 'Neimenovan'; //Ime videa, če ni dan uporabim "Neimenovan"
    $filename = uniqid() . '.' . pathinfo($videoFile['name'], PATHINFO_EXTENSION); // unikatno ime za video datoteko
    $uploadPath = $uploadDir . $filename;

    //nalaganje videa
    if (move_uploaded_file($videoFile['tmp_name'], $uploadPath)) {
        $stmt = $db->prepare("INSERT INTO videos (user_id, filename, video_name) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $filename, $videoName]);
    } else {
        echo "Upload failed.";
    }
}

// Najdi vse videodatoteke uporabnika
if ($isLoggedIn) {
    $stmt = $db->prepare("SELECT * FROM videos WHERE user_id = ?");
    $stmt->execute([$userId]);
    $userVideos = $stmt->fetchAll(PDO::FETCH_ASSOC); // vn dobimo associative array (ključi so stringi)
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Videoteka</title>

    <link rel="StyleSheet" type="text/css" href="CSS/stil.css">
</head>
<body>

<h1>Videoteka</h1>

    <?php if ($isLoggedIn): ?>        
        <header>
            <nav>
                
                <a href="logout.php">Odjava</a>
                <a href="deleteuser.php">Izbris računa</a>
                <a  href="info.php">O strani</a> 

            </nav>
        </header>

        <div class="okvircek2">
        <h2>Uporabnik: <?= htmlspecialchars($username) ?></h2>


        <form method="post" enctype="multipart/form-data">
            <input type="text" name="video_name" placeholder="Upiši ime videa" required>
            <input type="file" name="video" required>
            <button type="submit">Naloži</button>
        </form>

        <h2>Vaši videoposnetki</h2>
        <?php foreach ($userVideos as $video): ?>
            
            <video width="712" height="512" controls>
                <source src="uploads/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
            </video>
            <div><?= htmlspecialchars($video['video_name']) ?></div>  <!-- Display video name -->


            <form method="post" action="delete.php">
                <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                <button type="submit" onclick="return confirm('Ali si prepričan da želiš izbrisati video?');">Izbriši</button>
            </form>
            <hr>
            
        <?php endforeach; ?>



    <?php else: ?>

        <header>
            <nav>
                <a href="register.php">Registracija</a> 
                <a href="login.php">Prijava</a>
                <a  href="info.php">O strani</a> 
            </nav>
        </header>


        <p class="okvircek2">
         Ta spletna stran omogoča nalaganje in ogled videoposnetkov. 
        </p>


    <?php endif; ?>

</body>
</html>
