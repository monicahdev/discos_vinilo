
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tienda de vinilos</title>
    </head>
    <body>
        <?php
        // Importar credenciales de la configuración de la base de datos
        require 'db_config.php';

        try {
            // Consultar en la tabla y mostrar 1 solo disco
            $query = "SELECT * FROM discosvinilo ORDER BY RAND() LIMIT 1";
            $statement = $library_pdo->query($query);

            // Traer el disco de la consulta
            $vinyl_record = $statement->fetch(PDO::FETCH_ASSOC);
            
            /* Asegurarse de que existe el disco y mostrarlo con estas características */
            if ($vinyl_record) {
                echo "<h1>Tienda de vinilos</h1>";
                include 'menu.php';
                echo "<div class='vinyl'>";
                echo "<h2><strong>Título del disco: </strong>" . htmlentities($vinyl_record['titulo_album']) . "</h2>";
                echo "<p><strong>Artista: </strong>" . htmlentities($vinyl_record['artista']) . "</p>";
                echo "<p><strong>Género musical: </strong>" . htmlentities($vinyl_record['genero_musical']) . "</p>";
                echo "<p><strong>Año de lanzamiento: </strong>" . htmlentities($vinyl_record['lanzamiento']) . "</p>";
                echo "<p><strong>Estado del disco: </strong>" . htmlentities($vinyl_record['estado_disco']) . "</p>";
                echo "<p><strong>Precio: € </strong>" . htmlentities($vinyl_record['precio']) . "</p>";
                echo "<p><strong>Imagen de portada: </strong></p><img src='" . htmlentities($vinyl_record['imagen_portada']) . "' alt='Portada del vinilo'>";
                echo "</div>";
            } else {
                echo "<p>No hay discos en la base de datos.</p>";
            }
        } catch (PDOException $e) {
            // Mensaje en caso de error
            echo "<p>Error al obtener el disco: " . $e->getMessage() . "</p>"; 
        }
        ?>
    </body>
</html>