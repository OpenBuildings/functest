<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Native driver. 
 * In memory kohana request response classes. 
 * No Javascript
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Functest_Driver_Native extends Functest_Driver {

	public $name = 'native';

	protected $request;
	protected $response;
	protected $dom;
	protected $xpath;
	protected $forms;
	protected $environment;

	function __construct()
	{
		$this->response = Response::factory();
		$this->dom = new DOMDocument('1.0', 'utf-8');

		$this->environment = Functest_Environment::factory(Kohana::$config->load('functest.drivers.native.environment'));
	}

	public function clear()
	{
		$this->environment->restore();	
	}

	public function content($content = NULL)
	{
		if ($content !== NULL)
		{
			$this->response->body($content);
			$this->initialize();

			return $this;
		}
		return $this->response->body();
	}

	public function initialize()
	{
		@ $this->dom->loadHTML($this->content());
		$this->dom->encoding = 'utf-8';
		$this->xpath = new Functest_Driver_Native_Xpath($this->dom);
		$this->forms = new Functest_Driver_Native_Forms($this->dom, $this->xpath);
		$this->request = Request::$initial;
	}

	public function forms()
	{
		return $this->forms;
	}

	public function xpath()
	{
		return $this->xpath;
	}

	public function dom($id)
	{
		return $id ? $this->xpath()->find($id) : $this->dom;
	}

	public function get($uri, array $query = array())
	{
		return $this->request(Request::GET, $uri, $query);
	}

	public function post($uri, array $query = array(), array $post = array(), array $files = array())
	{
		return $this->request(Request::POST, $uri, $query, $post, $files);
	}

	public function request($method, $uri, array $query = array(), array $data = array(), array $files = array())
	{
		$this->response = Response::factory();

		$url = $uri.URL::query($query, FALSE);

		$query = parse_url($url, PHP_URL_QUERY);
		
		parse_str($query, $query);

		$this->environment->backup_and_set(array(
			'_GET' => $query, 
			'_POST' => $data, 
			'_FILES' => $files, 
			'_SESSION' => isset($_SESSION) ? $_SESSION : array(),
		));

		$old_url = $this->current_url();
		
		$this->request = new Functest_Driver_Native_Request($method, $url, $query, $data);
		
		$this->request->referrer($old_url);
		
		if ($method === Request::PUT)
		{
			$body = http_build_query($data);
			
			$this->request->body($body);
		}
		
		$this->response = $this->request->execute();
		
		$this->initialize();
		
		return $this;
	}
	
	public function response()
	{
		return $this->response;
	}

	/**
	 * GETTERS
	 */

	public function tag_name($id)
	{
		return $this->dom($id)->tagName;
	}

	public function attribute($id, $name)
	{
		$node = $this->dom($id);

		return $node->hasAttribute($name) ? $node->getAttribute($name) : NULL;
	}

	public function html($id)
	{
		if ( ! $id)
			return $this->dom->saveHTML();
		
		$node = $this->dom($id);

		return $node->ownerDocument->saveXml($node);
	}

	public function text($id)
	{
		$text = $this->dom($id)->textContent;
		$text = preg_replace('/([\t\n\r]|\s\s+| )/', ' ', $text);
		
		return trim($text);
	}

	public function value($id)
	{
		return $this->forms->get_value($id);
	}

	public function is_visible($id)
	{
		$node = $this->dom($id);

		$hidden_nodes = $this->xpath()->query("./ancestor-or-self::*[contains(@style, 'display:none') or contains(@style, 'display: none') or name()='script' or name()='head']", $node);
		return $hidden_nodes->length == 0;
	}

	public function is_selected($id)
	{
		return (bool) $this->dom($id)->getAttribute('selected');
	}

	public function is_checked($id)
	{
		return (bool) $this->dom($id)->getAttribute('checked');
	}

	public function set($id, $value)
	{
		$this->forms->set_value($id, $value);
	}

	public function select_option($id, $value)
	{
		$node = $this->forms->set_value($id, $value);
	}

	public function serialize_form($id)
	{
		return $this->forms->serialize_form($id);
	}

	public function click($id)
	{
		$node = $this->dom($id);

		if ($node->hasAttribute('href'))
		{
			$this->get($node->getAttribute('href'));
		}
		elseif (($node->tagName == 'input' AND $node->getAttribute('type') == 'submit') OR $node->tagName == 'button') 
		{
			$form = $this->xpath->find('./ancestor::form', $node);

			$action = $form->hasAttribute('action') ? $form->getAttribute('action') : $this->request->uri();

			$post = $this->forms->serialize_form($form);

			$files = $this->forms->serialize_files($form);

			if (in_array($node->tagName, array('button', 'input')) AND $node->hasAttribute('name'))
			{
				$post = $post.'&'.$node->getAttribute('name').'='.$node->getAttribute('value');
			}
			parse_str($post, $post);
			parse_str($files, $files);

			$this->post($action, array(), $post, $files);
		}
		else
		{
			throw new Kohana_Exception('The html tag :tag cannot be clicked', array(':tag' => $node->tagName));
		}
	}

	public function visit($uri, array $query = array())
	{
		return $this->get($uri, $query);
	}

	public function current_path()
	{
		return $this->request ? URL::site($this->request->uri()) : NULL;
	}

	public function current_url()
	{
		return URL::site($this->current_path(), TRUE);
	}

	public function all($xpath, $parent = NULL)
	{
		$xpath = $parent.$xpath;
		$ids = array();
		foreach ($this->xpath()->query($xpath) as $index => $elmenets) 
		{
			$ids[] = "($xpath)[".($index+1)."]";
		}
		return $ids;
	}

	public function is_page_active()
	{
		return (bool) $this->response;
	}

	public function user_agent()
	{
		return Request::$user_agent;
	}

	public function cookie($name, $value, $expires = 0)
	{
		$_COOKIE[$name] = $value;
	}

}
