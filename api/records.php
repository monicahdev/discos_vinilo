<?php
require '../db_config.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    $statement = $library_pdo->prepare("SELECT * FROM discosvinilo LIMIT :limit OFFSET :offset");
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();
    $all_vinyls = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['page' => $page, 'vinyls' => $all_vinyls]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener los discos ' . $e->getMessage()]);
    die;
}
?>