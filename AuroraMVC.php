<?php
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 70000)
	die('AuroraMVC requires PHP 7.0 or higher');

define('AURORAMVC_VERSION_ID','1.0');

if (!defined('AURORAMVC_AUTOLOAD_PREPEND'))
	define('AURORAMVC_AUTOLOAD_PREPEND',true);

require __DIR__.'/lib/HTTP.php';
require __DIR__.'/lib/Exceptions.php';
require __DIR__.'/lib/ViewBag.php';
require __DIR__.'/lib/MetaTag.php';
require __DIR__.'/lib/RouteTable.php';
require __DIR__.'/lib/Bootstrap.php';
require __DIR__.'/lib/Controller/Exceptions.php';
require __DIR__.'/lib/Controller/BaseController.php';
require __DIR__.'/lib/View/Helper.php';

/*if (!defined('AURORAMVC_AUTOLOAD_DISABLE'))
	spl_autoload_register('mvc_autoload',false, AURORAMVC_AUTOLOAD_PREPEND);

function mvc_autoload($class_name)
{
	$path = ActiveRecord\Config::instance()->get_model_directory();
	$root = realpath(isset($path) ? $path : '.');

	if (($namespaces = ActiveRecord\get_namespaces($class_name)))
	{
		$class_name = array_pop($namespaces);
		$directories = array();

		foreach ($namespaces as $directory)
			$directories[] = $directory;

		$root .= DIRECTORY_SEPARATOR . implode($directories, DIRECTORY_SEPARATOR);
	}

	$file = "$root/$class_name.php";

	if (file_exists($file))
		require_once $file;
}*/
?>