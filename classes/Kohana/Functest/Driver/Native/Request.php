<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Native Driver request. Uses Native Kohana Requests with a little patching to make them work with tests
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Functest_Driver_Native_Request extends Request {

	function __construct($method, $uri, $query = array(), $post = array(), $client_params = array(), $allow_external = TRUE, $injected_routes = array()) 
	{
		parent::__construct($uri, $client_params, $allow_external, $injected_routes);

		$this->method($method);
		$_GET = $query;
		$_POST = $query;
		$this->query($query);
		$this->post($post);

		Request::$initial = $this;
	}

	static public $redirects_count = 0;
	
	public function execute() 
	{
		$response = parent::execute();
		
		if ($response->status() >= 300 AND $response->status() < 400)
		{
			Functest_Driver_Native_Request::$redirects_count += 1;

			if (Functest_Driver_Native_Request::$redirects_count >= 5)
				throw new Kohana_Exception("Maximum Number of redirects (5) for url :url", array(':url' => $this->uri()));

			$query = parse_url($response->headers('location'), PHP_URL_QUERY);
			parse_str($query, $query);

			$redirected_request = new Functest_Driver_Native_Request(Request::GET, parse_url($response->headers('location'), PHP_URL_PATH), $query);
			$redirected_request->referrer($this->url());
			
			Request::$initial = $redirected_request;
			
			$response = $redirected_request->execute();
			
			Functest_Driver_Native_Request::$redirects_count = 0;
		}

		return $response;
	}
}
