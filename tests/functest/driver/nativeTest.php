<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package FuncTest
 * @group   functest
 * @group   functest.driver
 * @group   functest.driver.native
 */
class FuncTest_Driver_NodeTest extends FuncTest_Internal_TestCase {

	public $driver;

	public $form_data = array(
		'id' => '10',
		'name' => 'Arthur',
		'post' => array(
			'id' => '1',
			'title' => 'Title 1',
			'tag' => array(
				'name' => 'Red',
				'rating' => '20'
			),
			'body' => 'Lorem Ipsum',
			'type' => 'small',
			'send' => 'sendval',
			'category' => 'hw',
			'ads' => array(
				'text',
				'affiliate'
			)
		)
	);

	public function setUp()
	{
		parent::setUp();
		$this->driver = $this->getMock('FuncTest_Driver_Native', array('get', 'post'));
		$this->driver->content(View::factory('functest/form'));
	}

	public function test_serialize_form()
	{
		$form = $this->driver->page()->find('form')->dom();

		$form_data = $this->driver->forms()->serialize_form($form);
		parse_str($form_data, $form_data);

		$this->assertEquals($this->form_data, $form_data);
	}

	public function test_dom()
	{
		$node = $this->driver->dom("//select[@id='post_category']");

		$this->assertInstanceOf('DOMNode', $node, 'Should return a node');

		$new_node = $this->driver->dom($node);
		$this->assertSame($node, $new_node, 'Should return the node if passed one');

		$this->assertNode(array('select', 'id' => 'post_category'), $node);

		$this->setExpectedException('Kohana_Exception');
		$this->driver->dom("//select[@id='not-present-node']");
	}

	public function test_accessors()
	{
		$tag_name = $this->driver->tag_name("//select[@id='post_category']");
		$this->assertEquals('select', $tag_name);

		$tag_id = $this->driver->attribute("//select[@id='post_category']", 'id');
		$this->assertEquals('post_category', $tag_id);

		$html = $this->driver->html("//textarea[@id='post_body']");
		$this->assertEquals('<textarea name="post[body]" id="post_body" cols="30" rows="10">Lorem Ipsum</textarea>', $html);

		$text = $this->driver->text("//textarea[@id='post_body']");
		$this->assertEquals('Lorem Ipsum', $text);

		$value = $this->driver->value("//input[@id='post_title']");
		$this->assertEquals('Title 1', $value);

		$value = $this->driver->value("//textarea[@id='post_body']");
		$this->assertEquals('Lorem Ipsum', $value);

		$value = $this->driver->value("//select[@id='post_category']");
		$this->assertEquals('hw', $value);

		$value = $this->driver->value("//select[@id='post_ads']");
		$this->assertEquals(array('text', 'affiliate'), $value);
	}

	public function test_visible()
	{
		$value = $this->driver->is_visible("//select[@id='post_ads']");
		$this->assertTrue($value);

		$value = $this->driver->is_visible("//div[@id='hidden']");
		$this->assertFalse($value);

		$value = $this->driver->is_visible("//div[@id='visible']");
		$this->assertTrue($value);
	}

	public function test_actions()
	{
		$this->assertValueSet("//input[@id='post_title']", 'New Title', 'New Title', $this->driver);

		$this->assertValueSet("//textarea[@id='post_body']", 'New Text', 'New Title', $this->driver);

		$this->assertValueSet("//input[@value='tiny']", TRUE, 'tiny', $this->driver);

		$this->assertValueSet("//input[@value='sendval']", TRUE, 'sendval', $this->driver);

		$this->driver->select_option("//select[@id='post_category']//option[text()='Software']", TRUE);

		$value = $this->driver->value("//select[@id='post_category']");
		$this->assertEquals('sw', $value);

		$old_value = $this->driver->dom("//select[@id='post_category']//option[text()='Hardware']");
		$this->assertFalse($old_value->hasAttribute('selected'));

		$this->driver->select_option("//select[@id='post_ads']//option[text()='Banner']", TRUE);

		$value = $this->driver->value("//select[@id='post_ads']");
		$this->assertEquals(array('banner', 'text', 'affiliate'), $value);

		$this->driver->select_option("//select[@id='post_ads']//option[text()='Text']", FALSE);

		$value = $this->driver->value("//select[@id='post_ads']");
		$this->assertEquals(array('banner', 'affiliate'), $value);
	}

	public function test_clicks()
	{
		$this->driver
			->expects($this->once())
			->method('get')
			->with($this->equalTo('/test_functest/page1'));

		$this->driver
			->expects($this->at(1))
			->method('post')
			->with($this->equalTo('/test_functest/form'), $this->anything(), $this->equalTo($this->form_data));

		$this->driver
			->expects($this->at(2))
			->method('post')
			->with($this->equalTo('/test_functest/form'), $this->anything(), $this->equalTo($this->form_data + array('submit_input' => 'Submit Item')));


		$this->driver->click("//a[@id='visible-link']");
		$this->driver->click("//button[@id='submit-btn']");
		$this->driver->click("//input[@id='submit']");
	}


}

