<?php

namespace MVC;

class ViewBag
{
	private $_data = array();
	
	public function __construct()
	{
		//
	}

	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->_data))
			return $this->_data[$key];

		throw new MVCCollectionException("ViewBag key: $key not found.");
	}
}

?>