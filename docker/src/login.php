<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/sesion.php';
require_once __DIR__ . '/lib/almacen-json.php';
iniciarSesionJukebox();

if (isset($_SESSION['usuario_id'])) {
    header('Location: home.php', true, 303);
    exit;
}
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
    header('Allow: GET, POST'); http_response_code(405); exit('Método no permitido.');
}

$email = '';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValido()) {
        http_response_code(403);
        $error = 'Token CSRF no válido. Recarga el formulario.';
    } else {
        $emailRaw = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!is_string($emailRaw) || !is_string($password)) {
            $error = 'Credenciales no válidas.';
        } else {
            $email = strtolower(trim($emailRaw));
            try {
                $users = leerUsuarios();
                $found = null;
                foreach ($users as $user) {
                    if (isset($user['email']) && is_string($user['email']) &&
                        strtolower($user['email']) === $email) {
                        $found = $user;
                        break;
                    }
                }

                if (is_array($found) &&
                    isset($found['password_hash'], $found['id'], $found['alias']) &&
                    is_string($found['password_hash']) &&
                    password_verify($password, $found['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = (int) $found['id'];
                    $_SESSION['alias'] = (string) $found['alias'];
                    $_SESSION['csrf'] = bin2hex(random_bytes(32));
                    header('Location: home.php', true, 303);
                    exit;
                }
                $error = 'Credenciales no válidas.';
            } catch (Throwable $exception) {
                error_log('Error en login JSON: ' . get_class($exception));
                $error = 'No se pudo comprobar la cuenta.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Iniciar sesión</title><link rel="stylesheet" href="assets/app.css?v=3"></head>
<body>
<h1>Entrar en Jukebox</h1>
<?php if (isset($_GET['registered'])) { ?><p>Registro completado. Ya puedes iniciar sesión.</p><?php } ?>
<?php if ($error !== null) { ?><p role="alert"><?= e($error) ?></p><?php } ?>
<form method="post">
    <?= campoCsrf() ?>
    <p><label for="email">Correo electrónico</label><br>
    <input id="email" name="email" type="email" required value="<?= e($email) ?>"></p>
    <p><label for="password">Contraseña</label><br>
    <input id="password" name="password" type="password" required></p>
    <button type="submit">Entrar</button>
</form>
<p><a href="register.php">Crear una cuenta</a></p>
</body></html>

