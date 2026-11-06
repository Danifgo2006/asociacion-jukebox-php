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

$alias = '';
$email = '';
$errores = [];
$errorGeneral = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValido()) {
        http_response_code(403);
        $errorGeneral = 'Token CSRF no válido. Recarga el formulario.';
    } else {
        $aliasRaw = $_POST['alias'] ?? null;
        $emailRaw = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirm = $_POST['confirm_password'] ?? null;

        if (!is_string($aliasRaw)) {
            $errores['alias'] = 'El alias debe ser texto.';
        } else {
            $alias = trim($aliasRaw);
            $n = mb_strlen($alias, 'UTF-8');
            if ($n < 2 || $n > 30) $errores['alias'] = 'El alias debe tener entre 2 y 30 caracteres.';
        }

        if (!is_string($emailRaw)) {
            $errores['email'] = 'El correo debe ser texto.';
        } else {
            $email = strtolower(trim($emailRaw));
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) $errores['email'] = 'Correo no válido.';
        }

        if (!is_string($password)) {
            $errores['password'] = 'Contraseña no válida.';
        } else {
            $n = mb_strlen($password, 'UTF-8');
            if ($n < 12 || $n > 64) $errores['password'] = 'La contraseña debe tener entre 12 y 64 caracteres.';
            elseif (strlen($password) > 72) $errores['password'] = 'La contraseña supera 72 bytes.';
        }

        if (!is_string($confirm)) {
            $errores['confirm_password'] = 'Confirma la contraseña.';
        } elseif (is_string($password) && !hash_equals($password, $confirm)) {
            $errores['confirm_password'] = 'Las contraseñas no coinciden.';
        }

        if ($errores === []) {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                if (!is_string($hash)) throw new RuntimeException('No se pudo crear el hash.');

                actualizarUsuarios(function (array $users) use ($alias, $email, $hash): array {
                    foreach ($users as $user) {
                        if (isset($user['email']) && is_string($user['email']) &&
                            strtolower($user['email']) === $email) {
                            throw new DomainException('CORREO_DUPLICADO');
                        }
                    }
                    $maxId = 0;
                    foreach ($users as $user) {
                        if (isset($user['id']) && is_int($user['id'])) $maxId = max($maxId, $user['id']);
                    }
                    $newId = $maxId + 1;
                    $users[] = [
                        'id' => $newId,
                        'alias' => $alias,
                        'email' => $email,
                        'password_hash' => $hash
                    ];
                    return [$users, $newId];
                });

                header('Location: login.php?registered=1', true, 303);
                exit;
            } catch (DomainException $error) {
                $errores['email'] = 'Este correo ya está registrado.';
            } catch (Throwable $error) {
                error_log('Error en registro JSON: ' . get_class($error));
                $errorGeneral = 'No se pudo completar el registro.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Registro Jukebox</title><link rel="stylesheet" href="/assets/app.css?v=10"></head>
<body>
<h1>Crear cuenta</h1>
<p><a href="login.php">Ya tengo una cuenta</a></p>
<?php if ($errorGeneral !== null) { ?><p role="alert"><?= e($errorGeneral) ?></p><?php } ?>
<form method="post">
    <?= campoCsrf() ?>
    <p><label for="alias">Alias (2–30 caracteres)</label><br>
    <input id="alias" name="alias" maxlength="30" required value="<?= e($alias) ?>">
    <?php if (isset($errores['alias'])) { ?><br><strong><?= e($errores['alias']) ?></strong><?php } ?></p>

    <p><label for="email">Correo electrónico</label><br>
    <input id="email" name="email" type="email" required value="<?= e($email) ?>">
    <?php if (isset($errores['email'])) { ?><br><strong><?= e($errores['email']) ?></strong><?php } ?></p>

    <p><label for="password">Contraseña (12–64 caracteres)</label><br>
    <input id="password" name="password" type="password" minlength="12" maxlength="64" required>
    <?php if (isset($errores['password'])) { ?><br><strong><?= e($errores['password']) ?></strong><?php } ?></p>

    <p><label for="confirm_password">Repite la contraseña</label><br>
    <input id="confirm_password" name="confirm_password" type="password" minlength="12" maxlength="64" required>
    <?php if (isset($errores['confirm_password'])) { ?><br><strong><?= e($errores['confirm_password']) ?></strong><?php } ?></p>

    <button type="submit">Registrarme</button>
</form>
</body></html>
