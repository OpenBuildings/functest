<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Testcase_Functest_Internal definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Testcase_Functest_Internal extends Testcase_Functest {

	public $modpath;
	public $test_folder;

	public function setUp()
	{
		parent::setUp();
		$this->environment()->backup_and_set(array(
			'Request::$client_ip' => '127.0.0.1',
			'Request::$user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.162 Safari/535.19',
			'functest.modules' => array('functest'),
			'functest.apptests' => FALSE,
		));

		$this->modpath = Arr::get(Kohana::modules(), 'functest');
		$this->test_folder = 'tests'.DIRECTORY_SEPARATOR.'test_data'.DIRECTORY_SEPARATOR.'test_folder'.DIRECTORY_SEPARATOR;
	}

	public function assertValueSet($node, $value, $expected_value, $driver, $message = 'Should set value of field properly')
	{
		$driver->set($node, 'New Title');
		$new_value = $this->driver->value($node);
		$this->assertEquals($expected_value, $new_value, $message);
	}

	public function assertNode($options, $tag, $message = 'Tag should be present')
	{
		$this->assertNotNull($tag, $message);

		if ($tag instanceof Functest_Node)
		{
			$tag = $tag->dom();
		}

		$this->assertInstanceOf('DOMNode', $tag, 'Should be of appropriate html tag type');

		foreach ((array) $options as $name => $value) 
		{
			switch($name)
			{
				case '0':
					$this->assertEquals($value, $tag->nodeName, "The tag should be with type {$value} but was {$tag->nodeName}");
				break;
				case '1':
					$this->assertEquals($value, $tag->textContent, "The tag should have text {$value} but had {$tag->textContent}");	
				break;
				default:
					$this->assertTrue($tag->hasAttribute($name), "Tag should have attribute {$name}");
					$this->assertEquals($value, $tag->getAttribute($name), "Tag's attribute {$name} should be {$value} but was {$tag->getAttribute($name)}");
			}
		}
	}
}