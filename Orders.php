<?php

require_once "db_connect.php";

class Orders
{
	public static function createOrder($chatId, $products, $price)
	{
		global $db;
		$db->query("INSERT INTO `orders` (`chatID`, `products`, `price`) VALUES ($chatId, '$products', $price)");
		return $db->query("SELECT LAST_INSERT_ID() AS `id`")->fetch_assoc()['id'];
	}

	public static function getOrderById($id): bool|array|null
	{
		global $db;
		return $db->query("SELECT * FROM `orders` WHERE `id` = $id LIMIT 1")->fetch_assoc();
	}
}