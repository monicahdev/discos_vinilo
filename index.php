<?php
// Incluir configuración de la base de datos
require 'db_config.php';

try {
    // Consulta para obtener los 2 discos fijos (IDs específicos, por ejemplo, 1 y 2)
    $queryFixed = "SELECT titulo_album, artista, precio, imagen_portada FROM discosvinilo WHERE id IN (5, 6)";
    
    // Consulta para obtener 3 discos aleatorios
    $queryRandom = "SELECT titulo_album, artista, precio, imagen_portada FROM discosvinilo WHERE id NOT IN (5, 6) ORDER BY RAND() LIMIT 3";
    
} catch (PDOException $e) {
    die("Error al obtener los discos: " . $e->getMessage());
}
?>