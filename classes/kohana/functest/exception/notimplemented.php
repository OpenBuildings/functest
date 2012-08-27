<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Func_Test Exception for not implemented driver features
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Exception_NotImplemented extends Kohana_Exception {

	public function __construct($method, FuncTest_Driver $driver)
	{
		parent::__construct("Method ':method' not implemented by driver ':driver'", array(':method' => $method, ':driver' => $driver->name));
	}

}
