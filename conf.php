<?php

define('DSN', 'mysql:host=mysql;dbname=db;charset=utf8mb4');
define('DB_USER', 'user');
define('DB_PASS', 'user');

function dbConnect()
{
  $db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
  $db['dbname'] = ltrim($db['path'], '/');
  $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
  $user = $db['user'];
  $password = $db['pass'];
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}
