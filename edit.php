<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edición usuarios - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <?php
            session_start();
            require 'db_config.php';

            // Verificar si el usuario está loggeado
            if (!isset($_SESSION['username'])) {
                header('Location: login.php');
                die();
            }
            // Para mostrar mensajes de error o éxito
            $message = ''; 
            $username = $_SESSION['username'];

            // Obtener datos actuales del usuario
            $statement = $library_pdo->prepare("SELECT * FROM users_pec3 WHERE username = :username");
            $statement->execute([':username' => $username]);
            $user = $statement->fetch();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $password = $_POST['password'];

                // Actualizar los datos del usuario actual
                $user_edition = "UPDATE users_pec3 SET nombre = :nombre, apellidos = :apellidos";
                $parameters = [':nombre' => $nombre, ':apellidos' => $apellidos, ':username' => $username];

                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $user_edition .= ", password = :password";
                    $parameters[':password'] = $hashed_password;
                }

                $user_edition .= " WHERE username = :username";
                $statement = $library_pdo->prepare($user_edition);

                try {
                    $statement->execute($parameters);
                    $message = '¡Perfil actualizado!';
                } catch (Exception $e) {
                    $message = 'Error al actualizar el perfil.';
                }
            }
        ?>
        
       <h1>Tienda de vinilos</h1>
       <?php include 'menu.php'; ?>
       <div class="container">
           <h2>Editar perfil</h2>
            <p><?= $message ?></p>
            <form method="POST" class="vinyl">
                <label>Nombre de usuario</label>
                <input type="text" value="<?= htmlentities($user['username']) ?>" disabled><br>

                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlentities($user['nombre']) ?>"><br>

                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?= htmlentities($user['apellidos']) ?>"><br>

                <label>Nueva contraseña (solo si quieres cambiarla)</label>
                <input type="password" name="password"><br>

                <button type="submit">Editar</button>
             </form>
        </div>
    </body>
</html>