<?php
require 'config.php';

$error = ""; // spremenljivka za error sporočilo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        // najdi če upoarabnik že obszaja d db
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) { // preveri če se geslo ujema za hashom v bazi
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Napačno uporabniško ime ali geslo.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
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

<h1>Prijava</h1>


<header>
    <nav>
        <a href="index.php">Osnovna stran</a> 
        <a href="register.php">Registracija</a>
        <a  href="info.php">O strani</a> 
    </nav>
</header>

<div class="okvircek">

<form method="post">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>

    <?php if (!empty($error)) echo "<p class='neuspeh'>$error</p>"; ?>
</form>

</div>

</body>

</html>
