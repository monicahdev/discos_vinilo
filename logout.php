<?php
    session_start();
    session_destroy();

    // Redirige al index.php
    header('Location: index.php');
    exit;
?>
