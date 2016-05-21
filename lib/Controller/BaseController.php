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
		$dummy = explode("\\", get_class($this));
		return array_pop($dummy);
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
		$_app_url = \MVC\Config::instance()->get_app_url();
		
		if( strpos($url, "~/") === false )
			return $url;
		if($suppress_protocol)
			return substr_replace($url, str_ireplace(array("http:", "https:"), "", $_app_url) , 0, 2);
		return substr_replace($url, $_app_url, 0, 2);
	}
	
	public function include_view( $view_file )
	{
		if(!file_exists($this->get_view_directory() . strtolower($this->get_called_class() . "/" . $view_file) . ".php"))
		{
			throw new MVCControllerException("View file: $view_file not found.");
		} else {
			require_once( $this->get_view_directory() . strtolower($this->get_called_class() . "/" . $view_file) . ".php" );
		}
	}

	public function include_shared_view( $view_file )
	{
		if(!file_exists($this->get_view_directory() . "shared/" . strtolower($view_file) . ".php"))
		{
			throw new MVCControllerException("View shared file: $view_file not found.");
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
			$dummy = debug_backtrace();
			$_caller = next($dummy);
			$view_file = $_caller['function'];
		}

		if(!file_exists($view_path . strtolower($view_file) . ".php"))
		{
			throw new MVCControllerException("View file: $view_file not found.");
		} else {
			require_once($view_path . strtolower($view_file) . ".php");
		}
	}
}

?>