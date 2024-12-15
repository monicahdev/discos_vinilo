<?php
require '../db_config.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(['error' => 'Identificador inválido']);
    die;
}

try {
    $statement = $library_pdo->prepare("SELECT * FROM discosvinilo WHERE id = :id");
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $vinyl = $statement->fetch(PDO::FETCH_ASSOC);

    if ($vinyl) {
        echo json_encode($vinyl);
    } else {
        echo json_encode(['error' => 'Vinilo no encontrado']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener el vinilo: ' . $e->getMessage()]);
    die;
}
?>
