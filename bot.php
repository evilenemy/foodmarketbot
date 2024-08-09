<?php

require_once "Telegram.php";
require_once "User.php";
require_once "Texts.php";
require_once "Pages.php";
require_once "Categories.php";
require_once "Products.php";
require_once "Cart.php";
require_once "Orders.php";
require_once "Settings.php";

$telegram = new Telegram(Settings::API_TOKEN);
$data = $telegram->getData();
$message = $data['message'];
$text = $message['text'];
$chat_id = $telegram->ChatID();

$user = new User($chat_id);
$user_lang = $user->getLanguage();
$texts = new Texts($user_lang);
$categories = new Categories($user_lang);

$rootPath = Settings::ROOT_PATH;
$numbers = ['', '1️⃣', "2️⃣", "3️⃣", "4️⃣", "5️⃣", "6️⃣", "7️⃣", "8️⃣", "9️⃣"];
$ADMIN_CHAT_IDS = Settings::ADMIN_CHAT_IDS;

if (!empty($telegram->Callback_Query())) {
	$callback_data = $telegram->Callback_Data();
	$callback_message = $telegram->Callback_Message();

	$telegram->answerCallbackQuery(['callback_query_id' => $telegram->Callback_ID(), 'text' => ""]);

	if (str_contains($callback_data, 'language')) {
		$user->setLanguage(explode("_", $callback_data)[1]);
		$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
		$telegram->sendMessage(['chat_id' => $chat_id, 'text' => "*{$texts->getText('welcome_text')}*, [{$callback_message['chat']['first_name']}](tg://user?id={$callback_message['chat']['id']})\n\n{$texts->getText('welcome_info')}", 'parse_mode' => 'markdown']);
		showStart();
	} elseif (str_contains($callback_data, 'back')) {
		if (str_contains($callback_data, 'order')) {
			$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
			showProduct(explode("_", $callback_data)[2]);
		} elseif (str_contains($callback_data, 'cart')) {
			$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
			showCart();
		} else showMenu(true, false, true, $callback_message['message_id']);
	} elseif (str_contains($callback_data, 'categoryId')) showMenu(false, true, true, $callback_message['message_id'], str_replace("categoryId", "", $callback_data));
	elseif (str_contains($callback_data, "option")) {
		$arr = explode(",", $callback_data);
		$productId = str_replace("productId", "", $arr[0]);
		$option = str_replace("option", "", $arr[1]);
		chooseCount($callback_message['message_id'], $productId, $option);
	} elseif (str_contains($callback_data, 'productId')) {
		$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
		showProduct(str_replace("productId", "", $callback_data));
	} elseif ($callback_data === "orderCart") {
		$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
		orderCart();
	} elseif (str_contains($callback_data, 'order')) {
		$params = explode("_", $callback_data);
		addToCart($params[1], $params[2], $params[3], [$callback_message['message_id']]);
	} elseif ($callback_data === "clear_cart") {
		Cart::clearCart($chat_id);
		$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
		showCart();
	} elseif ($callback_data === "change_cart") {
		changeCart($callback_message['message_id']);
	} elseif (str_contains($callback_data, "delete")) {
		Cart::deleteCartItem(str_replace("delete", "", $callback_data));
		$telegram->deleteMessage(['chat_id' => $chat_id, 'message_id' => $callback_message['message_id']]);
		showCart();
	}
} elseif ($text === "/start") {
	chooseLanguage();
} elseif (str_contains($text, "/start")) {
	$referralUserId = explode(" ", $text)[1];
	$referralUser = new User($referralUserId);
	if ($referralUser->setReferral($chat_id)) {
		$texts = new Texts($referralUser->getLanguage());
		$user->setReferralStatus();
		$textToSend = str_replace("name", $message['from']['first_name'], $texts->getText('new_referral'));
		$telegram->sendMessage(['chat_id' => $referralUserId, 'text' => $textToSend, 'parse_mode' => 'markdown']);
	}
	chooseLanguage();
} else {
	switch ($user->getPage()) {
		case Pages::PAGE_MAIN:
			switch ($text) {
				case $texts->getText('change_lang'):
					$user->setLanguage($user_lang === 'uz' ? 'ru' : 'uz');
					showStart();
					break;
				case $texts->getText('referrals'):
					showReferrals();
					break;
				case $texts->getText('menu'):
					showMenu();
					break;
				case $texts->getText('cart'):
					showCart();
					break;
				default:
					unknownCommand();
					break;
			}
			break;
		case Pages::PAGE_REFERRALS:
			switch ($text) {
				case $texts->getText('back_btn'):
					showStart();
					break;
				default:
					unknownCommand();
					break;
			}
			break;
		case Pages::PAGE_SET_PHONE:
			$phone = $message['contact']['phone_number'];
			if ($phone) {
				if (str_starts_with($phone, "998")) {
					$user->setPhoneNumber("+" . $phone);
					$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('success_linked_phone')]);
				} else {
					$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('wrong_link_phone')]);
				}
				showStart();
			} else {
				$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('error_link_phone')]);
			}
	}
}

function showStart(): void
{
	global $user, $texts;
	$texts = new Texts($user->getLanguage());
	$user->setPage(Pages::PAGE_MAIN);
	sendTextWithKeyboard([$texts->getText('menu'), $texts->getText('cart'), $texts->getText('referrals'), $texts->getText('change_lang')], $texts->getText('main_page'), false, "markdown", true);
}

function showMenu($categories_page = true, $products_page = false, $edit = false, $message_id = 0, $categoryId = 1): void
{
	global $user_lang, $texts, $categories;
	if ($categories_page) {
		$allCategories = $categories->getAllCategories();
		sendTextWithInlineKeyboard($allCategories, $texts->getText('select_categories'), $user_lang, "categories", false, $edit, $message_id);
	} elseif ($products_page) {
		$allProducts = Products::getProductsByCategoryId($categoryId);
		sendTextWithInlineKeyboard($allProducts, $texts->getText('select_products'), 'name_' . $user_lang, 'products', true, true, $message_id);
	}
}

function showProduct($productId): void
{
	global $user_lang, $rootPath, $telegram, $chat_id, $texts;
	$product = Products::getProductById($productId);
	$text = "<a href='$rootPath{$product['photoUrl']}'> </a>" . $product['name_' . $user_lang] . "\n\n" . $product['info_' . $user_lang];
	$option = [];
	$options = json_decode($product['options'], true);
	for ($i = 0; $i < count($options); $i++) $option[] = [$telegram->buildInlineKeyboardButton($product['name_' . $user_lang] . " " . $options[$i]['name'] . " - " . number_format($options[$i]['price'], 0, "", " ") . " " . $texts->getText('sum'), '', "productId" . $product['id'] . ",option" . $i)];
	$option[] = [$telegram->buildInlineKeyboardButton($texts->getText('main_menu'), "", "back")];
	$keyb = $telegram->buildInlineKeyBoard($option);
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $text, 'parse_mode' => "HTML", 'reply_markup' => $keyb]);
}

function chooseCount($message_id, $productId, $productOption): void
{
	global $texts, $telegram, $numbers, $chat_id;
	$option = [];
	for ($i = 1; $i < 10; $i += 3) {
		$option[] = [$telegram->buildInlineKeyboardButton($numbers[$i], "", "order_" . $productId . "_" . $productOption . "_" . $i), $telegram->buildInlineKeyboardButton($numbers[$i + 1], "", "order_" . $productId . "_" . $productOption . "_" . ($i + 1)), $telegram->buildInlineKeyboardButton($numbers[$i + 2], "", "order_" . $productId . "_" . $productOption . "_" . ($i + 2))];
	}
	$option[] = [$telegram->buildInlineKeyboardButton($texts->getText('back_btn'), "", "back_order_" . $productId)];
	$telegram->editMessageText(['chat_id' => $chat_id, 'text' => $texts->getText('choose_count'), 'message_id' => $message_id, 'reply_markup' => $telegram->buildInlineKeyBoard($option)]);
}

function addToCart($productId, $optionId, $count, $message_ids): void
{
	global $telegram, $chat_id, $texts;
	$telegram->endpoint('deleteMessages', ['chat_id' => $chat_id, 'message_ids' => json_encode($message_ids)]);
	Cart::addToCart($chat_id, $productId, $optionId, $count);
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('added_to_cart')]);
	showStart();
}

function showReferrals(): void
{
	global $chat_id, $texts, $user;
	$user->setPage(Pages::PAGE_REFERRALS);
	$referrals = $user->getReferrals();
	$text = str_replace('link', "`https://t.me/foodmarketsimplebot?start=" . $chat_id . "`", $texts->getText('referrals_page'));
	$text = str_replace('referrals_count', count($referrals), $text);
	$text = str_replace('discount_referrals', count($referrals) * 2 . "%", $text);
	sendTextWithKeyboard([], $text, true);
}

function showCart()
{
	global $telegram, $chat_id, $texts, $user_lang, $user;
	$cart = Cart::getCartByChatId($chat_id);
	if (empty($cart)) {
		return $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('cart_empty')]);
	}
	$option = [
		[$telegram->buildInlineKeyboardButton($texts->getText('change_cart'), "", "change_cart"), $telegram->buildInlineKeyboardButton($texts->getText('clear_cart'), "", "clear_cart")],
		[$telegram->buildInlineKeyboardButton($texts->getText('order'), "", "orderCart")]
	];
	$text = "*{$texts->getText('cart_page')}:*\n\n";
	$allPrice = 0;
	foreach ($cart as $orderProduct) {
		$product = Products::getProductById($orderProduct['productID']);
		$optionProduct = json_decode($product['options'], true);
		$price = $optionProduct[$orderProduct['optionID']]['price'] * $orderProduct['count'];
		$formattedPrice = number_format($price, 0, "", " ");
		$text .= "{$product['name_'.$user_lang]} {$optionProduct[$orderProduct['optionID']]['name']} *x* {$orderProduct['count']} = *$formattedPrice {$texts->getText('sum')}*\n";
		$allPrice += $price;
	}
	$referralsCount = count($user->getReferrals());
	$formattedAllPrice = number_format($allPrice, 0, "", " ");
	if ($referralsCount > 0) {
		$referralDiscount = $referralsCount * 2;
		$discount = $allPrice - ($allPrice / 100 * $referralDiscount);
		$formattedDiscount = number_format($discount, 0, "", " ");
		$priceSection = "*Referral bonus:* $formattedAllPrice - $referralDiscount% = $formattedDiscount {$texts->getText('sum')}\n*{$texts->getText('all_price')}:* $formattedDiscount {$texts->getText('sum')}";
	} else $priceSection = "*{$texts->getText('all_price')}:* $formattedAllPrice {$texts->getText('sum')}";
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => "$text\n*{$texts->getText('delivery')}:* {$texts->getText('free')}\n\n$priceSection", 'reply_markup' => $telegram->buildInlineKeyBoard($option), 'parse_mode' => 'markdown']);
}

function changeCart($message_id): void
{
	global $telegram, $texts, $chat_id, $user_lang;
	$option = [];
	$cart = Cart::getCartByChatId($chat_id);
	foreach ($cart as $cartProduct) {
		$product = Products::getProductById($cartProduct['productID']);
		$options = json_decode($product['options'], true);
		$option[] = [$telegram->buildInlineKeyboardButton("{$product['name_'.$user_lang]} {$options[$cartProduct['optionID']]['name']} - {$cartProduct['count']} ❌", "", "delete" . $cartProduct['id'])];
	}
	$option[] = [$telegram->buildInlineKeyboardButton($texts->getText('back_btn'), "", "back_cart")];
	$telegram->editMessageText(['chat_id' => $chat_id, 'text' => $texts->getText('change_cart_page'), 'reply_markup' => $telegram->buildInlineKeyBoard($option), 'message_id' => $message_id]);
}

function orderCart(): void
{
	global $telegram, $chat_id, $user_lang, $user, $texts, $ADMIN_CHAT_IDS;
	if (getPhoneNumber()) {
		$data = [];
		$price = 0;
		$cart = Cart::getCartByChatId($chat_id);
		foreach ($cart as $cartProduct) {
			$product = Products::getProductById($cartProduct['productID']);
			$productOptions = json_decode($product['options'], true);
			$price += $productOptions[$cartProduct['optionID']]['price'];
			$data[] = $product['name_' . $user_lang] . " " . $productOptions[$cartProduct['optionID']]['name'] . " *x* " . $cartProduct['count'];
		}
		$referralsCount = count($user->getReferrals());
		if ($referralsCount > 0) {
			$price -= $price / 100 * ($referralsCount * 2);
		}
		$orderId = Orders::createOrder($chat_id, json_encode($data), $price);
		$order = Orders::getOrderById($orderId);
		$phone = $user->getPhoneNumber();
		$orderPrice = number_format($order['price'], 0, "", " ") . " ";
		$orderProducts = "\n - " . implode("\n - ", json_decode($order['products'], true));
		Cart::clearCart($chat_id);
		$text = $texts->getText('created_order');
		$text = str_replace("id", $orderId, $text);
		$text = str_replace("number", $phone, $text);
		$text = str_replace("price", $orderPrice . $texts->getText('sum'), $text);
		$text = str_replace("array", $orderProducts, $text);
		$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $text, 'parse_mode' => 'markdown']);
		$status = !$order['status'] ? "aktiv" : "yetkazilgan";
		$created = $order['created'];
		foreach ($ADMIN_CHAT_IDS as $admin_chat_id) {
			$text = "❗️ *Yangi buyurtma*\n\n*Buyurtma raqami:* $orderId\n*Bog'lanish uchun telefon:* $phone\n*Buyurtma narxi:* $orderPrice so'm\n*Buyurtma mahsulotlari:*$orderProducts\n\nBuyurtma holati: $status\n*Yaratilgan vaqt:* $created";
			$telegram->sendMessage(['chat_id' => $admin_chat_id, 'text' => $text, 'parse_mode' => 'markdown']);
		}
	}
}

function chooseLanguage(): void
{
	global $telegram, $chat_id, $message;
	$option = [
		[$telegram->buildInlineKeyboardButton("🇷🇺 Русский", "", "language_ru"), $telegram->buildInlineKeyboardButton("🇺🇿 O'zbekcha", "", "language_uz")]
	];
	$keyb = $telegram->buildInlineKeyBoard($option);
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => "🇷🇺 {$message['from']['first_name']}, выберите язык, чтобы продолжить.\n\n🇺🇿 {$message['from']['first_name']}, davom etish uchun tilni tanlang.", 'reply_markup' => $keyb]);
}

function unknownCommand(): void
{
	global $telegram, $chat_id, $texts;
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('unknown_command'), 'parse_mode' => 'markdown']);
}

function getPhoneNumber(): bool
{
	global $user, $telegram, $chat_id, $texts;
	if (empty($user->getPhoneNumber())) {
		$user->setPage(Pages::PAGE_SET_PHONE);
		$option = array([$telegram->buildKeyboardButton($texts->getText('send_phone'), true)]);
		$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $texts->getText('set_phone'), 'reply_markup' => $telegram->buildKeyBoard($option, true, true)]);
		return false;
	}
	return true;
}

function sendTextWithKeyboard($buttons, $text, $backBtn = false, $parseMode = "markdown", $onetime = false): void
{
	global $telegram, $chat_id, $texts;
	$option = [];
	for ($i = 0; count($buttons) % 2 === 0 ? $i < count($buttons) : $i < count($buttons) - 1; $i += 2) $option[] = [$telegram->buildKeyboardButton($buttons[$i]), $telegram->buildKeyboardButton($buttons[$i + 1])];
	if (count($buttons) % 2 === 1) $option[] = [$telegram->buildKeyboardButton(end($buttons))];
	elseif ($backBtn) $option[] = [$telegram->buildKeyboardButton($texts->getText('back_btn'))];
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $text, 'reply_markup' => $telegram->buildKeyBoard($option, $onetime, true), 'parse_mode' => $parseMode]);
}

function sendTextWithInlineKeyboard($buttons, $text, $name, $page, $backBtn = false, $edit = false, $message_id = 0): void
{
	global $telegram, $chat_id, $texts;
	$option = [];
	for ($i = 0; count($buttons) % 2 === 0 ? $i < count($buttons) : $i < count($buttons) - 1; $i += 2) $option[] = [$telegram->buildInlineKeyboardButton($buttons[$i][$name], "", ($page == "categories" ? "categoryId" : "productId") . $buttons[$i]['id']), $telegram->buildInlineKeyboardButton($buttons[$i + 1][$name], "", ($page == "categories" ? "categoryId" : "productId") . $buttons[$i + 1]['id'])];
	if (count($buttons) % 2 === 1) $option[] = [$telegram->buildInlineKeyboardButton(end($buttons)[$name], "", ($page == "categories" ? "categoryId" : "productId") . end($buttons)['id'])];
	if ($backBtn) $option[] = [$telegram->buildInlineKeyboardButton($texts->getText('back_btn'), "", "back")];
	if ($edit) {
		$telegram->editMessageText(['chat_id' => $chat_id, 'text' => $text, 'reply_markup' => $telegram->buildInlineKeyBoard($option), 'message_id' => $message_id]);
	} else {
		$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $text, 'reply_markup' => $telegram->buildInlineKeyBoard($option), 'parse_mode' => "markdown"]);
	}
}