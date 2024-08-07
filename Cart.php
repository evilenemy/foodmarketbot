<?php

require_once "db_connect.php";

class Cart
{
	public static function addToCart($chatID, $productID, $optionID, $count): void
	{
		global $db;
		$res = $db->query("SELECT * FROM `cart` WHERE `chatID` = $chatID AND `productID` = $productID AND `optionID` = $optionID LIMIT 1")->fetch_assoc();
		if (empty($res)) {
			$db->query("INSERT INTO `cart` (`chatID`, `productID`, `optionID`, `count`) VALUES ($chatID, $productID, $optionID, $count)");
		} else {
			$count = $count + $res['count'];
			$db->query("UPDATE `cart` SET `count` = $count WHERE `id` = {$res['id']}");
		}
	}

	public static function getCartByChatId($chatId): array
	{
		global $db;
		return mysqli_fetch_all($db->query("SELECT * FROM `cart` WHERE `chatID` = $chatId"), MYSQLI_ASSOC);
	}

	public static function clearCart($chat_id): void
	{
		global $db;
		$db->query("DELETE FROM `cart` WHERE `chatID` = $chat_id");
	}

	public static function deleteCartItem($id): void
	{
		global $db;
		$db->query("DELETE FROM `cart` WHERE `id` = $id");
	}
}