<?php

namespace MVC;

class MetaTag
{
	private $_data = array(
		"__TITLE__" => "",
		"__DESCRIPTION__" => "",
		"__KEYWORDS__" => "",
		"__AUTHOR__" => "",
		"__COMPANY__" => "",
		"__ROBOTS__" => ""
	);

	public function __construct()
	{
		if(!defined("__APP_RUNNING_MODE__"))
			define("__APP_RUNNING_MODE__", 1);
	}

	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->_data))
			return $this->_data[$key];

		if(__APP_RUNNING_MODE__ == 1)
			throw new MVCCollectionException("MetaTag key: $key not found.");
		else
			die("Error# MetaTag key: $key not found.");
	}
}

?>