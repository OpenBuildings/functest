<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package FuncTest
 * @group   functest
 * @group   functest.nodelist
 */
class FuncTest_NodeListTest extends FuncTest_Internal_TestCase {

	public function setUp()
	{
		parent::setUp();

		$driver = FuncTest::driver('native')->content(View::factory('functest/index'));

		$this->page = $driver->page();
	}

	public function test_all()
	{
		$nodes = $this->page->all('.content ul.subnav li > a');
		$this->assertInstanceOf('FuncTest_NodeList', $nodes);
		$this->assertCount(3, $nodes);
		$this->assertEquals('css', $nodes->locator()->type());
		$this->assertEquals('.content ul.subnav li > a', $nodes->locator()->selector());

		$this->assertInstanceOf('FuncTest_Node', $nodes[0]);
		$this->assertNode(array('a', 'id' => 'navlink-1'), $nodes[0]);

		$this->assertInstanceOf('FuncTest_Node', $nodes->first());
		$this->assertNode(array('a', 'id' => 'navlink-1'), $nodes->first());

		$this->assertInstanceOf('FuncTest_Node', $nodes[1]);
		$this->assertNode(array('a', 'id' => 'navlink-2'), $nodes[1]);

		$this->assertInstanceOf('FuncTest_Node', $nodes[2]);
		$this->assertNode(array('a', 'id' => 'navlink-3'), $nodes[2]);

		$this->assertInstanceOf('FuncTest_Node', $nodes->last());
		$this->assertNode(array('a', 'id' => 'navlink-3'), $nodes->last());
	}

	public function test_to_string()
	{
		$nodes = $this->page->all('.content ul.subnav li > a');
		$string = $nodes->__toString();

		$this->assertContains('.content ul.subnav li > a', $string, 'Should have the selector as string');
		$this->assertContains('<a class="navlink" id="navlink-2" title="Subpage Title 2" href="/test_functest/subpage2">', $string);
	}

	public function test_as_array()
	{
		$nodes = $this->page->all('.content ul.subnav li > a')->as_array();
		$this->assertInternalType('array', $nodes);

		$this->assertNode(array('a', 'id' => 'navlink-1'), $nodes[0]);
		$this->assertNode(array('a', 'id' => 'navlink-2'), $nodes[1]);
		$this->assertNode(array('a', 'id' => 'navlink-3'), $nodes[2]);
	}

}