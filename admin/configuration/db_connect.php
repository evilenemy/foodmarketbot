<?php

/*подключение к базе данных*/

$host = "localhost"; // в 90% случаев это менять не надо
$password = "lK2nU3zX8l";
$username = "x_u_13602_foodmarket";
$databasename = "x_u_13602_foodmarket";

global $db;

setlocale(LC_ALL, "ru_RU.UTF8");

$db = new mysqli($host, $username, $password, $databasename, 3306);
$db->set_charset('utf8mb4');

if ($db->connect_errno) {
	echo "Не удалось подключиться к MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	exit;
}