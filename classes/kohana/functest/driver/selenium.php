<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Selenium driver. 
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Selenium extends FuncTest_Driver {

	public $name = 'selenium';

	protected $_session_id;
	
	protected $_timeout;

	protected $_url;


	function __construct()
	{
		parent::__construct($name, $config);
		$this->_url = 'http://'.$this->config('selenium.host').':'.$this->config('selenium.port').'/selenium-server/driver/';
		$this->_timeout = $this->config('selenium.timeout');
	}

	public function clear()
	{
		// if ($this->_session_id !== NULL)
		// {
		// 	$this->execute('testComplete');
		// 	$this->_session_id = NULL;
		// }

	}

	public function content()
	{
		return $this->action('getHtmlSource');
	}

	public function initialize()
	{
		if ($this->_session_id !== NULL)
			throw new Kohana_Exception("Session already started", array('driver' => 'selenium'));

		$this->_session_id = $this->action('getNewBrowserSession', $this->config('browser.type'), $this->config('browser.start_url'), FALSE);
		
		$this->action('setTimeout', $this->config('selenium.timeout'));
	}

	public function forms()
	{
		return $this->forms;
	}

	public function xpath()
	{
		return $this->xpath;
	}

	public function dom($xpath)
	{
		return $xpath ? $this->xpath()->find($xpath) : $this->dom;
	}

	public function get($uri, array $query = NULL)
	{
		return $this->request(Request::GET, $uri, $query);
	}

	public function post($uri, array $query = NULL, array $post = NULL, array $files = NULL)
	{
		return $this->request(Request::POST, $uri, $query, $post, $files);
	}

	public function request($type, $uri, array $query = NULL, array $post = NULL, array $files = NULL)
	{
		$this->response = Response::factory();

		$url = $uri.URL::query($query, FALSE);
		$query = parse_url($url, PHP_URL_QUERY);
		parse_str($query, $query);

		$this->environment->update_environment(array('_GET' => $query, '_POST' => $post, '_FILES' => $files));

		$this->request = new FuncTest_Driver_Native_Request($type, $url);

		$this->response = $this->request->execute();
		$this->initialize();
		return $this;
	}


	/**
	 * GETTERS
	 */

	public function tag_name($xpath)
	{
		return $this->dom($xpath)->tagName;
	}

	public function attribute($xpath, $name)
	{
		$node = $this->dom($xpath);

		return $node->hasAttribute($name) ? $node->getAttribute($name) : NULL;
	}

	public function html($xpath)
	{
		if ( ! $xpath)
			return $this->dom->saveHTML();
		
		$node = $this->dom($xpath);

		return $node->ownerDocument->saveXml($node);
	}

	public function text($xpath)
	{
		return $this->dom($xpath)->textContent;	
	}

	public function value($xpath)
	{
		return $this->forms->get_value($xpath);
	}

	public function visible($xpath)
	{
		$node = $this->dom($xpath);

		$hidden_nodes = $this->xpath()->query("./ancestor-or-self::*[contains(@style, 'display:none') or contains(@style, 'display: none') or name()='script' or name()='head']", $node);
		return $hidden_nodes->length == 0;
	}

	public function set($xpath, $value)
	{
		$this->forms->set_value($xpath, $value);
	}

	public function select_option($xpath, $value)
	{
		$node = $this->forms->set_value($xpath, $value);
	}

	public function click($xpath)
	{
		$node = $this->dom($xpath);

		if ($node->hasAttribute('href'))
		{
			$this->get($node->getAttribute('href'));
		}
		elseif (($node->tagName == 'input' AND $node->getAttribute('type') == 'submit') OR $node->tagName == 'button') 
		{
			$form = $this->xpath->find('./ancestor::form', $node);

			$action = $form->hasAttribute('action') ? $form->getAttribute('action') : $this->request->uri();

			$post = $this->forms->serialize_form($form);

			if ($node->tagName == 'input' AND $node->hasAttribute('name'))
			{
				$post = $post.'&'.$node->getAttribute('name').'='.$node->getAttribute('value');
			}
			parse_str($post, $post);

			$this->post($action, NULL, $post);
		}
		else
		{
			throw new Kohana_Exception('The html tag :tag cannot be clicked', array(':tag' => $node->tagName));
		}
	}

	public function visit($uri, array $query = NULL)
	{
		return $this->get($uri, $query);
	}

	public function current_path()
	{
		return $this->request->uri();
	}

	public function current_url()
	{
		return URL::site($this->request->uri(), TRUE);
	}

	public function count($xpath)
	{
		return $this->xpath()->query($xpath)->length;
	}

}
