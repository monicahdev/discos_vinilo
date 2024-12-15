<?php
// Importar credenciales de la configuración de la base de datos
require 'db_config.php';

try {
    //Variables para los parámetros de los filtros pasados por url
    $artist_filter = isset($_GET['artist']) ? $_GET['artist'] : '';
    $genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
    $record_status_filter = isset($_GET['status']) ? $_GET['status'] : '';
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'id';
    $order_dir = isset($_GET['order_dir']) && in_array($_GET['order_dir'], ['asc', 'desc']) ? $_GET['order_dir'] : 'asc';

    //Orden por defecto de aparición de discos
    $permitted_order = ['id', 'precio', 'lanzamiento'];
    if (!in_array($order_by, $permitted_order)) {
        $order_by = 'id';
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    //Consulta con filtros dinámicos
    $query = "SELECT * FROM discosvinilo WHERE true";
    $parameters = [];

    if (!empty($artist_filter)) {
        $query .= " AND artista LIKE :artist";
        $parameters[':artist'] = "%$artist_filter%";
    }

    if (!empty($genre_filter)) {
        $query .= " AND genero_musical LIKE :genre";
        $parameters[':genre'] = "%$genre_filter%";
    }

    if (!empty($record_status_filter)) {
        $query .= " AND estado_disco = :status";
        $parameters[':status'] = $record_status_filter;
    }

    $query .= " ORDER BY $order_by $order_dir LIMIT :limit OFFSET :offset";

    $statement = $library_pdo->prepare($query);

    foreach ($parameters as $key => $value) {
        $statement->bindValue($key, $value);
    }

    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();
    $all_vinyls = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todos los registros para paginación con filtros incluidos
    $count_vinyl_query = "SELECT COUNT(*) FROM discosvinilo WHERE true";

    if (!empty($artist_filter)) {
        $count_vinyl_query .= " AND artista LIKE :artist";
    }

    if (!empty($genre_filter)) {
        $count_vinyl_query .= " AND genero_musical LIKE :genre";
    }

    if (!empty($record_status_filter)) {
        $count_vinyl_query .= " AND estado_disco = :status";
    }

    $full_statement = $library_pdo->prepare($count_vinyl_query);

    foreach ($parameters as $key => $value) {
        $full_statement->bindValue($key, $value);
    }

    $full_statement->execute();
    $all_filtered_records = $full_statement->fetchColumn();
    $total_pages = ceil($all_filtered_records / $limit);

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
            <h2>Filtrar y ordenar</h2>
            <div class="filters">
                <form method="GET" action="records.php">
                    <label for="artist">Artista</label>
                    <input type="text" id="artist" name="artist" value="<?= htmlentities($_GET['artist'] ?? '') ?>">

                    <label for="genre">Género musical</label>
                    <input type="text" id="genre" name="genre" value="<?= htmlentities($_GET['genre'] ?? '') ?>">

                    <label for="status">Estado del disco</label>
                    <select id="status" name="status">
                        <option value="">Todos</option>
                        <option value="nuevo" <?= isset($_GET['status']) && $_GET['status'] === 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                        <option value="usado" <?= isset($_GET['status']) && $_GET['status'] === 'usado' ? 'selected' : '' ?>>Usado</option>
                        <option value="colección" <?= isset($_GET['status']) && $_GET['status'] === 'colección' ? 'selected' : '' ?>>Colección</option>
                    </select>
                    
                    <label for="order_by">Ordenar por</label>
                    <select id="order_by" name="order_by">
                        <option value="id" <?= isset($_GET['order_by']) && $_GET['order_by'] === 'id' ? 'selected' : '' ?>>ID</option>
                        <option value="precio" <?= isset($_GET['order_by']) && $_GET['order_by'] === 'precio' ? 'selected' : '' ?>>Precio</option>
                        <option value="lanzamiento" <?= isset($_GET['order_by']) && $_GET['order_by'] === 'lanzamiento' ? 'selected' : '' ?>>Año de lanzamiento</option>
                    </select>

                    <label for="order_dir">Orden tipo</label>
                    <select id="order_dir" name="order_dir">
                        <option value="asc" <?= isset($_GET['order_dir']) && $_GET['order_dir'] === 'asc' ? 'selected' : '' ?>>Ascendente</option>
                        <option value="desc" <?= isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'selected' : '' ?>>Descendente</option>
                    </select>

                    <button type="submit">Aplicar</button>
                    
                </form>        
            </div>

            <?php if (!empty($all_vinyls)): ?>
                <?php foreach ($all_vinyls as $vinyl_record): ?>
                    <div class="vinyl">
                        <h2>
                            <a href="post.php?id=<?= urlencode($vinyl_record['id']) ?>">
                                <?= htmlentities($vinyl_record['titulo_album']) ?>
                            </a>
                        </h2>
                        <p><strong>Artista:</strong> <?= htmlentities($vinyl_record['artista']) ?></p>
                        <p><strong>Género:</strong> <?= htmlentities($vinyl_record['genero_musical']) ?></p>
                        <p><strong>Estado:</strong> <?= htmlentities($vinyl_record['estado_disco']) ?></p>
                        <p><strong>Precio:</strong> €<?= htmlentities($vinyl_record['precio']) ?></p>
                        <img src="<?= htmlentities($vinyl_record['imagen_portada']) ?>" alt="Portada del disco">
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron resultados.</p>
                <?php endif; ?>
        </div>
        <div class="pagination">
   
            <?php if ($total_pages > 1): ?>
                <ul>
                    <?php 
                    for ($i = 1; $i <= $total_pages; $i++): 
                        $url = "?page=$i";
                        $url .= "&artist=" . urlencode($artist_filter);
                        $url .= "&genre=" . urlencode($genre_filter);
                        $url .= "&status=" . urlencode($record_status_filter);
                        $url .= "&order_by=" . urlencode($order_by);
                        $url .= "&order_dir=" . urlencode($order_dir);
                    ?>
                    <li>
                        <a href="<?= $url ?>" <?= $i === $page ? 'style="font-weight: bold;"' : '' ?>>
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            <?php endif; ?>
        </div>
    </body>
</html>

