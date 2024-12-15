<?php
    require 'db_config.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $message = 'Las contraseñas no coinciden.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insertar el usuario en la tabla users_pec3
            $new_user_insertion = "INSERT INTO users_pec3 (username, nombre, apellidos, password) VALUES (:username, :nombre, :apellidos, :password)";
            $statement = $library_pdo->prepare($new_user_insertion);

            try {
                $statement->execute([
                    ':username' => $username,
                    ':nombre' => $nombre,
                    ':apellidos' => $apellidos,
                    ':password' => $hashed_password
                ]);
                $message = '¡Registro exitoso!';
            } catch (Exception $e) {
                $message = 'El nombre de usuario ya existe.';
            }
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de usuario - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        
        <h1>Tienda de vinilos</h1>
        <?php include 'menu.php'; ?>
        <div class="container">
            <h2>Registro</h2>

            <p><?= $message ?></p>
            <form method="POST" class="vinyl">
                <label>Nombre de usuario</label>
                <input type="text" name="username" required><br>

                <label>Nombre</label>
                <input type="text" name="nombre" required><br>

                <label>Apellidos</label>
                <input type="text" name="apellidos" required><br>

                <label>Contraseña</label>
                <input type="password" name="password" required><br>

                <label>Confirmar contraseña</label>
                <input type="password" name="confirm_password" required><br>

                <button type="submit">Sign up</button>
            </form>
        </div>
    </body>
</html>