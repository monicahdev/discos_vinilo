<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalle vinilo - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
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
        <h1>Tienda de vinilos</h1>
        <?php include 'menu.php'; ?>
        <div class="vinyl">
            <h2><?= htmlentities($vinyl_record['titulo_album']) ?></h2>
            <p><strong>Artista:</strong> <?= htmlentities($vinyl_record['artista']) ?></p>
            <p><strong>Género:</strong> <?= htmlentities($vinyl_record['genero_musical']) ?></p>
            <p><strong>Año:</strong> <?= htmlentities($vinyl_record['lanzamiento']) ?></p>
            <p><strong>Estado:</strong> <?= htmlentities($vinyl_record['estado_disco']) ?></p>
            <p><strong>Precio:</strong> <?= htmlentities($vinyl_record['precio']) ?> €</p>
            <img src="<?= htmlentities($vinyl_record['imagen_portada']) ?>" alt="Portada de <?= htmlentities($vinyl_record['titulo_album']) ?>" width="200">
            <br>
        </div>
    </body>
</html>