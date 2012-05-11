<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Kohana_FuncTest_Driver {

	public $page = NULL;

	public $name = NULL;

	public function all($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function content($content)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function tag_name($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function attribute($xpath, $name)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function html($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function text($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function value($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function visible($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function set($xpath, $value)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function select_option($xpath, $value)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function click($xpath)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function visit($uri, array $query = NULL)
	{
		throw new FuncTest_Exception_NotImplemented(__FUNCTION__, $this->name);
	}

	public function page()
	{
		if ( ! $this->page)
		{
			$this->page = new FuncTest_Node($this);
		}
		return $this->page;
	}
}
