<?php

namespace MVC;
use Closure;

/**
 * Manages configuration options for MVC.
 *
 * <code>
 * MVC::initialize(function($cfg) {
 *   $cfg->set_plugin_home('plugins');
 * });
 * </code>
 *
 */
class Config extends Singleton
{
	/**
	 * Name of the connection to use by default.
	 *
	 * <code>
	 * MVC\Config::initialize(function($cfg) {
	 *   $cfg->set_plugin_path('/your/app/plugins');
	 *   $cfg->set_app_url('http://yourdomain.com/yourapp/');
	 * });
	 * </code>
	 *
	 * This is a singleton class so you can retrieve the {@link Singleton} instance by doing:
	 *
	 * <code>
	 * $config = MVC\Config::instance();
	 * </code>
	 */

	/**
	 * path for the auto_loading of plugin classes.
	 *
	 * @see mvc_autoload
	 * @var string
	 */
	private $plugin_path;

	private $app_url;

	public static function initialize(Closure $initializer)
	{
		$initializer(parent::instance());
	}

	/**
	 * Sets the path where plugins are located.
	 *
	 * @param string $dir path path containing your plugins
	 * @return void
	 */
	public function set_plugin_path($dir)
	{
		$this->plugin_path = $dir;
	}

	public function set_app_url($url)
	{
		$this->app_url = $url;
	}

	/**
	 * Returns the plugin path.
	 *
	 * @return string
	 * @throws ConfigException if specified path was not found
	 */
	public function get_plugin_path()
	{
		if ($this->plugin_path && !file_exists($this->plugin_path))
			throw new MVCConfigException('Invalid or non-existent path: '.$this->plugin_path);

		return $this->plugin_path;
	}

	public function get_app_url()
	{
		return $this->app_url;
	}
};
?>
