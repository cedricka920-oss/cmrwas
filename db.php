<?php
// ============================================================
// db.php - Universal Database Connection
// Supports:
// 1. Neon Serverless PostgreSQL (via DATABASE_URL or POSTGRES_URL)
// 2. Cloud MySQL (Render / TiDB / Aiven via DB_HOST)
// 3. Local XAMPP MySQL (Default localhost)
// ============================================================

$database_url = getenv('DATABASE_URL') ?: getenv('POSTGRES_URL') ?: '';

$db_driver = 'mysql';

if (!empty($database_url)) {
    // Parse Neon / PostgreSQL Connection URL
    // e.g.: postgresql://user:password@ep-xyz.aws.neon.tech/neondb?sslmode=require
    $db_parts = parse_url($database_url);
    $db_driver = 'pgsql';
    $host = $db_parts['host'] ?? 'localhost';
    $port = $db_parts['port'] ?? 5432;
    $username = isset($db_parts['user']) ? urldecode($db_parts['user']) : '';
    $password = isset($db_parts['pass']) ? urldecode($db_parts['pass']) : '';
    $dbname = isset($db_parts['path']) ? ltrim($db_parts['path'], '/') : 'neondb';

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
} elseif (getenv('DB_DRIVER') === 'pgsql') {
    // Individual PostgreSQL / Neon Environment Variables
    $db_driver = 'pgsql';
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $dbname = getenv('DB_NAME') ?: 'neondb';
    $username = getenv('DB_USER') ?: 'postgres';
    $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
} else {
    // MySQL (Default for Local XAMPP)
    $db_driver = 'mysql';
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $dbname = getenv('DB_NAME') ?: 'cmrwas_db';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    if (getenv('DB_SSL') === 'true') {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
}

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}
