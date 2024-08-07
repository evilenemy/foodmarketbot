<?php

class Texts
{
	private $lang;

	public function __construct($lang)
	{
		$this->lang = $lang;
	}

	function getText($keyword)
	{
		global $db;
		return $db->query("SELECT `$this->lang` FROM `texts` WHERE `keyword` = '$keyword' LIMIT 1")->fetch_assoc()[$this->lang];
	}
}