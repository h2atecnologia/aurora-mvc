<?php

namespace MVC\View;

class Helper
{
	private static $_sections = array();
	
	// usar (antes de qq include view):
	//
	//	Helper::set_section("js",
	// <<<TTT
	// <script type="text/javascript">
	// 		$(document).ready(function () {
	// 		//
	// 		});
	// </script>
	// TTT
	// );
	//
	// ou:
	//
	// Helper:: set_section("js", "<script>alert(mensagem);</script>");

	public static function set_section($name, $content)
	{
		static::$_sections[$name] = $content;
	}

	// usar:
	//
	// Helper:: get_section("js");

	public static function get_section($name)
	{
		if(isset(static::$_sections[$name]))
			echo static::$_sections[$name];
	}

	public static function img_link( $img_url, $link_url, $classname = null)
	{
		echo "<a href=\"", $link_url;
		echo (empty($target) ? "": "\" target=\"{$target}");
		echo (empty($classname) ? "": "\" class=\"{$classname}");
		echo "\"><img src=\"", $img_url, "\" /></a>";
	}

	public static function label_link( $label_text, $link_url, $classname = null)
	{
		echo "<a href=\"", $link_url;
		echo (empty($target) ? "": "\" target=\"{$target}");
		echo (empty($classname) ? "": "\" class=\"{$classname}");
		echo "\">", $label_text, "</a>";
	}

	// recebe um array no formato:
	//
	// $menu_array = array();
	// $menu_array["Label1"] = array( "link_url" => "http://url1", "link_target" => "_blank" );
	// $menu_array["Label2"] = array( "link_url" => "/url2", "link_target" => null );
	//
	public static function list_link(array $itens, $tag = null, $classname = null)
	{
		foreach( $itens as $k => $v )
		{
			echo (empty($tag) ? "" : "<$tag>");
			echo "<a href=\"", $v["link_url"];
			echo (empty($v["link_target"]) ? "": "\" target=\"{$v['link_target']}");
			echo (empty($classname) ? "": "\" class=\"{$classname}");
			echo "\">", $k, "</a>";
			echo (empty($tag) ? "" : "</$tag>");
			echo "\n";
		}
	}
}

?>