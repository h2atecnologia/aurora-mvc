<?php

namespace MVC;

class ViewBag
{
	private $_data = array();
	
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
			throw new MVCCollectionException("ViewBag key: $key not found.");
		else
			die("Error# ViewBag key: $key not found.");
	}
}

?>