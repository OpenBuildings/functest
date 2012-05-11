<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_WebTest_Driver_Phantomjs extends WebTest {
	
	protected $_phantomjs;
	protected $_pipes;

	protected $_within;

	public function initialize()
	{
		$this->_phantomjs = proc_open($this->config('phantomjs.command'), array(array("pipe","r")), $this->_pipes, DOCROOT);
		usleep(300000);
	}

	public function clear()
	{
		$this->action('close');
		proc_close($this->_phantomjs);
	}

	public function get($uri = '', array $query = NULL)
	{
		$this->action('open', NULL, URL::site($uri).($query ? URL::query($query) : ''));

		return $this;
	}

	public function post($uri, array $query = NULL, array $post = NULL, array $files = NULL)
	{
		throw new WebTest_NotImplemented_Exception("A browser cannot naturally make an isolated POST request.", array('driver' => 'selenium'));
	}

	public function delete($uri, array $query = NULL)
	{
		throw new WebTest_NotImplemented_Exception("A browser cannot naturally make an isolated DELETE request.", array('driver' => 'selenium'));
	}

	public function body()
	{
		return $this->action('getHtmlSource');
	}

	public function headers($header = NULL)
	{
		throw new WebTest_NotImplemented_Exception("Selenium does not provide direct access to the response headers", array('driver' => 'selenium'));
	}

	public function find($selector, $params = TRUE)
	{
		if ( ! ($found = $this->locate_insist($selector, TRUE)))
			return FALSE;

		if (is_string($params))
		{
			if ($this->action('getText', $found) == $params)
				return array($found);

			return FALSE;
		}

		$occurances = $this->get_CSS_count($selector);

		return array_fill(0, $occurances, $found);
	}

	public function js($code)
	{
		return $this->action('getEval', $code);
	}

	public function get_CSS_count($selector)
	{
		return (int) $this->js("+window.document.querySelectorAll('$selector').length;");
	}

	public function locator($selector)
	{
		if (preg_match("/^(\/\/|[a-z]+\=)/", $selector))
			return $selector;

		return $this->_locator.'='.($this->_within ? $this->_within.' ' : '').$selector;
	}

	public function form_locator($name, $filter = NULL)
	{
		if (is_string($filter))
		{
			$filter = " ".(preg_match("/^(\/\/|[a-z]+\=)/", $filter) ? $filter : 'value='.$filter);
		}
		else
		{
			$filter = "";
		}

		return $this->locator((preg_match("/^[a-z\-_0-9\[\]]+$/", $name) ? "name=" : "").$name.$filter);
	}

	public function locate($selector, $form = NULL)
	{
		$locator = $form ? $this->form_locator($selector, $form) : $this->locator($selector);
		$total_attempts = max(1, (int) WebTest::instance()->config('selector.attempts'));
		$attempts = 0;
		$sleep = WebTest::instance()->config('selector.delay');
		
		do
		{
			if ($attempts)
			{
				sleep(round($sleep / 1000));
			}

			if ($this->action('isElementPresent', $locator))
				return $locator;

			$attempts++;
		} while ($attempts <= $total_attempts);

		return FALSE;
	}		

	public function locate_insist($selector, $form = NULL)
	{
		if (($locator = $this->locate($selector, $form)) === FALSE)
		{
			$selector = $form ? $this->form_locator($selector, $form) : $this->locator($selector);
			throw new WebTest_Selector_Exception('Element with selector :selector is not present on the page!', array(':selector' => $selector, 'driver' => 'selenium'), 500, $selector);
		}
		
		return $locator;
	}

	public function wait_for_selector($selector)
	{
		return $this->locate_insist($selector);
	}

	public function click($selector)
	{
		$this->action("click", $this->locate_insist($selector));
		return $this;
	}

	public function within($selector)
	{
		$this->_within = $selector;
		return $this;
	}

	public function end()
	{
		$this->_within = NULL;
		return $this;
	}

	public function fill_in($name, $value)
	{
		$this->action('type', $this->locate_insist($name, TRUE), $value);
		return $this;
	}

	public function fill_in_keys($name, $value)
	{
		$input = $this->locate_insist($name, TRUE);
		$this->action('typeKeys', $input, $value);
		$this->action('type', $input, $value);
		return $this;
	}

	public function select($selector, $value)
	{
		$this->action('select', $this->locate_insist($selector, TRUE), $value);
	}

	public function check($selector, $filter = NULL)
	{
		$this->action('check', $this->locate_insist($selector, $filter));
		return $this;
	}

	public function uncheck($selector, $filter = NULL)
	{
		$this->action('uncheck', $this->locate_insist($selector, $filter));
		return $this;
	}

	public function choose($selector, $filter = NULL)
	{
		$this->check($selector, $filter);
		return $this;
	}

	public function attach_file($selector, $file)
	{
		$this->action('typeKeys', $this->locate_insist($selector, TRUE), $file);
		return $this;
	}

	public function value($selector)
	{
		return $this->action('getValue', $this->locate_insist($selector, TRUE));
	}

	public function title()
	{
		return $this->action('getTitle');
	}

	public function status()
	{
		throw new WebTest_NotImplemented_Exception("Selenium does not provide direct access to the response status code", array('driver' => 'selenium'));
	}

	public function uri()
	{
		return $this->action('getLocation');
	}

	public function action($command, $target = NULL, $value = NULL)
	{
		$this->initialize();

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, $this->config('phantomjs.timeout'));
		curl_setopt($curl, CURLOPT_URL, $this->config('phantomjs.url'));
		curl_setopt($curl, CURLOPT_POST, TRUE);

		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('action' => $command, 'target' => $target, 'value' => $value)));
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if ( ! $response)
			throw new WebTest_Exception('Curl Error :error ', array(':error' => curl_error($curl), 'driver' => 'phantomjs'));

		if ($code === 200)
		{
			return $response;
		}
		else
		{
			throw new WebTest_Exception("Unexpected response from the panthomjs: :response", array(':response' => $response, 'driver' => 'phantomjs'));
		}
	}
}
