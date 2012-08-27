<?php
/**
 * Func_Test Xpath extension for native driver
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Native_XPath extends DOMXpath
{
	public function find($expression, $contextnode = NULL)
	{
		if ($expression instanceof DOMNode)
			return $expression;

		@ $items = $contextnode ? $this->query($expression, $contextnode) : $this->query($expression);

		if ( ! $items)
			throw new Kohana_Exception('Error in expression: :expression', array(':expression' => $expression));

		if ($items->length == 0)
			throw new Kohana_Exception('No element for selector :expression', array(':expression' => $expression));

		return $items->item(0);

	}
}