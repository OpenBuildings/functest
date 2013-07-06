<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Func_Test Exception for not implemented driver features
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Functest_Exception_Notfound extends Kohana_Exception {

	public $driver;
	public $locator;

	public function __construct(Functest_Locator $locator, Functest_Driver $driver)
	{
		$this->driver = $driver;
		$this->locator = $locator;

		parent::__construct('Item (:type) ":selector", filters :filters, not found by driver ":driver"', array(
			':type' => $locator->type(), 
			':selector' => $locator->selector(),
			':driver' => $driver->name, 
			':filters' => preg_replace('/(\n|\s{2,})/', '', print_r($locator->filters(), TRUE)),
		));
	}

}
