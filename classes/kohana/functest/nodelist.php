<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Node List represinting a list of nodes. Features lazy loading 
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_NodeList implements Iterator, Countable, SeekableIterator, ArrayAccess {

	protected $_driver;
	protected $_parent;

	protected $_list_ids;
	protected $_node;
	protected $_current = 0;

	function __construct(FuncTest_Driver $driver, FuncTest_Locator $locator, FuncTest_Node $parent)
	{
		$this->_driver  = $driver;
		$this->_locator = $locator;
		$this->_parent  = $parent;
		$this->_node = FuncTest::node($driver, $parent);
	}

	/**
	 * Returns a string representation of the collection.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		$nodes = array();
		foreach($this as $node)
		{
			$nodes[] = $node->html();
		}
		return "FuncTest_NodeList: \nLocator: {$this->locator()} \nContent: [\n".join("\n", $nodes)."\n]\n";
	}

	/**
	 * Implementation of the Iterator interface
	 * @return  FuncTest_NodeList
	 */
	public function rewind()
	{
		$this->_current = 0;
		return $this;
	}

	/**
	 * Implementation of the Iterator interface
	 *
	 * @return  FuncTest_Node
	 */
	public function current()
	{
		$ids = $this->list_ids();
		return $this->_load($ids[$this->_current]);
	}

	/**
	 * Implementation of the Iterator interface
	 * @return  int
	 */
	public function key()
	{
		return $this->_current;
	}

	/**
	 * Implementation of the Iterator interface
	 * @return  FuncTest_NodeList
	 */
	public function next()
	{
		++$this->_current;
		return $this;
	}

	public function prev()
	{
		--$this->_current;
		return $this;
	}

	/**
	 * Implementation of the Iterator interface
	 *
	 * @return  boolean
	 */
	public function valid()
	{
		return $this->offsetExists($this->_current);
	}

	/**
	 * Implementation of the Countable interface
	 *
	 * @return  int
	 */
	public function count()
	{
		return count($this->list_ids());
	}

	/**
	 * Implementation of SeekableIterator
	 *
	 * @param   mixed  $offset
	 * @return  boolean
	 */
	public function seek($offset)
	{
		if ($this->offsetExists($offset))
		{
			$this->current = $offset;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * ArrayAccess: offsetExists
	 *
	 * @param   mixed  $offset
	 * @return  boolean
	 */
	public function offsetExists($offset)
	{
		return ($offset >= 0 AND $offset < $this->count());
	}

	/**
	 * ArrayAccess: offsetGet
	 *
	 * @param   mixed  $offset
	 * @return  FuncTest_Node
	 */
	public function offsetGet($offset)
	{
		if ( ! $this->offsetExists($offset))
			return NULL;
		$ids = $this->list_ids();
		return $this->_load($ids[$offset]);
	}

	/**
	 * ArrayAccess: offsetSet
	 *
	 * @throws  Kohana_Exception
	 * @param   mixed  $offset
	 * @param   mixed  $value
	 * @return  void
	 */
	public function offsetSet($offset, $value)
	{
		throw new Kohana_Exception('Cannot modify FuncTest_NodeList');
	}

	/**
	 * ArrayAccess: offsetUnset
	 *
	 * @throws  Kohana_Exception
	 * @param   mixed  $offset
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		throw new Kohana_Exception('Cannot modify FuncTest_NodeList');
	}

	protected function _load($id)
	{
		return $this->_node->load_vars($id);
	}

	protected function list_ids()
	{
		if ($this->_list_ids === NULL)
		{
			$this->_list_ids = $this->_driver->all($this->_locator->xpath(), $this->_parent->id());

			if ($this->_locator->filters())
			{
				foreach ($this->_list_ids as $offset => $id) 
				{
					if ( ! $this->_locator->is_filtered($this->_load($id), $offset))
					{
						unset($this->_list_ids[$offset]);
					}
				}
			}
		}

		return $this->_list_ids;
	}

	public function locator()
	{
		return $this->_locator;
	}

	public function driver()
	{
		return $this->_driver;
	}

	public function first()
	{
		$ids = $this->list_ids();

		if (count($ids) <= 0)
			return NULL;

		return $this->_load(reset($ids));
	}

	public function at($index)
	{
		return $this->offsetGet($index);
	}

	public function last()
	{
		$ids = $this->list_ids();

		if (count($ids) <= 0)
			return NULL;

		return $this->_load(end($ids));
	}

	public function as_array()
	{
		$nodes = array();
		foreach ($this->list_ids() as $i => $id) 
		{
			$nodes[] = FuncTest::node($this->_driver, $this->_parent, $id);
		}
		return $nodes;
	}

}
