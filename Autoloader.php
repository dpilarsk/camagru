<?php

class Autoloader
{
	public static function register()
	{
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	public static function autoload($class)
	{
		require_once 'class/' . $class . '.php';
	}
}

?>