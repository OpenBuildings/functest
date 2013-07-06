<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Basic driver you have to extend this class and implement its functions
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
abstract class Kohana_Functest_Driver {

	public $page = NULL;

	public $name = NULL;

	/**
	 * Clear session stuff / cookies created by the current page
	 * @return $this
	 */
	public function clear()
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return an array of HTML fragments that match a given XPath query
	 * @param  string $id 
	 * @return array        
	 */
	public function all($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return an the id of the html elemenet
	 * @throws Functest_Exception_Notfound If element not found
	 * @param  string $id 
	 * @return array        
	 */
	public function find($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the HTML content of the current page or set it manually
	 * @param  string $content 
	 * @return string          
	 */
	public function content($content = NULL)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the tag name of a HTML fragemnt, specified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id 
	 * @return string        
	 */
	public function tag_name($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the tag attribute of an HTML fragment, spesified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id 
	 * @param  string $name 
	 * @return string
	 */
	public function attribute($id, $name)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the HTML fragment, spesified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id 
	 * @return string        
	 */
	public function html($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the plain text of an HTML fragment, spesified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id
	 * @return string
	 */
	public function text($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Retrun the value of an HTML fragment of a form input, spesified by id 
	 * If multiple tags match - return the first one. 
	 * The value is specific for diferrent for each input type. 
	 * - input -> value
	 * - textarea -> content
	 * - checkbox -> checked
	 * - radios -> checked
	 * - select -> selected option
	 * 
	 * @param  string $id 
	 * @return string        
	 */
	public function value($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the visibility of an HTML fragment, spesified by id 
	 * If multiple tags match - return the first one.
	 * 
	 * @param  string $id 
	 * @return string        
	 */
	public function is_visible($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return if the option is selected or not, spesified by id 
	 * If multiple tags match - return the first one.
	 * 
	 * @param  string $id 
	 * @return string        
	 */
	public function is_selected($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return if the input (checkbox/radio) is checked or not, spesified by id 
	 * If multiple tags match - return the first one.
	 * 
	 * @param  string $id 
	 * @return string        
	 */
	public function is_checked($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}
	/**
	 * Set the value for a form input tag.
	 * If multiple tags match - use the first one.
	 * 
	 * @param string $id 
	 * @param mixed $value value
	 */
	public function set($id, $value)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Set an HTML option tag as selected or remove selection for a given XPath query.
	 * If multiple tags match - use the first one.
	 * 
	 * @param  string $id 
	 * @param  boolean $value
	 * @return $this
	 */
	public function select_option($id, $value)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * INitiate a click on a give XPath element.
	 * If multiple tags match - use the first one.
	 * You can click on anchor and submit buttons.
	 * 
	 * @param  string $id
	 * @return $this
	 */
	public function click($id)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Go to a specified url
	 * @param  string $uri   
	 * @param  array $query 
	 * @return $this        
	 */
	public function visit($uri, array $query = NULL)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Get the current url without domain
	 * @return string
	 */
	public function current_path()
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Get the current url with domain
	 * @return string
	 */
	public function current_url()
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Confirm or cancel for the next confirmation dialog
	 * @param  bool $confirm 
	 */
	public function confirm($confirm)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return The root node
	 * @return Functest_Node 
	 */
	public function is_page_active()
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Return all the javascript errors on the page
	 * @return Functest_Node 
	 */
	public function javascript_errors()
	{
		return array();
	}

	/**
	 * Return all the javascript console messages on the page
	 * @return Functest_Node 
	 */
	public function javascript_messages()
	{
		return array();
	}

	/**
	 * Return The root node
	 * @return Functest_Node 
	 */
	public function page()
	{
		if ( ! $this->page)
		{
			$this->page = new Functest_Node($this);
		}
		return $this->page;
	}

	/**
	 * Move the mouse to a certain element
	 */
	public function move_to($id = NULL, $x = NULL, $y = NULL)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Take a screenshot ant place it in the given file
	 * @param  string $file 
	 */
	public function screenshot($file)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * execute Javascript
	 */
	public function execute($id, $script)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Get the current browser user agent
	 */
	public function user_agent()
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}

	/**
	 * Manually set a cookie
	 * 
	 * @param  string $name    
	 * @param  string $value   
	 * @param  integer $expires 
	 */
	public function cookie($name, $value, $expires = 0)
	{
		throw new Functest_Exception_Notimplemented(__FUNCTION__, $this);
	}
}
