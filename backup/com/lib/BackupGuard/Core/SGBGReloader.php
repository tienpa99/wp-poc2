<?php

class SGBGReloader
{
	private static $instance = null;
	protected $interval = 60; //seconds
	protected $lastReloadTs = 0;

	private function __construct()
	{

	}

	private function __clone()
	{

	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getInterval()
	{
		return $this->interval;
	}

	public function setInterval($interval)
	{
		$this->interval = $interval;
	}

	public function getLastReloadTs()
	{
		return $this->lastReloadTs;
	}

	public function setLastReloadTs($lastReloadTs)
	{
		$this->lastReloadTs = $lastReloadTs;
	}

	public function shouldReload()
	{
		return (time() - $this->getLastReloadTs() >= $this->getInterval());
	}

	protected function getCurrentUrl()
	{
		$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')?'https':'http';
		return ($scheme.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}


	private function doCurl ($url, $key, $action) {

		// Set post values
		$postfields = array(
			'k' => $key,
			'action' => $action,
		);

		// Call the API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		curl_exec($ch);
		curl_close($ch);

	}

    public function reload() {


		if (defined('BG_EXTERNAL_RESTORE_RUNNING') && BG_EXTERNAL_RESTORE_RUNNING) {

				$external = SGExternalRestore::getInstance()->getDestinationFileUrlArray();
				$action = "awake";
				$this->doCurl($external['url'].$external['restore_file'], $external['key'], $action);

		} else {

			$url = get_admin_url() . "admin-ajax.php?action=backup_guard_awake";
			$args = array(
				'timeout'     => 2,
				'sslverify'   => false,
				'headers' => [
					'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9'
				]
			);

			wp_remote_get($url, $args);

		}


        die;
    }
}
