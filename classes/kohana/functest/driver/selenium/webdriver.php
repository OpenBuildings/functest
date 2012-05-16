<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Xpath extension for native driver
 *
 * @package    FuncTest
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Selenium_WebDriver
{
	protected $_url;
	protected $_session_id;
	protected $_curl;

	public function __construct()
	{
		$config = Kohana::$config->load('functest.drivers.selenium');
		$this->_url = $config['url'];
	
		// Reuse the last session, and delete inactive sessions
		$sessions = $this->get('sessions');
		foreach ($sessions as $session) 
		{
			$id = Arr::get($session, 'id');
			try
			{
				$session = $this->get("session/$id/window_handle");
				$this->_session_id = $id;	
				break;
			}
			catch (FuncTest_Exception_Webdriver $exception)
			{
				$this->delete("session/$id");
			}
		}

		// New Browser session
		if ( ! $this->_session_id)
		{
			$session = $this->post('session', array('desiredCapabilities' => $config['desired']));
			$this->_session_id = $session['webdriver.remote.sessionid'];
		}

		$this->_url .= "session/{$this->_session_id}/";

		//$this->post('timeouts/implicit_wait', array('ms' => $config['implicit_wait']));
	}

	public function session_id()
	{
		return (bool) $this->_session_id;
	}

	public function get($command)
	{
		return $this->call($command);
	}

	public function post($command, array $params)
	{
		$options = array();
		$options[CURLOPT_POST] = TRUE;
		$options[CURLOPT_POSTFIELDS] = json_encode($params);

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
		$options[CURLOPT_HTTPHEADER] = array(
			'Content-Type: application/json;charset=UTF-8',
			'Accept: application/json',
		);

		curl_setopt_array($curl, $options);
		
		$raw = trim(curl_exec($curl));

		$result = json_decode($raw, TRUE);


		if ($error = curl_error($curl))
			throw new Kohana_Exception('Curl ":command" throws exception :error', array(':command' => $command, ':error
				' => $error));

		if ($result['status'] != 0)
			throw new FuncTest_Exception_Webdriver($result['status']);
		

		return Arr::get($result, 'value');
	}
}