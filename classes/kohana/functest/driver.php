<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Basic driver you have to extend this class and implement its functions
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
abstract class Kohana_FuncTest_Driver {

	public $page = NULL;

	public $name = NULL;

	public $current_test = NULL;

	/**
	 * Return an array of HTML fragments that match a given XPath query
	 * @param  string $id 
	 * @return array        
	 */
	public function all($id)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return an the id of the html elemenet
	 * @throws FuncTest_Exception_NotFound If element not found
	 * @param  string $id 
	 * @return array        
	 */
	public function find($id)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the HTML content of the current page or set it manually
	 * @param  string $content 
	 * @return string          
	 */
	public function content($content = NULL)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the tag name of a HTML fragemnt, specified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id 
	 * @return string        
	 */
	public function tag_name($id)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the HTML fragment, spesified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id 
	 * @return string        
	 */
	public function html($id)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return the plain text of an HTML fragment, spesified by id 
	 * If multiple tags match - return the first one.
	 * @param  string $id
	 * @return string
	 */
	public function text($id)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
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
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Go to a specified url
	 * @param  string $uri   
	 * @param  array $query 
	 * @return $this        
	 */
	public function visit($uri, array $query = NULL)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Get the current url without domain
	 * @return string
	 */
	public function current_path()
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Get the current url with domain
	 * @return string
	 */
	public function current_url()
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Confirm or cancel for the next confirmation dialog
	 * @param  bool $confirm 
	 */
	public function confirm($confirm)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return The root node
	 * @return FuncTest_Node 
	 */
	public function has_page()
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}

	/**
	 * Return The root node
	 * @return FuncTest_Node 
	 */
	public function javascript_errors()
	{
		return array();
	}

	/**
	 * Return The root node
	 * @return FuncTest_Node 
	 */
	public function page()
	{
		if ( ! $this->page)
		{
			$this->page = new FuncTest_Node($this);
		}
		return $this->page;
	}

	/**
	 * Move the mouse to a certain element
	 */
	public function move_to()
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this);
	}
}
