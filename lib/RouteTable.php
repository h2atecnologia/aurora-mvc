<?php

namespace MVC;

class RouteTable
{
	private $_routes = array();

	public function __construct()
	{
		$this->_routes["default"] = 
			array(
				"template" => "{controller}/{action}/{id}",
				"default" => array(
					"controller" => "Home", 
					"action" => "Index"
				),
				"constraint" => array(
					"controller" => "[a-z][a-z0-9_]+", 
					"action" => "[a-z][a-z0-9_]+", 
					"id" => "[\d]*"
				)
			);
	}

	private function _defaults($route)
	{
		if(!isset($route["default"]))
			$route["default"] = array();
		if(!isset($route["constraint"]))
			$route["constraint"] = array();

		return $route;
	}

	public function set_default($route)
	{
		$this->_routes["default"] = $this->_defaults($route);
	}

	public function add_route($name, $route)
	{
		if(preg_match("/^default$/i", trim($name)))
		{
			throw new MVCRouteTableException("The default route already exists. Use set_default() method to change its value.");
		} else {
			$this->_routes[strtolower($name)] = $this->_defaults($route);
		}
	}

	public function remove_route($name)
	{
		if(preg_match("/^default$/i", trim($name)))
			throw new MVCRouteTableException("The default route cannot be removed.");
			
		unset($this->_routes[strtolower($name)]);
	}

	public function get_all()
	{
		return $this->_routes;
	}

	public function get_route($name)
	{
		if(isset($this->_routes[strtolower($name)]))
			return $this->_routes[strtolower($name)];
		else
			return null;
	}

	public function match_route($url)
	{
		$url = trim($url, "/");

		if(!empty($url))
		{
			$route = $this->_match($url, false);
	
			if($route != null)
				return $route;
		}
		
		return $this->_match($url, true);
	}

	public function strip_values($values)
	{
		$_v = array();
		foreach( $values as $k => $v )
		{
			if(preg_match("/^controller|action$/i", $k) != true)
			{
				$_v[] = $v;
			}
		}
		return $_v;
	}

	private function _match($url, $verify_default)
	{	
		foreach( $this->_routes as $name => $route)
		{
			if((!$verify_default && $name == "default") || ($verify_default && $name != "default"))
				continue;

			$p = explode("/", $route["template"]);

			$a = empty($url) ? array() : explode("/",  $url);

			if($verify_default && $name == "default")
			{
				for($i = 0; $i < count($p); $i++)
				{
					if( count($a) < count($p))
						array_push($a, "");

					if( empty($a[$i]) && preg_match("/{(.*)}/", $p[$i], $_out) )
					{
						if( isset($route["default"][$_out[1]]) )
							$a[$i] = $route["default"][$_out[1]];
					}
				}
				$url = implode("/", $a);
			}

			$r = array();

			for($i = 0; $i < count($p); $i++)
			{
				if( preg_match("/{(.*)}/", $p[$i], $_out) )
				{
					if( isset($route["constraint"][$_out[1]]) )
						array_push( $r, $route["constraint"][$_out[1]] );
					else
						array_push( $r, "[A-Za-z0-9_]+" );

				} else {
					array_push( $r, $p[$i] );
				}
			}
		
			$preg = "/^" . implode("\\/", $r) ."$/i";

			$_m = preg_match($preg, $url) != false;

			if($_m)
			{
				$route["values"] = $route["default"];

				for($i = 0; $i < count($p); $i++)
				{
					if( preg_match("/{(.*)}/", $p[$i], $_out) && !empty($a[$i]))
						$route["values"][$_out[1]] = $a[$i];
				}

				return $route;
			}
		
			if($verify_default && $name == "default")
				break;
		}
	
		return null;	// se não encontrado, retorna null !!
	}
}

?>