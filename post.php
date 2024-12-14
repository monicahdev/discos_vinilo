<?php
//Importar credenciales de la configuración de la base de datos
require 'db_config.php';


try {
    // Obtener id de disco
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Consultar disco por id
    $statement = $library_pdo->prepare("SELECT * FROM discosvinilo WHERE id = :id");
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $vinyl_record = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$vinyl_record) {
        echo "Disco no encontrado.";
        die;
    }
}
catch (PDOException $e) {
    die("Error al obtener el disco: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlentities($vinyl_record['titulo_album']) ?></title>
</head>
    <body>
        <h1><?= htmlentities($vinyl_record['titulo_album']) ?></h1>
        <p>Artista: <?= htmlentities($vinyl_record['artista']) ?></p>
        <p>Género: <?= htmlentities($vinyl_record['genero_musical']) ?></p>
        <p>Año: <?= htmlentities($vinyl_record['lanzamiento']) ?></p>
        <p>Estado: <?= htmlentities($vinyl_record['estado_disco']) ?></p>
        <p>Precio: <?= htmlentities($vinyl_record['precio']) ?> €</p>
        <img src="<?= htmlentities($vinyl_record['imagen_portada']) ?>" alt="Portada de <?= htmlentities($vinyl_record['titulo_album']) ?>" width="200">
        <br>
        <a href="records.php">Mira todos los discos.</a>
    </body>
</html>