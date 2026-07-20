<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/Database.php';

$dbPath = __DIR__ . '/mobile_money.db';
if (!file_exists($dbPath)) {
    echo "db_missing\n";
    exit(1);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
foreach ($tables as $row) {
    echo $row['name'] . PHP_EOL;
}

echo "---\n";
foreach ($pdo->query("SELECT COUNT(*) AS c FROM users") as $row) {
    echo 'users=' . $row['c'] . PHP_EOL;
}
foreach ($pdo->query("SELECT COUNT(*) AS c FROM clients") as $row) {
    echo 'clients=' . $row['c'] . PHP_EOL;
}
foreach ($pdo->query("SELECT COUNT(*) AS c FROM transactions") as $row) {
    echo 'transactions=' . $row['c'] . PHP_EOL;
}
