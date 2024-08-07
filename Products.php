<?php

require_once "db_connect.php";

class Products
{

	public static function getProductsByCategoryId($categoryId): array
	{
		global $db;
		return mysqli_fetch_all($db->query("SELECT * FROM `products` WHERE `categoryId` = $categoryId"), MYSQLI_ASSOC);
	}

	public static function getProductById($productId): bool|array|null
	{
		global $db;
		return $db->query("SELECT * FROM `products` WHERE `id` = $productId LIMIT 1")->fetch_assoc();
	}
}