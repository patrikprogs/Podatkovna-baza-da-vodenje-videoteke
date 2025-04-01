<?php
session_start();
require 'config.php'; 

// ali si upisan
if (!isset($_SESSION['user_id'])) {
    echo "Error: Moraš biti vpisan v račun.";
    exit;
}


$userId = $_SESSION['user_id'];

// preveri ali smo pritisnili gumb (izbris rauna)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // seznam videoposnetkov iz baze od uporabnika
    $stmt = $db->prepare("SELECT filename FROM videos WHERE user_id = ?");
    $stmt->execute([$userId]);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // izbris vedeoposnetkov
    foreach ($videos as $video) {

        $filePath = "uploads/" . $video['filename'];
        
        if (file_exists($filePath)) {
            unlink($filePath); // Izbris
        }
    }

    // Izbris video informacij iz podatkovne baze
    $stmt = $db->prepare("DELETE FROM videos WHERE user_id = ?");
    $stmt->execute([$userId]);

    //Izbris uporabnika iz podatkovne baze
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Log out
    session_destroy();
    header("Location: index.php");
    exit;
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

<h1>Izbris računa</h1>

<header>
    <nav>
        <a href="index.php">Domov</a>
        <a href="logout.php">Odjava</a> 
        <a href="info.php">O strani</a> 
    </nav>
</header>

<div class="okvircek">
    <p>Ali ste prepričani, da želite izbrisati svoj račun? To dejanje ni mogoče razveljaviti. Z izbrisom računa izbrišete tudi vse videoposnetke, ki ste jih naložili.</p>
    
    <form action="deleteuser.php" method="post" onsubmit="return confirm('Z izbrisom računa izbrišete tudi vse videoposnetke, ki ste jih naložili.');">
        <button type="submit">Izbriši moj račun</button>
    </form>
</div>

</body>
</html>