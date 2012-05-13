<?php
/**
 * Func_Test Exception, Encapsulate Environment
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Native_Environment extends Unittest_Helpers {
	
	public function update_environment(array $environment)
	{
		$environment = Arr::merge(Kohana::$config->load('functest.drivers.native.environment'), (array) $environment);
		$this->clear();
		$this->set_environment($environment);
	}

	public function clear()
	{
		if ($this->_environment_backup)
		{
			$this->restore_environment();
		}
	}
}