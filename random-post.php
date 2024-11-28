<?php
//Importar credenciales de la configuración de la base de datos
require 'db_config.php';

try {
    //Consultar en la tabla y mostrar 1 solo disco
    $query = "SELECT * FROM discosvinilo ORDER BY RAND() LIMIT 1";
    $stmt = $library_pdo->query($query);

    //Mostrar el disco
    $vinyl_record = $stmt->fetch(PDO::FETCH_ASSOC);

    /*Asegurarse de que existe el disco y mostrarlo con estas características*/
    if ($vinyl_record) {
        
        echo "<h1>Discos de vinilo</h1>";
        echo "<p>Título del disco: " . htmlspecialchars($vinyl_record['titulo_album']) . "</p>";
        echo "<p>Artista: " . htmlspecialchars($vinyl_record['artista']) . "</p>";
        echo "<p>Género musical: " . htmlspecialchars($vinyl_record['genero_musical']) . "</p>";
        echo "<p>Año de lanzamiento: " . htmlspecialchars($vinyl_record['lanzamiento']) . "</p>";
        echo "<p>Estado del disco: " . htmlspecialchars($vinyl_record['estado_disco']) . "</p>";
        echo "<p>Precio: €" . htmlspecialchars($vinyl_record['precio']) . "</p>";
        echo "<p>Imagen de portada:</p><img src='" . htmlspecialchars($vinyl_record['imagen_portada']) . "' alt='portada';'>";
    } else {
        echo "<p>No hay discos en la base de datos.</p>";
    }
} catch (PDOException $e) {
    echo "Error al obtener el disco: " . $e->getMessage(); //Mensaje en caso de error
}
?>