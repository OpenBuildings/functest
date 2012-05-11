<?php
/**
* Native Driver exception for avoiding exit on redirect
*/
class Kohana_FuncTest_Driver_Native_XPath extends DOMXpath
{
	public function find($expression, $contextnode = NULL)
	{
		if ($expression instanceof DOMNode)
			return $expression;

		$items = $contextnode ? $this->query($expression, $contextnode) : $this->query($expression);

		if ($items->length == 0)
			throw new Kohana_Exception('No element for selector :expression', array(':expression' => $expression));

		return $items->item(0);

	}
}