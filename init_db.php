<?php
// ============================================================
// init_db.php - Universal Database Initializer for Neon / Cloud
// Detects whether database is PostgreSQL (Neon) or MySQL
// ============================================================

require_once __DIR__ . '/db.php';

$message = '';
$status = '';
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($driver === 'pgsql') {
            $sqlFile = __DIR__ . '/neon_schema.sql';
        } else {
            $sqlFile = __DIR__ . '/cmrwas_db.sql';
        }

        if (!file_exists($sqlFile)) {
            throw new Exception("Schema file " . basename($sqlFile) . " not found.");
        }

        $sql = file_get_contents($sqlFile);

        if ($driver === 'mysql') {
            // Remove CREATE DATABASE and USE statements for cloud MySQL
            $sql = preg_replace('/CREATE DATABASE[^;]+;/i', '', $sql);
            $sql = preg_replace('/USE[^;]+;/i', '', $sql);
        }

        $pdo->exec($sql);

        $dbType = ($driver === 'pgsql') ? 'Neon Serverless PostgreSQL' : 'MySQL';
        $message = "Success! {$dbType} database tables, views, and initial accounts were successfully initialized!";
        $status = "success";
    } catch (Exception $e) {
        $message = "Error initializing database: " . $e->getMessage();
        $status = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Initialize CMRWAS Database (Neon / Render)</title>
<style>
    body { font-family: Arial, sans-serif; background: #f3f4f6; color: #333; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
    .card { background: white; padding: 35px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 520px; width: 90%; text-align: center; }
    h1 { color: #23439d; margin-bottom: 10px; font-size: 24px; }
    .badge { display: inline-block; padding: 4px 10px; background: #e0e7ff; color: #3730a3; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 15px; }
    p { color: #666; font-size: 14px; line-height: 1.5; margin-bottom: 25px; }
    .btn { background: #2949b5; color: white; border: none; padding: 12px 24px; font-size: 15px; font-weight: bold; border-radius: 6px; cursor: pointer; }
    .btn:hover { background: #1e3a8a; }
    .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: left; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .btn-home { display: inline-block; margin-top: 15px; color: #2949b5; text-decoration: none; font-weight: bold; }
    .btn-home:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="card">
    <h1>CMRWAS Database Setup</h1>
    <div class="badge">Connected: <?= ($driver === 'pgsql') ? '🐘 Neon PostgreSQL' : '🐬 MySQL' ?></div>
    <p>Click the button below to initialize your database tables, views, and default admin/user accounts.</p>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $status ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <?php if ($status === 'success'): ?>
            <a href="index.php" class="btn-home">👉 Open Campus Maintenance System</a>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($status !== 'success'): ?>
        <form method="POST">
            <button type="submit" class="btn">🚀 Initialize Database Now</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
