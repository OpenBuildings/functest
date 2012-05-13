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

	protected $_node_indexes;
	protected $_node;
	protected $_current = 0;

	function __construct(FuncTest_Driver $driver, FuncTest_Locator $locator, FuncTest_Node $parent = NULL)
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
		return $this->_load($this->_current);
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
		return count($this->node_indexes());
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
		
		return $this->_load($offset);
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

	protected function _selector_for($offset)
	{
		$selector = '';
		if ($this->_parent AND $this->_parent->selector())
		{
			$selector = $this->_parent->selector();
		}
		$index = Arr::get($this->node_indexes(), $offset);

		return "({$selector}{$this->_locator->xpath()})[$index]";
	}

	protected function _load($offset)
	{
		return $this->_node->load_vars($this->_selector_for($offset));
	}

	protected function node_indexes()
	{
		if ($this->_node_indexes === NULL)
		{
			$total_count = $this->_driver->count($this->_locator->xpath());
			$this->_node_indexes = $total_count ? array_combine(range(0, $total_count - 1), range(1, $total_count)) : array();

			if ($this->_locator->filters())
			{
				$indexes = array();
				foreach ($this->_node_indexes as $offset => $index) 
				{
					if ($this->_locator->is_filtered($this->_load($offset), $offset))
					{
						$indexes[] = $index;
					}
				}
				$this->_node_indexes = $indexes;
			}

		}

		return $this->_node_indexes;
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
		if ($this->count() <= 0)
			return NULL;

		return $this->_load(0);
	}

	public function at($index)
	{
		return $this->offsetGet($index);
	}

	public function last()
	{
		if ($this->count() <= 0)
			return NULL;

		return $this->_load($this->count() - 1);
	}

	public function as_array()
	{
		$nodes = array();
		foreach ($this->node_indexes() as $i => $index) 
		{
			$nodes[] = FuncTest::node($this->_driver, $this->_parent, $this->_selector_for($i));
		}
		return $nodes;
	}

}
