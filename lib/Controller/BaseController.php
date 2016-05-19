<?php

namespace MVC\Controller;

class BaseController
{
	private $default_cookie_name;
	private $view_directory;

	public $Model = null;
	public $ViewBag;
	public $MetaTag;
	
	public function __construct( $view_dir, $meta_tag, $view_bag, $cookie_name )
	{
		if(!defined("__APP_RUNNING_MODE__"))
			define("__APP_RUNNING_MODE__", 1);

		$this->default_cookie_name = $cookie_name;
		$this->view_directory =  $view_dir;

		$this->ViewBag =  $view_bag;
		$this->MetaTag =  $meta_tag;
	}
	
	final protected function getFriendlyMessage($message, $default = "unknown failure")
	{
		if(preg_match("/Couldn't find[.]*/i", $message))
			return "id not found";
		else if(preg_match("/Duplicate entry[.]*/i", $message))
			return "duplicate entry/key";
		else
			return $default;
	}

	final protected function get_view_directory()
	{
		return $this->view_directory;
	}
	
	final protected function get_called_class()
	{
		return array_pop(explode("\\", get_class($this)));
	}
	
	public function get_cookie()
	{
		return 
			\HTTP\Request::get_cookie($this->default_cookie_name);
	}

	public function set_cookie(array $arrayValue, $validate = "", $path = "", $domain = "")
	{
		\HTTP\Response::set_cookie($this->default_cookie_name, $arrayValue, $validate, $path, $domain);
	}

	public function url_context( $url, $suppress_protocol = false )
	{
		if( strpos($url, "~/") === false )
			return $url;
		if($suppress_protocol)
			return substr_replace($url, str_ireplace(array("http:", "https:"), "", __APP_URL__) , 0, 2);
		return substr_replace($url, __APP_URL__, 0, 2);
	}
	
	public function include_view( $view_file )
	{
		if(!file_exists($this->get_view_directory() . strtolower($this->get_called_class() . "/" . $view_file) . ".php"))
		{
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCControllerException("View file: $view_file not found.");
			else
				die("Error# View file: $view_file not found.");
		} else {
			require_once( $this->get_view_directory() . strtolower($this->get_called_class() . "/" . $view_file) . ".php" );
		}
	}

	public function include_shared_view( $view_file )
	{
		if(!file_exists($this->get_view_directory() . "shared/" . strtolower($view_file) . ".php"))
		{
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCControllerException("View shared file: $view_file not found.");
			else
				die("Error# View shared file: $view_file not found.");
		} else {	
			require_once( $this->get_view_directory() . "shared/" . strtolower($view_file) . ".php" );
		}
	}

	public function render($model = null, $view_file = null)
	{
		$this->Model = $model;

		$view_path = $this->view_directory . strtolower($this->get_called_class()). "/";
		
		if($view_file == null || empty($view_file))
		{
			$_caller = next(debug_backtrace());
			$view_file = $_caller['function'];
		}

		if(!file_exists($view_path . strtolower($view_file) . ".php"))
		{
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCControllerException("View file: $view_file not found.");
			else
				die("Error# View file: $view_file not found.");
		} else {
			require_once($view_path . strtolower($view_file) . ".php");
		}
	}
}

?>