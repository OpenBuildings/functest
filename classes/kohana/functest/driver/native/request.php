<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_FuncTest_Driver_Native_Request extends Request {

	function __construct($mthod, $uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array()) 
	{
		$request = parent::__construct($uri, $cache, $injected_routes);

		if ( ! $request)
			throw new Kohana_Exception("Route :uri not found", array(':uri' => $uri));

		$request->method($method);

		if ($query = parse_url($uri, PHP_URL_QUERY))
		{
			$request->query($query);
			$_GET = $query;
		}

		Request::$initial = $request;

		return $request;
	}

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

		$referrer = $this->uri();

		if (($response = $this->response()) === NULL)
		{
			$response = $this->create_response();
		}

		$response->status($code)
			->headers('Location', $url)
			->headers('Referer', $referrer);

		// Stop execution
		throw new FuncTest_Driver_Native_Redirect($response);
	}

	public function execute() 
	{
		try
		{
			$response = $this->execute();
		}
		catch (FuncTest_Driver_Native_Redirect $exception)
		{
			$response = $exception->getResponse();
		}
		catch (HTTP_Exception $exception)
		{
			$response = Response::factory()->body("HTTP Exception: \n".$exception->getMessage())->status($exception->getCode());
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
