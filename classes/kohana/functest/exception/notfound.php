<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Func_Test Exception for not implemented driver features
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Exception_NotFound extends Kohana_Exception {

	public $driver;
	public $locator;

	public function __construct(FuncTest_Locator $locator, FuncTest_Driver $driver)
	{
		$this->driver = $driver;
		$this->locator = $locator;

		parent::__construct("Item (:type) ':selector' not found by driver ':driver'", array(':type' => $locator->type(), ':selector' => $locator->selector(), ':driver' => $driver->name));
	}

}
