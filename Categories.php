<?php

require_once "db_connect.php";

class Categories
{
	private $lang;

	public function __construct($lang)
	{
		$this->lang = $lang;
	}

	function getAllCategories(): array
	{
		global $db;
		return mysqli_fetch_all($db->query("SELECT `$this->lang`, `id` FROM `categories`"), MYSQLI_ASSOC);
	}
}