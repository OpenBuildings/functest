<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_Functest_Driver_Phantomjs_Connection{
	
	protected $_url;

	public function __construct()
	{
		$this->_url = Kohana::$config->load('functest.drivers.phantomjs.url');

		if (Kohana::$config->load('functest.drivers.phantomjs.autostart'))
		{
			if (is_file($this->pid_file()))
			{
				shell_exec('kill '.file_get_contents($this->pid_file()));
			}

			$pid = shell_exec(strtr('nohup :command > :log 2> :log & echo $!', array(
				':command' => Kohana::$config->load('functest.drivers.phantomjs.command'),
				':log' => APPPATH.'logs/phantomjs.log',
			)));

			file_put_contents($this->pid_file(), $pid);
			sleep(1);
		}

	}

	public function __destruct()
	{
		if (Kohana::$config->load('functest.drivers.phantomjs.autostart'))
		{
			$this->delete('session', array());
			unlink($this->pid_file());
		}
	}

	public function pid_file()
	{
		return Kohana::$config->load('functest.drivers.phantomjs.pid');
	}

	public function get($command)
	{
		return $this->call($command);
	}

	public function post($command, array $params)
	{
		$options = array();
		$options[CURLOPT_POST] = TRUE;
		$options[CURLOPT_POSTFIELDS] = http_build_query($params);

		return $this->call($command, $options);
	}

	public function delete($command)
	{
		$options = array();
		$options[CURLOPT_CUSTOMREQUEST] = Request::DELETE;
		
		return $this->call($command, $options);	
	}

	public function call($command, array $options = array())
	{
		$curl = curl_init();
		$options[CURLOPT_URL] = $this->_url.$command;
		$options[CURLOPT_RETURNTRANSFER] = TRUE;
		$options[CURLOPT_FOLLOWLOCATION] = TRUE;

		curl_setopt_array($curl, $options);

		$raw = trim(curl_exec($curl));

		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$result = json_decode($raw, TRUE);

		if ($error = curl_error($curl))
			throw new Kohana_Exception('Curl ":command" throws exception :error', array(':command' => $command, ':error' => $error));

		if ($code != 200)
			throw new Kohana_Exception('Unexpected response from the panthomjs for :command: :code', array(':command' => $command, ':code' => $code));

		return $result;
	}
}
