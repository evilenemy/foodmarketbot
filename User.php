<?php

require_once "db_connect.php";

class User
{
	private $chat_id;

	public function __construct($chat_id)
	{
		$this->chat_id = $chat_id;
		if ($this->isUserSet()) $this->makeUser();
	}

	function isUserSet(): bool
	{
		global $db;
		return empty($db->query("SELECT * FROM `users` WHERE users.chatID=$this->chat_id LIMIT 1")->fetch_assoc());
	}

	function makeUser(): void
	{
		global $db;
		$db->query("INSERT INTO `users` (`chatID`, `referrals`) VALUES ($this->chat_id, '[]')");
	}

	function setLanguage($lang): void
	{
		global $db;
		$db->query("UPDATE `users` SET `language` = '$lang' WHERE `chatID` = $this->chat_id");
	}

	function getLanguage()
	{
		global $db;
		return $db->query("SELECT `language` FROM `users` WHERE `chatID` = $this->chat_id")->fetch_assoc()['language'];
	}

	function setPage($page): void
	{
		global $db;
		$db->query("UPDATE `users` SET `page` = '$page' WHERE `chatID` = $this->chat_id");
	}

	function getPage()
	{
		global $db;
		return $db->query("SELECT `page` FROM `users` WHERE `chatID` = $this->chat_id LIMIT 1")->fetch_assoc()['page'];
	}

	function setReferral($chat_id): bool
	{
		global $db;
		$isReferral = $this->getReferralStatus($chat_id);
		$referrals = $this->getReferrals();
		if (!$isReferral && !in_array($chat_id, $referrals)) {
			$referrals[] = $chat_id;
			$referrals = json_encode($referrals);
			$db->query("UPDATE `users` SET `referrals` = '$referrals' WHERE `chatID` = $this->chat_id");
			return true;
		}
		return false;
	}

	function getReferrals()
	{
		global $db;
		$referrals = $db->query("SELECT `referrals` FROM `users` WHERE `chatID` = $this->chat_id LIMIT 1")->fetch_assoc()['referrals'];
		return json_decode(!empty($referrals) ? $referrals : []);
	}

	function setReferralStatus(): void
	{
		global $db;
		$db->query("UPDATE `users` SET `referral_status` = 1 WHERE `chatID` = $this->chat_id");
	}

	public function getReferralStatus($chat_id)
	{
		global $db;
		return $db->query("SELECT `referral_status` FROM `users` WHERE `chatID` = $chat_id LIMIT 1")->fetch_assoc()['referral_status'];
	}

	function setPhoneNumber($phone): void
	{
		global $db;
		$db->query("UPDATE `users` SET `phone_number` = '$phone' WHERE `chatID` = $this->chat_id");
	}

	function getPhoneNumber()
	{
		global $db;
		return $db->query("SELECT `phone_number` FROM `users` WHERE `chatID` = $this->chat_id")->fetch_assoc()['phone_number'];
	}
}