<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package FuncTest
 * @group   functest
 * @group   functest.node
 */
class FuncTest_NodeTest extends FuncTest_Database_TestCase {

	public function test_node()
	{
		$view = View::factory('functest/template', array('content' => View::factory('functest/index')))->render();
		$driver = FuncTest::driver('native')->content($view);

		$page = $driver->page();
		
		$nav_links = $page->all('div.navigation li > a');

		$this->assertCount(3, $nav_links);

		$this->assertNode(array('a', 'page1 Nav-1', 'href' => '/test_functest'), $nav_links[0]);
		$this->assertNode(array('a', 'page2 Nav-2', 'href' => '/test_functest/page1'), $nav_links[1]);
		$this->assertNode(array('a', 'page3 Nav-3', 'href' => '/test_functest/page2'), $nav_links[2]);

		$span = $nav_links[0]->find('span');
		$this->assertNode(array('span', 'Nav-1'), $span);
	}

}

