<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_FuncTest_Driver_Phantomjs_Connection{
	
	protected $_phantomjs;
	protected $_pipes;
	protected $_url;

	public function __construct()
	{
		$this->_url = Kohana::$config->load('functest.drivers.phantomjs.url');
		// $this->_phantomjs = proc_open(Kohana::$config->load('functest.drivers.phantomjs.command'), array(array("pipe","r")), $this->_pipes, DOCROOT);
		// usleep(300000);
	}

	public function connected()
	{
		return (bool) $this->_phantomjs;
	}

	public function close()
	{
		//$this->delete('session', array());
		//proc_close($this->_phantomjs);
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
			throw new Kohana_Exception('Curl ":command" throws exception :error', array(':command' => $command, ':error
				' => $error));

		if ($code != 200)
			throw new Kohana_Exception('Unexpected response from the panthomjs for :command: :code', array(':command' => $command, ':code' => $code));

		return $result;
	}
}
