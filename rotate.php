<?php
$hostgroups = require(__DIR__ . '/dbconf.php');
$sqls = [
    'DROP TABLE IF EXISTS mysql.general_log2;',
    'DROP TABLE IF EXISTS mysql.general_log_backup;',
    'CREATE TABLE mysql.general_log2 LIKE mysql.general_log;',
    'INSERT INTO mysql.general_log2 SELECT * FROM mysql.general_log LIMIT 1000;',
    'RENAME TABLE mysql.general_log TO mysql.general_log_backup, mysql.general_log2 TO mysql.general_log;',
    'DROP TABLE IF EXISTS mysql.slow_log2;',
    'DROP TABLE IF EXISTS mysql.slow_log_backup;',
    'CREATE TABLE mysql.slow_log2 LIKE mysql.slow_log;',
    'INSERT INTO mysql.slow_log2 SELECT * FROM mysql.slow_log LIMIT 1000;',
    'RENAME TABLE mysql.slow_log TO mysql.slow_log_backup, mysql.slow_log2 TO mysql.slow_log;',
];

foreach ($hostgroups as $hostgroup) {
    foreach ($hostgroup['hosts'] as $host) {
        $pdo = new PDO("mysql:host={$host['addr']};port={$host['port']}", $host['user'], $host['password']);
        foreach ($sqls as $sql) {
            $pdo->query($sql);
        }
    }
}

