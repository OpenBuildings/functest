<?php
/**
 * Func_Test Exception, rised on redirect
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Native_Redirect extends Kohana_Exception
{
	protected $url;

	function __construct($url)
	{
		$this->url = $url;
		parent::__construct("Redirected");
	}

	public function url()
	{
		return $this->url;
	}
}