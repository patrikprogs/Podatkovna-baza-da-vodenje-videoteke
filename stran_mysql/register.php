<?php
require 'config.php';

$neuspeh = "";  
$uspeh = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //geslo pretvori v hash obliko (PASSWORD_DEFAULT je način inkriptiranja)

    try {
        // Preveri če uporabniško ime že obstaja
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $neuspeh = "Uporabniško ime nažalost že obstaja. Izberi novega!";
        } else {
            // Dodaj uporabnika v podatkovno bazo
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
            $uspeh = "Registracija je bila uspešna. Prijavite se na zrrani Prijava.";
        }
    } catch (PDOException $e) {
        $neuspeh = "Error: " . $e->getMessage();
    }
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

<h1>Registracija</h1>

<header>
    <nav>
        <a href="index.php">Osnovna stran</a> 
        <a href="login.php">Prijava</a>
        <a  href="info.php">O strani</a> 
    </nav>
</header>

<div class="okvircek">
<form method="post">

    <input type="text" name="username" required placeholder="Username"> 
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Register</button>

    <br>

    <?php if (!empty($neuspeh)) echo "<div class='neuspeh'> $neuspeh </div>"; ?>
    <?php if (!empty($uspeh)) echo "<div class='uspeh'> $uspeh</div> "; ?>

</form>
</div>
</body>

</html>