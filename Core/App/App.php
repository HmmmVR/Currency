<?php

namespace Core\App;

/**
 * Domain class
 * A central application manager.
 * - Get database
 * - Get config
 * - Execute events
 */
class App
{
	private static $config;

	public static function initialize(string $root)
	{
		self::$config = new Config($root);
	}

	public static function getDatabase()
	{
		return self::$config->getDatabase();
	}

	public static function isLocal()
	{
		return self::$config->getEnv() === Config::LOCAL;
	}

	public static function isStaging()
	{
		return self::$config->getEnv() === Config::STAGING;
	}

	public static function isProduction()
	{
		return self::$config->getEnv() === Config::PRODUCTION;
	}

	public static function isCli()
	{
		return php_sapi_name() == 'cli';
	}

	public static function getRoot()
	{
		return self::$config->getRoot();
	}

	public static function bootstrap()
	{
		if (!self::isCli())
		{
			// router
			return;
		}

		// cli
	}
}
