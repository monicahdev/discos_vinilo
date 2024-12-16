<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de inicio - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <?php session_start(); ?>
        <?php
        // Importar credenciales de la configuración de la base de datos
        require 'db_config.php';

        try {
            // Consulta para obtener los 2 discos fijos
            $query_fixed_records = "SELECT id, titulo_album, artista, precio, imagen_portada FROM discosvinilo WHERE id IN (5, 6)";
            $fixed_statement = $library_pdo->query($query_fixed_records);
            $fixed_records = $fixed_statement->fetchAll(PDO::FETCH_ASSOC);

            // Consulta para obtener 3 discos aleatorios
            $query_random_records = "SELECT id, titulo_album, artista, precio, imagen_portada FROM discosvinilo WHERE id NOT IN (5, 6) ORDER BY RAND() LIMIT 3";
            $random_statement = $library_pdo->query($query_random_records);
            $random_records = $random_statement->fetchAll(PDO::FETCH_ASSOC);

            // Array para guardar los discos fijos y aleatorios a mostrar
            $all_vinyls = array_merge($fixed_records, $random_records);

        } catch (PDOException $e) {
            die("Error al obtener los discos: " . $e->getMessage());
        }
        ?>    
            
        <h1>Tienda de vinilos</h1>
        <?php include 'menu.php'; ?>
        <div class="container">
            <?php foreach ($all_vinyls as $vinyl_record): ?>
                <div class="vinyl">
                    <h2>
                        <a href="post.php?id=<?= urlencode($vinyl_record['id']) ?>">
                            <?= htmlentities($vinyl_record['titulo_album']) ?>
                        </a>
                    </h2>
                    <p><strong>Artista:</strong> <?= htmlentities($vinyl_record['artista']) ?></p>
                    <p><strong>Precio:</strong> €<?= htmlentities($vinyl_record['precio']) ?></p>
                    <img src="<?= htmlentities($vinyl_record['imagen_portada']) ?>" alt="Portada del disco">
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>