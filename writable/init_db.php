<?php
$path = __DIR__ . '/mobile_money.db';
if (file_exists($path)) {
    unlink($path);
}

$db = new SQLite3($path);
$sql = file_get_contents(__DIR__ . '/../base.sql');
$sql = str_replace('sqlite3 mobile_money.db', '', $sql);
$statements = array_filter(array_map('trim', preg_split('/;\s*/', $sql)));
foreach ($statements as $statement) {
    if ($statement === '') {
        continue;
    }
    if (!$db->exec($statement)) {
        fwrite(STDERR, $db->lastErrorMsg() . PHP_EOL);
        exit(1);
    }
}

echo "DB_OK\n";
