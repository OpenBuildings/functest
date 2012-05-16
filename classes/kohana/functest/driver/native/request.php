<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Native Driver request. Uses Native Kohana Requests with a little patching to make them work with tests
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Native_Request extends Request {

	function __construct($method, $uri, HTTP_Cache $cache = NULL, $injected_routes = array()) 
	{
		parent::__construct($uri, $cache, $injected_routes);

		if ( ! $this->route())
			throw new Kohana_Exception("Route :uri not found", array(':uri' => $uri));

		$this->method($method);

		$this->_query = $_GET;
		$this->_post = $_POST;

		Request::$initial = $this;
	}

	static public $redirects_count = 0;

	public function redirect($url = '', $code = 302)
	{
		$url_parts = parse_url($url);
		$url = isset($url_parts['path']) ? ltrim($url_parts['path'], '/') : '/';

		if (isset($url_parts['query']))
		{
			$url .= '?'.$url_parts['query'];
		}

		if (isset($url_parts['fragment']))
		{
			$url .= '#'.$url_parts['fragment'];
		}

		// Stop execution
		throw new FuncTest_Driver_Native_Redirect($url);
	}

	public function execute() 
	{
		try
		{
			$response = parent::execute();
			FuncTest_Driver_Native_Request::$redirects_count = 0;
		}
		catch (FuncTest_Driver_Native_Redirect $exception)
		{
			FuncTest_Driver_Native_Request::$redirects_count += 1;

			if (FuncTest_Driver_Native_Request::$redirects_count >= 5)
				throw new Kohana_Exception("Maximum Number of redirects (5) for url :url", array(':url' => $this->uri()));

			$query = parse_url($exception->url(), PHP_URL_QUERY);
			parse_str($query, $query);
			$_GET = $query;

			$redirected_request = new FuncTest_Driver_Native_Request(Request::GET, $exception->url());
			$redirected_request->referrer($this->url());

			Request::$initial = $redirected_request;

			$response = $redirected_request->execute();
		}
		catch (HTTP_Exception $exception)
		{
			$action = $exception->getCode() ? $exception->getCode() : 500;
			$response = Request::factory(Route::get('error_handler')
				->uri(array(
					'controller' => 'errors',
					'action' => $action,
					'message' => base64_encode($exception->getMessage()))
				))
				->execute();
		}
		catch (Kohana_Exception $exception)
		{
			$code = $exception->getCode();

			if (isset(Kohana_Exception::$php_errors[$code]))
			{
				// Use the human-readable error name
				$code = Kohana_Exception::$php_errors[$code];
			}

			$response = Response::factory()->body(Kohana_Exception::text($exception)."\n--\n".$exception->getTraceAsString())->status(500);
		}

		return $response;
	}
}
