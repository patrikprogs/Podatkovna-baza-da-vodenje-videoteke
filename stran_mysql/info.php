<?php $isLoggedIn = isset($_SESSION['username']);?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Videoteka</title>

    <link rel="StyleSheet" type="text/css" href="CSS/stil.css">
</head>
<body>

<h1>O strani</h1>

<?php if ($isLoggedIn): ?>        

<header>
    <nav>  
        <a href="index.php">Osnovna stran</a> 
        <a href="logout.php">Odjava</a>
        <a href="deleteuser.php">Izbris računa</a>
    </nav>
</header>

<?php else: ?>

<header>
    <nav>
        <a href="index.php">Osnovna stran</a>   
        <a href="register.php">Registracija</a> 
        <a href="login.php">Prijava</a>
    </nav>
</header>

<?php endif; ?>



<div class="okvircek2">
<h2>Info</h2>
<p>
Gimnazija Vič <br>
Avtor: Patrik Linke, 4.c<br>
Mentor: Profesor Bajec Klemen<br>
Šolsko leto: 2024/25<br>
</p>

<h2>Vir slike</h2>
<p>
Pixels, Dostopno na spletnem naslovu: <a style="  color:rgb(219, 73, 73);" href="https://www.pexels.com/" >pexels.com</a> <br>
Povezava do slike :<a style="  color:rgb(219, 73, 73);" href="https://www.pexels.com/photo/twisted-photo-film-603580/" >slika</a>
</p>
</div>

</body>