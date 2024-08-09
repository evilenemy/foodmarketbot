<?php
require_once "db_connect.php";

function getAllProducts(): array
{
	global $db;
	return mysqli_fetch_all($db->query("SELECT `products`.`id`, `products`.`name_uz`, `categories`.`uz` AS `category` FROM `products` INNER JOIN `categories` ON `products`.`categoryId` = `categories`.`id`"), MYSQLI_ASSOC);
}

function getProductInfo($id): bool|array|null
{
	global $db;
	return $db->query("SELECT * FROM `products` WHERE `id` = $id LIMIT 1")->fetch_assoc();
}

function createProduct($name_uz, $name_ru, $info_uz, $info_ru, $category_id, $options, $photoUrl): void
{
	global $db;
	$db->query("INSERT INTO `products` (`name_uz`, `name_ru`, `info_uz`, `info_ru`, `categoryId`, `options`, `photoUrl`) VALUES ('$name_uz', '$name_ru', '$info_uz', '$info_ru', $category_id, '$options', '$photoUrl')");
}

function editProduct($id, $name_uz, $name_ru, $info_uz, $info_ru, $category_id, $options, $photoUrl = false): void
{
	global $db;
	$options = $db->real_escape_string($options);
	$db->query("UPDATE `products` SET `name_uz` = '$name_uz', `name_ru` = '$name_ru', `info_uz` = '$info_uz', `info_ru` = '$info_ru', `categoryId` = $category_id, `options` = '$options' WHERE `id` = $id");
	if ($photoUrl !== false) {
		deleteImage($id);
		$imagePath = $db->real_escape_string(uploadImage($photoUrl));
		$db->query("UPDATE `products` SET `photoUrl` = '$imagePath' WHERE `id`=$id");

	}
}

function deleteProduct($id): void
{
	global $db;
	$db->query("DELETE FROM `products` WHERE `id` = $id");
}

function getAllCategories(): array
{
	global $db;
	return mysqli_fetch_all($db->query("SELECT * FROM `categories`"), MYSQLI_ASSOC);
}

function createCategory($name_uz, $name_ru): void
{
	global $db;
	$db->query("INSERT INTO `categories` (`uz`, `ru`) VALUES ('$name_uz', '$name_ru')");
}

function deleteCategory($id): void
{
	global $db;
	$db->query("DELETE FROM `products` WHERE `categoryId` = $id");
	$db->query("DELETE FROM `categories` WHERE `id` = $id");
}

function getAllOrders(): array
{
	global $db;
	return mysqli_fetch_all($db->query("SELECT * FROM `orders`"), MYSQLI_ASSOC);
}

function completeOrder($id): void
{
	global $db;
	$db->query("UPDATE `orders` SET `status` = 1 WHERE `id` = $id");
}

function getAllUsers(): array
{
	global $db;
	return mysqli_fetch_all($db->query("SELECT * FROM `users`"), MYSQLI_ASSOC);
}

function uploadImage($file): string
{
	$imageCount = (int)(file_get_contents("image_counter.txt"));
	$fileName = ($imageCount + 1) . "." . explode(".", $file['name'])[1];
	if (move_uploaded_file($file['tmp_name'], "../../photos/$fileName")) {
		file_put_contents("image_counter.txt", $imageCount + 1);
		return "photos/$fileName";
	}
	return "photos/empty_image.png";
}

function deleteAllCookies($path = '/foodmarketbot/admin/pages', $domain = ''): void
{
	if (count($_COOKIE) > 0) {
		foreach ($_COOKIE as $name => $value) {
			setcookie($name, '', time() - 3600, $path, $domain);
			unset($_COOKIE[$name]);
		}
	}
}

function deleteImage($id): void
{
	global $db;
	$product = getProductInfo($id);
	if ($product['photoUrl'] !== "photos/empty_image.png") {
		unlink("../../" . $product['photoUrl']);
		$db->query("UPDATE `products` SET `photoUrl` = 'photos/empty_image.png' WHERE `id`=$id");
	}
}