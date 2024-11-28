<?php

$host = 'localhost'; 
$database_name = 'vinilos';
$user = 'root'; 
$password = '';

/*Conectar a librería PDO*/
try {
    $library_pdo = new PDO("mysql:host=$host;dbname=$database_name;charset=utf8mb4", $user, $password);
    $library_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>