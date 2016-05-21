<?php
//if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 70000)
//	die('AuroraMVC requires PHP 7.0 or higher');
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50534)
	die('AuroraMVC requires PHP 5.5.34 or higher');

define('AURORAMVC_VERSION_ID','1.0');

if (!defined('AURORAMVC_AUTOLOAD_PREPEND'))
	define('AURORAMVC_AUTOLOAD_PREPEND',true);

require __DIR__.'/lib/Singleton.php';
require __DIR__.'/lib/Config.php';
require __DIR__.'/lib/Exceptions.php';
require __DIR__.'/lib/ViewBag.php';
require __DIR__.'/lib/MetaTag.php';
require __DIR__.'/lib/RouteTable.php';
require __DIR__.'/lib/Bootstrap.php';
require __DIR__.'/lib/Controller/Exceptions.php';
require __DIR__.'/lib/Controller/BaseController.php';
require __DIR__.'/lib/View/Helper.php';

if (!defined('AURORAMVC_AUTOLOAD_DISABLE'))
	spl_autoload_register('mvc_autoload',false, AURORAMVC_AUTOLOAD_PREPEND);

function mvc_autoload($class_name)
{
trigger_error($class_name, E_USER_NOTICE);
	$path = MVC\Config::instance()->get_plugin_path();
	$root = realpath(isset($path) ? $path : '.');

	if (($namespaces = get_namespaces($class_name)))
	{
		$class_name = array_pop($namespaces);
		//$directories = array();

		//foreach ($namespaces as $directory)
		//	$directories[] = $directory;

		//$root .= DIRECTORY_SEPARATOR . implode($directories, DIRECTORY_SEPARATOR);
	}

	$file = "$root/$class_name.php";

	if (file_exists($file))
		require_once $file;
}

function get_namespaces($class_name)
{
	if (has_namespace($class_name))
		return explode('\\', $class_name);
	return null;
}

function has_namespace($class_name)
{
	if (strpos($class_name, '\\') !== false)
		return true;
	return false;
}

?>