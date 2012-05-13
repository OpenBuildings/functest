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

	/**
	 * Return an array of HTML fragments that match a given XPath query
	 * @param  string $xpath 
	 * @return array        
	 */
	public function all($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Return the HTML content of the current page or set it manually
	 * @param  string $content 
	 * @return string          
	 */
	public function content($content = NULL)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Return the tag name of a HTML fragemnt, specified by the XPath query. 
	 * If multiple tags match - return the first one.
	 * @param  string $xpath 
	 * @return string        
	 */
	public function tag_name($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Return the tag attribute of an HTML fragment, spesified by the XPath query. 
	 * If multiple tags match - return the first one.
	 * @param  [type] $xpath [description]
	 * @param  [type] $name  [description]
	 * @return [type]        [description]
	 */
	public function attribute($xpath, $name)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Return the HTML fragment, spesified by the XPath query. 
	 * If multiple tags match - return the first one.
	 * @param  string $xpath 
	 * @return string        
	 */
	public function html($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Return the plain text of an HTML fragment, spesified by the XPath query. 
	 * If multiple tags match - return the first one.
	 * @param  string $xpath
	 * @return string
	 */
	public function text($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Retrun the value of an HTML fragment of a form input, spesified by the XPath query. 
	 * If multiple tags match - return the first one. 
	 * The value is specific for diferrent for each input type. 
	 * - input -> value
	 * - textarea -> content
	 * - checkbox -> checked
	 * - radios -> checked
	 * - select -> selected option
	 * 
	 * @param  string $xpath 
	 * @return string        
	 */
	public function value($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Return the visibility of an HTML fragment, spesified by the XPath query. 
	 * If multiple tags match - return the first one.
	 * 
	 * @param  string $xpath 
	 * @return string        
	 */
	public function visible($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Set the value for a form input tag.
	 * If multiple tags match - use the first one.
	 * 
	 * @param string $xpath 
	 * @param mixed $value value
	 */
	public function set($xpath, $value)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Set an HTML option tag as selected or remove selection for a given XPath query.
	 * If multiple tags match - use the first one.
	 * 
	 * @param  string $xpath 
	 * @param  boolean $value
	 * @return $this
	 */
	public function select_option($xpath, $value)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * INitiate a click on a give XPath element.
	 * If multiple tags match - use the first one.
	 * You can click on anchor and submit buttons.
	 * 
	 * @param  string $xpath
	 * @return $this
	 */
	public function click($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Go to a specified url
	 * @param  string $uri   
	 * @param  array $query 
	 * @return $this        
	 */
	public function visit($uri, array $query = NULL)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Get the current url without domain
	 * @return string
	 */
	public function current_path()
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	/**
	 * Get the current url with domain
	 * @return string
	 */
	public function current_url()
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
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
}
