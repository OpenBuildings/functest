<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package FuncTest
 * @group   functest
 * @group   functest.locator
 */
class FuncTest_LocatorTest extends FuncTest_Internal_TestCase {

	public function provider_types()
	{
		return array(
			array(array('.nav'), 'css'),
			array(array('.field a'), 'css'),
			array(array(array('field', 'Maraba')), 'field'),
			array(array(array('link', 'Maraba')), 'link'),
			array(array(array('button', 'Maraba')), 'button'),
			array(array(array('xpath', '//Maraba')), 'xpath'),
			array(array(array('field', 'Maraba'), array('value' => '1')), 'field'),
			array(array('fieldren', 'Maraba'), NULL),
		);
	}

	/**
	 * @dataProvider provider_types
	 */
	public function test_types($args, $type)
	{
		if ($type)
		{
			$locator = call_user_func_array('FuncTest::locator', $args);

			$this->assertEquals($type, $locator->type(), 'Should load appropriate type');	
		}
		else
		{
			$this->setExpectedException('Kohana_Exception');
			$locator = FuncTest::locator($args);
		}
	}

	public function provider_finders()
	{
		$view = View::factory('functest/index');
		$doc = new DOMDocument;
		$doc->loadHTML($view->render());
		$xpath = new DOMXPath($doc);

		return array(
			array($xpath, array('css', '.content .subnav'), 1, array('ul', 'class' => 'subnav')),
			array($xpath, array('css', 'form[action="/test_functest/contact"]'), 1, array('form', 'class' => 'contact', 'action' => '/test_functest/contact')),
			array($xpath, array('css', '.content .subnav > li > a'), 3, array('a', 'class' => 'navlink')),
			array($xpath, array('css', '#index'), 1, array('div', 'id' => 'index')),
			array($xpath, array('css', '.page p'), 3, array('p', 'id' => 'p-1')),

			array($xpath, array('link', 'Subpage 1'), 1, array('a', 'href' => '/test_functest/subpage1')),
			array($xpath, array('link', 'Subpage 2'), 1, array('a', 'href' => '/test_functest/subpage2')),
			array($xpath, array('link', 'navlink-3'), 1, array('a', 'href' => '/test_functest/subpage3')),
			array($xpath, array('link', 'Subpage Title 1'), 1, array('a', 'href' => '/test_functest/subpage1')),
			array($xpath, array('link', 'icon 3'), 1, array('a', 'href' => '/test_functest/subpage3')),

			array($xpath, array('button', 'submit input'), 1, array('input', 'id' => 'submit')),
			array($xpath, array('button', 'Submit Item'), 1, array('input', 'id' => 'submit')),
			array($xpath, array('button', 'submit'), 1, array('input', 'id' => 'submit')),
			array($xpath, array('button', 'Submit Button'), 1, array('button', 'id' => 'submit-btn')),
			array($xpath, array('button', 'submit-btn'), 1, array('button', 'id' => 'submit-btn')),
			array($xpath, array('button', 'Submit Image'), 1, array('button', 'id' => 'submit-btn-icon')),
			array($xpath, array('button', 'Image Title'), 1, array('button', 'id' => 'submit-btn-icon')),
			array($xpath, array('button', 'submit-btn-icon'), 1, array('button', 'id' => 'submit-btn-icon')),

			array($xpath, array('field', 'email'), 1, array('input', 'id' => 'email')),
			array($xpath, array('field', 'Enter Email'), 1, array('input', 'id' => 'email')),
			array($xpath, array('field', 'This is your email'), 1, array('input', 'id' => 'email')),
			array($xpath, array('field', 'name'), 1, array('input', 'id' => 'name')),
			array($xpath, array('field', 'message'), 1, array('textarea', 'id' => 'message')),
			array($xpath, array('field', 'Enter Message'), 1, array('textarea', 'id' => 'message')),
			array($xpath, array('field', 'country'), 1, array('select', 'id' => 'country')),
			array($xpath, array('field', 'Enter Country'), 1, array('select', 'id' => 'country')),
			array($xpath, array('field', 'submit'), 0, NULL),
			array($xpath, array('field', 'gender'), 2, array('input', 'type' => 'radio', 'name' => 'gender')),
			array($xpath, array('field', 'Gender Male'), 1, array('input', 'type' => 'radio', 'name' => 'gender', 'value' => 'male')),
			array($xpath, array('field', 'Gender Female'), 1, array('input', 'type' => 'radio', 'name' => 'gender', 'value' => 'female')),

			array($xpath, array('xpath', '//form[@class="contact"]'), 1, array('form', 'class' => 'contact', 'action' => '/test_functest/contact')),
		);
	}

	/**
	 * @dataProvider provider_finders
	 */
	public function test_finders($xpath, $args, $count, $expected_node)
	{
		$locator = FuncTest::locator($args);

		$result = $xpath->query($locator->xpath());

		$node = $result->item(0);
		$this->assertEquals($count, $result->length, 'Should have '.$count.' of elements with from xpath '.$locator->xpath());

		if ($expected_node)
		{
			$this->assertNode($expected_node, $node, 'Should have a tag from xpath '.$locator->xpath());
		}
	}

	public function test_filters()
	{
		$driver = FuncTest::driver('native')->content(View::factory('functest/index')->render());

		$paragraph = $driver->page()->find('.page p', array('text' => 'Filtered!'));
		
		$this->assertNode(array('p', 'id' => 'p-2'), $paragraph);

		$paragraph = $driver->page()->find('.page p', array('visible' => FALSE));

		$this->assertNode(array('p', 'id' => 'p-3'), $paragraph);
		
		$us_option = $driver->page()
			->find_field('Enter Country')
			->find('option', array('value' => 'us'));

		$this->assertNode(array('option', 'value' => 'us'), $us_option);
	}
}

