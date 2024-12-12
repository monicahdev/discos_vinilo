<?php
// Importar credenciales de la configuración de la base de datos
require 'db_config.php';

try {
    //Implementación de la paginación con parámetros URL
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    //Preparar la consulta que cambia según las variables limit y offset
    $statement = $library_pdo->prepare("SELECT * FROM discosvinilo LIMIT :limit OFFSET :offset");
    //reemplazar el placeholder :limit por la variable $limit
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    //reemplazar el placeholder :offset por la variable $offset
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    //ejecutar la consulta y guardarla en el array $all_vinyls
    $statement->execute();
    $all_vinyls = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Contar registros totales para paginación
    $count_vinyls = $library_pdo->query("SELECT COUNT(*) FROM discosvinilo");
    $total_vinyls = $count_vinyls->fetchColumn();
    $total_pages = ceil($total_vinyls / $limit);

} catch (PDOException $e) {
    die("Error al obtener los discos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de inicio - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Tienda de vinilos</h1>
        <div class="container">
            <?php if (!empty($all_vinyls)): ?>
                <?php foreach ($all_vinyls as $vinyl_record): ?>
                    <div class="vinyl">
                        <h2><?= htmlentities($vinyl_record['titulo_album']) ?></h2>
                        <p><strong>Artista:</strong> <?= htmlentities($vinyl_record['artista']) ?></p>
                        <p><strong>Precio:</strong> €<?= htmlentities($vinyl_record['precio']) ?></p>
                        <img src="<?= htmlentities($vinyl_record['imagen_portada']) ?>" alt="Portada del disco">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontró ningún vinilo.</p>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <?php if ($total_pages > 1): ?>
                <ul>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li>
                            <a href="?page=<?= $i ?>" <?= $i === $page ? 'style="font-weight: bold;"' : '' ?>>
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            <?php endif; ?>
        </div>
    </body>
</html>