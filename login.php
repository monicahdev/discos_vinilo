<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Tienda de vinilos</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
	
        <?php
        // Iniciar sesión
        session_start();

        require 'db_config.php';

        // String vacío para mensaje en caso de error
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtenter datos del formulario del login
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username && $password) {
                try {
                    // Consulta para obtener el usuario por username
                    $statement = $library_pdo->prepare('SELECT * FROM users_pec3 WHERE username = :username');
                    $statement->bindValue(':username', $username, PDO::PARAM_STR);
                    $statement->execute();
                    $user = $statement->fetch(PDO::FETCH_ASSOC);

                    if ($user && password_verify($password, $user['password'])) {
                        // Credenciales válidas: guarda el usuario en la sesión
                        $_SESSION['username'] = $user['username'];

                        // Redirige a index.php
                        header('Location: index.php');
                        exit;
                    } else {
                        // Credenciales inválidas
                        $error = 'Usuario o contraseña incorrectos.';
                    }
                } catch (PDOException $e) {
                    $error = 'Error al conectar con la base de datos.';
                }
            } else {
                $error = 'Completa todos los campos.';
            }
        }
        ?>

        <h1>Tienda de vinilos</h1>
        <?php include 'menu.php'; ?>
        
        <div class="container">
            
            <?php if ($error): ?>
                <p id="error"><?= htmlentities($error) ?></p>
            <?php endif; ?>
            <h2>Iniciar sesión</h2>
            <form method="POST" class="vinyl">
                <label>Usuario
                    <input type="text" name="username" required>
                </label>
                <label>Contraseña
                    <input type="password" name="password" required>
                </label>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
</html>