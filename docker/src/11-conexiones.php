<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

$resultado = [
    'postgresql' => ['estado' => 'error', 'base_datos' => null],
    'mariadb' => ['estado' => 'error', 'base_datos' => null],
];
$hayErrores = false;

try {
    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', getenv('PGHOST'), getenv('PGPORT'), getenv('PGDATABASE'));
    $db = new PDO($dsn, (string) getenv('PGUSER'), (string) getenv('PGPASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $resultado['postgresql'] = [
        'estado' => 'ok',
        'base_datos' => $db->query('SELECT current_database()')->fetchColumn()
    ];
} catch (PDOException $error) {
    $hayErrores = true;
    error_log('Error PostgreSQL. Código PDO: ' . $error->getCode());
}

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        getenv('MARIADB_HOST'),
        getenv('MARIADB_PORT'),
        getenv('MARIADB_DATABASE')
    );
    $db = new PDO($dsn, (string) getenv('MARIADB_USER'), (string) getenv('MARIADB_PASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $resultado['mariadb'] = [
        'estado' => 'ok',
        'base_datos' => $db->query('SELECT DATABASE()')->fetchColumn()
    ];
} catch (PDOException $error) {
    $hayErrores = true;
    error_log('Error MariaDB. Código PDO: ' . $error->getCode());
}

http_response_code($hayErrores ? 503 : 200);
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
