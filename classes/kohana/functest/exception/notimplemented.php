<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * @package    FuncTest
 * @author     Ivan Kerin
 * @copyright  (c) 20011-2012 Despark Ltd.
 * @license    http://creativecommons.org/licenses/by-sa/3.0/legalcode
 */
class Kohana_FuncTest_Exception_NotImplemented extends Kohana_Exception {

	public function __construct($method, $driver)
	{
		parent::__construct("Method ':method' not implemented by driver 'driver'", array(':method' => $method, ':driver' => $driver));
	}

}
