<?php
const SERVER = 'mysql218.phy.lolipop.lan';
const DBNAME = 'LAA1517473-final';
const USER = 'LAA1517473';
const PASS = 'final';

$connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
$pdo = new PDO($connect, USER, PASS);
?>
