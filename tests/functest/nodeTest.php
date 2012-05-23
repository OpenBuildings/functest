<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package FuncTest
 * @group   functest
 * @group   functest.node
 */
class FuncTest_NodeTest extends FuncTest_Internal_TestCase {

	public $page;
	public $driver;

	public function setUp()
	{
		parent::setUp();

		$this->driver = $this->getMock('FuncTest_Driver_Native', array('get', 'post'));
		$this->driver->content(View::factory('functest/index'));

		$this->page = $this->driver->page();
	}

	public function test_finders()
	{
		$node = $this->page->find_field('Enter Name');
		$this->assertInstanceOf('FuncTest_Node', $node);
		$this->assertNode(array('input', 'name' => 'name'), $node);

		$node = $this->page->find_link('Subpage Title 3');
		$this->assertInstanceOf('FuncTest_Node', $node);
		$this->assertNode(array('a', 'id' => 'navlink-3'), $node);

		$node = $this->page->find_button('Submit Button');
		$this->assertInstanceOf('FuncTest_Node', $node);
		$this->assertNode(array('button', 'id' => 'submit-btn'), $node);

		$node = $this->page->find('form.contact');
		$this->assertInstanceOf('FuncTest_Node', $node);
		$this->assertNode(array('form', 'action' => '/test_functest/contact'), $node);

		$node = $this->page->find(array('field', 'Enter Name'));
		$this->assertInstanceOf('FuncTest_Node', $node);
		$this->assertNode(array('input', 'name' => 'name'), $node);

		$nodes = $this->page->all('.content ul.subnav li > a');
		$this->assertInstanceOf('FuncTest_NodeList', $nodes);

		$this->setExpectedException('FuncTest_Exception_NotFound');
		$node = $this->page->find('form.contact23');
	}

	public function test_getters()
	{
		$form = $this->page->find('form');
		$input = $form->find_field('Enter Name');
		$textarea = $form->find_field('Enter Message');

		$this->assertNode(array('form', 'action' => '/test_functest/contact'), $form->dom(), 'Should extract the right DOMElement');

		$this->assertEquals("(//form)[1]", $form->id());

		$this->assertEquals('<input id="name" name="name" value="Tomas"/>', $input->html());
		
		$this->assertEquals('<input id="name" name="name" value="Tomas"/>', $input->__toString());
		
		$this->assertEquals('input', $input->tag_name());
		$this->assertEquals('Tomas', $input->attribute('value'));
		$this->assertContains('Lorem ipsum dolor sit amet', $textarea->text());
		$this->assertTrue($textarea->is_visible());
		$this->assertEquals('Tomas', $input->value());

		$option = $this->page->find_field('country')->find('option');
		$this->assertFalse($option->is_selected(), 'Should not be a selected option');
		$option->select_option();
		$this->assertTrue($option->is_selected(), 'Should be a selected option');

		$checkbox = $this->page->find_field('Enter Notify Me');
		$this->assertFalse($checkbox->is_checked(), 'Should not be checked by default');

		$checkbox->set(TRUE);
		$this->assertTrue($checkbox->is_checked(), 'Should be checked after action');

		$radio = $this->page->find_field('Gender Female');
		$this->assertTrue($radio->is_checked(), 'Should be checked by default');

		$radio->set(FALSE);
		$this->assertFalse($radio->is_checked(), 'Should not be checked after action');
	}

	public function test_setters()
	{
		$input = $this->page->find_field('Enter Name');
		$input->set('New Name');
		$this->assertNode(array('input', 'value' => 'New Name'), $input);

		$link = $this->page->find_link('Subpage Title 3');
		$this->driver
			->expects($this->at(0))
			->method('get')
			->with($this->equalTo($link->attribute('href')));

		$link->click();

		$option = $this->page->find_field('country')->find('option', array('at' => 1));
		$option->select_option();

		$this->assertNode(array('option', 'selected' => 'selected'), $option);
		$option->unselect_option();

		$this->assertNull($option->attribute('selected'));
	}

	public function test_actions()
	{
		$this->driver->expects($this->at(0))->method('get')->with($this->equalTo('/test_functest/subpage1'));
		$this->driver->expects($this->at(1))->method('get')->with($this->equalTo('/test_functest/subpage2'));
		$this->driver->expects($this->at(2))->method('post')->with($this->equalTo('/test_functest/contact'));

		$this->page->click_on('#navlink-1');
		$this->page->click_link('icon 2');
		$this->page->click_button('Submit Item');

		$this->page->fill_in('Enter Message', 'New Text');
		$this->assertNode(array('textarea', 'New Text', 'id' => 'message'), $this->page->find('#message'));

		$this->page->choose('Gender Male');
		$this->assertNode(array('input', 'id' => 'gender-1', 'checked' => 'checked'), $this->page->find('#gender-1'));		

		$this->page->check('Enter Notify Me');
		$this->assertNode(array('input', 'id' => 'notifyme', 'checked' => 'checked'), $this->page->find('#notifyme'));		

		$this->page->uncheck('Enter Notify Me');
		$this->assertNull($this->page->find('#notifyme')->attribute('checked'));

		$this->page->select('Enter Country', 'United States');
		$this->assertEquals('us', $this->page->find_field('Enter Country')->value());

		$this->page->select('Enter Country', array('value' => 'uk'));
		$this->assertEquals('uk', $this->page->find_field('Enter Country')->value());

		$this->page->unselect('Enter Country', 'uk');
		$this->assertNull($this->page->find_field('Enter Country')->value());
	}

	public function test_traverse()
	{
		$form = $this->page->find('form');
		$this->assertNode(array('form', 'class' => 'contact'), $form);

		$fieldset = $form->find('fieldset');
		$this->assertNode(array('fieldset'), $fieldset);

		$actions = $fieldset->find('.actions');
		$this->assertNode(array('div', 'class' => 'actions'), $actions);

		$button = $actions->find_button('Submit Button');
		$this->assertNode(array('button', 'id' => 'submit-btn'), $button);

		$button_parent = $button->end();
		$this->assertNode(array('div', 'class' => 'actions'), $button_parent);

		$actions_parent = $button_parent->end();
		$this->assertNode(array('fieldset'), $actions_parent);

		$fieldset_parent = $actions_parent->end();
		$this->assertNode(array('form', 'class' => 'contact'), $form);
	}

}