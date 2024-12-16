<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menú - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <nav class="menu">
            <a href="index.php">Home</a>
            <a href="random-post.php">Disco</a>
            <a href="records.php">Discos</a>
            <a href="api/records.php?page=1" target="_blank">API_discos</a>
            <a href="api/record.php?id=1" target="_blank">API_disco</a>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="edit.php">Perfil de usuario</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign up</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php">Logout</a>
                <p>Hola! Nos alegramos de volverte a ver, <?= htmlentities($_SESSION['username']) ?></p>
            <?php endif; ?>    
        </nav>
    </body>
</html>