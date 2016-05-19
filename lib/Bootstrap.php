<?php

namespace MVC;

class Bootstrap
{
	private $controller_directory = "./controller/";
	private $view_directory = "./view/";
	private $default_cookie_name = "app_default_cookie";
	private $route_table;
	private $root_path;
	
	public $view_bag;
	public $meta_tag;

	public function __construct( $root = null, $route_table = null, $controller_dir = null, $view_dir = null )
	{
		if(!defined("__APP_RUNNING_MODE__"))
			define("__APP_RUNNING_MODE__", 1);

		if($root == null || gettype($root) != "string")
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCBootstrapException("Root value is a mandatory parameter.");
			else
				die("Error# Root value is a mandatory parameter.");

		if($route_table == null || gettype($route_table) != "object")
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCBootstrapException("RouteTable instance is a mandatory parameter.");
			else
				die("Error# RouteTable instance is a mandatory parameter.");

		$this->root_path = $root;
		
		$this->route_table = $route_table;
		
		$this->view_bag = new ViewBag();
		$this->meta_tag = new MetaTag();
		
		if($controller_dir != null)
			$this->controller_directory = $controller_dir;

		if($view_dir != null)
			$this->view_directory =  $view_dir;
	}

	public function set_default_cookie_name($name)
	{
		$this->default_cookie_name = $name;
	}
	
	public function set_cache($maxAge = 60)
	{
		//Set up no cache
		header("Expires: Mon, 06 Jan 1990 00:00:01 GMT");             // Date in the past
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
		header("Cache-Control: no-cache, max-age=$maxAge, must-revalidate");
		header("Pragma: no-cache");
	}

	public function run( $view_dir = null )
	{
		if($view_dir == null)
			$view_dir = $this->view_directory;

		$_qs = "";

		if(isset($_SERVER["PATH_INFO"]))
		{
			$_qs = ltrim($this->root_path=="/" ? $_SERVER["PATH_INFO"] : str_replace($this->root_path,"",$_SERVER["PATH_INFO"]),"/");
		} else {
			if(isset($_SERVER["REDIRECT_URL"]) && $_SERVER["REDIRECT_URL"]!="")
			{
				$_qs = ltrim($this->root_path=="/" ? $_SERVER["REDIRECT_URL"] : str_replace($this->root_path,"",$_SERVER["REDIRECT_URL"]),"/");
			}
			else if(isset($_GET["path_info"]))
			{
				$_qs = $_GET["path_info"];
			} 
		}

		$_route = $this->route_table->match_route($_qs);
		
		if($_route == null)	// nenhum route foi encontrado
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCBootstrapException("RouteTable cannot discover a route.");
			else
				die("Error# RouteTable cannot discover a route.");

		//------  route values
		$_values = $this->route_table->strip_values( $_route["values"] );

		//------ get valies
		if(\HTTP\Request::is_get())
			$_values[] = \HTTP\Request::get_get_object();
		//------ post values
		else if(\HTTP\Request::is_post())
			$_values[] = \HTTP\Request::get_post_object();
		//------ put values
		else if(\HTTP\Request::is_put())
			$_values[] = \HTTP\Request::get_put_object();

		//------ controller

		$_controller =  "MVC\\Controller\\".$_route["values"]["controller"];

		if(!file_exists($this->controller_directory . strtolower($_route["values"]["controller"]) . ".php"))
		{
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCBootstrapException("Controller file: " . strtolower($_route["values"]["controller"]) . " not found.");
			else
				die("Error# Controller file: " . strtolower($_route["values"]["controller"]) . " not found.");
		} else {
			require_once( $this->controller_directory . strtolower($_route["values"]["controller"]) . ".php" );
		}

		//------  action
		if(!class_exists($_controller))
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCBootstrapException("Controller instance: " . $_route["values"]["controller"] . " not found.");
			else
				die("Error# Controller instance: " . $_route["values"]["controller"] . " not found.");

		$_instance = new $_controller( $view_dir , $this->meta_tag, $this->view_bag, $this->default_cookie_name );

		if(method_exists($_instance, $_route["values"]["action"]))
		{
			call_user_func_array(array($_instance, $_route["values"]["action"]), $_values);
		} else {
			if(__APP_RUNNING_MODE__ == 1)
				throw new MVCBootstrapException("Action method: " . $_route["values"]["action"] . " not found.");
			else
				die("Error# Action method: " . $_route["values"]["action"] . " not found.");
		}

	}
}

?>