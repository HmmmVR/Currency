<?php

namespace Core\App;

use \Core\Database\Factory as DatabaseFactory;

class Config
{

	const UNKNOWN = 0;

	const LOCAL = 1;

	const STAGING = 2;

	const PRODUCTION = 3;

	private $root;

	private $env;

	private $database;

	private $connectivity;

	private $localIp;

	private $stagingIp;

	private $productionIp;

	public function __construct(string $root)
	{
		$this->root = "/" . trim($root, "/");
		$this->init(\file_get_contents($this->root . "/app.json"));
	}

	private function init(string $json)
	{
		$config = json_decode($json, true);

		$this->localIp = $config['local']['ip'];
		$this->stagingIp = $config['staging']['ip'];
		$this->productionIp = $config['production']['ip'];

		$databaseConfig = null;
		switch (getenv('SERVER_ADDR'))
		{
			case $this->localIp:
				$this->env = self::LOCAL;
				$config = $config['local'];
				break;

			case $this->stagingIp:
				$this->env = self::STAGING;
				$config = $config['staging'];
				break;

			case $this->productionIp:
				$this->env = self::PRODUCTION;
				$config = $config['production'];
				break;

			default:
				$this->env = self::LOCAL;
				$config = $config['local'];
				break;
		}

		$this->database = new DatabaseFactory($config['database']);
		$this->connectivity = $config['connectivity'];
	}

	public function getEnv()
	{
		return $this->env;
	}

	public function getRoot()
	{
		return $this->root;
	}

	public function getDatabase()
	{
		return $this->database->getConnection();
	}

	public function getConnectivity(string $name)
	{
		return $this->connectivity[$name];
	}

}
