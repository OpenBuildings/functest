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
class Kohana_FuncTest_Driver_Native extends FuncTest_Driver {

	public $name = 'native';

	protected $resuqest;
	protected $response;
	protected $dom;
	protected $xpath;
	protected $forms;

	function __construct()
	{
		$this->response = Response::factory();
		$this->dom = new DOMDocument();
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
		$this->dom->loadHTML($this->response->body());
		$this->xpath = new FuncTest_Driver_Native_XPath($this->dom);
		$this->forms = new FuncTest_Driver_Native_Forms($this->dom, $this->xpath);
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
		return $this->xpath()->find($xpath);
	}

	public function get($uri, array $query = NULL)
	{
		$this->request = new FuncTest_Driver_Native_Request(Request::GET, $uri.URL::query($query, FALSE));
		$this->response = $this->request->execute();
		$this->initialize();
		return $this;
	}

	public function post($uri, array $query = NULL, array $post = NULL, array $files = NULL)
	{
		$this->request = new FuncTest_Driver_Native_Request(Request::POST, $uri.URL::query($query, FALSE));

		if ($post)
		{
			$this->request->post($post);
			$_POST = $post;
		}

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
		elseif (in_array($node->tagName, array('input', 'button')) AND $node->getAttribute('type') == 'submit') 
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

	public function all($xpath)
	{
		return $this->xpath()->query($xpath);
	}
}
