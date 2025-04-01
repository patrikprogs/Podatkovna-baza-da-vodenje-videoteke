<?php
require 'config.php';

//če je bil request za deletanje -> najdi id od videa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_id'])) {
    $videoId = $_POST['video_id'];

    //Ime videa iz baze
    $stmt = $db->prepare("SELECT filename FROM videos WHERE id = ?");
    $stmt->execute([$videoId]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($video) {

        //pot do videa
        
        $filePath = __DIR__ . "/uploads/" . $video['filename'];

        // izbris videa če obstaja
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // izbris is podatkovne baze
        $stmt = $db->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->execute([$videoId]);

        header("Location: index.php");
        exit;
    } else {
        die("Invalid video.");
    }
}

die("Invalid request.");
?>
