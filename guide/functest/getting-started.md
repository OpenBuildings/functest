Getting started with Functest
=============================

Functest requires kohana's Unittest module to operate. So you'll have to enable both in your modules/unittest/bootsrap.php file:

	Kohana::modules(Kohana::modules() + array(
		'unittest' => MODPATH.'unittest', 
		'functest' => MODPATH.'functest',
		// ...
	));

That way both of those modules will be available only to phpunit.

Your First Functest
-------------------

You should generally put your tests inside APPPATH/tests/* or MODPATH/*/tests/* directories - that's where Kohana's unittest is searching for them. So lets put a test as APPPATH/tests/func/myTest.php

	<?php defined('SYSPATH') OR die('No direct script access.');

	/**
	 * @group   func
	 */
	class myTest extends FuncTest_TestCase {

		public function test_finders()
		{
			$this->visit('/my/url');
			$this->assertHasCss('h1', array('text' => 'Hellow World'))
		}

	}

That test will open up a url of /my/url and check if there is a h1 tag containing the text 'Hellow World'. Those are just 2 methods of the [DSL](/OpenBuildings/functest/blob/master/guide/functest/dsl.md) which is quite powerful.

_Notice:_
> Most Functional test frameworks and this one as well discourages using direct POST / PUT / DELETE requests, as users can't perform those from browsers directly and are not supported by selenium or other drivers. That's way there is only a `visit()` method which acts as though the user entered some text into the URL field. However, if you really really want to perform such a request (for example testing out some API) you can still do that with `$this->driver()->get()`, `$this->driver()->post()`, `$this->driver()->put()` or `$this->driver()->delete()`. Keep in mind that you will not be able to convert to selenium tests later, if you use those methods.


Testing Forms
-------------

You can perform "user-like" events - filling out forms, checking out checkboxes, picking form select lists. For example for a form like this:

	<form action="users/create" method="post" class="my-form" />
		<label for="name">Name</label>
		<input name="name" value="" id="name"/>
		<br />

		<label for="username">Username</label>
		<input name="username" value="" id="username"/>
		<br />

		<select name="year" id="year">
			<option>Select Year</option>
			<option value="2001">2001</option>
			<option value="2002">2002</option>
			<option value="2003">2003</option>
			<option value="2004">2004</option>
		</select>
		<br />

		<label for="newsletter">Recieve Newsletter</label>
		<input name="newsletter" type="checkbox" value="1" id="newsletter"/>
		<br />

		<button type="submit">
			Create User
		</button>

	</form>

We will have this test:

	<?php defined('SYSPATH') OR die('No direct script access.');

	/**
	 * @group   func
	 */
	class myTest extends FuncTest_TestCase {

		public function test_finders()
		{
			$this->visit('/my/url');
			$this->fill_in('Name', 'John');
			$this->fill_in('Username', 'john-user');
			$this->select('Select Year', '2004');
			$this->check('Recieve Newsletter');
			$this->click_button('Create User');
		}

	}

This will fill out a form, and click the button named "Create User". Most form elements can be selected by what text appears to the user and is associated with a given input. For example here we have an `<input />` that is associated with labels and we can find them by the text of the label. Or for select tags we can search by the texts of the placeholder option.

The whole philosophy is to be able to write tests without looking too much at the underlying HTML as it is generally more volatile than the content of the page thus making the tests more resilient. And they are much easier to write in the first place.


Assertions and contexts
-----------------------

Sometimes you want to fill in forms inside a particular form as there may be other forms with similar inputs on the same page. Or you just want to better organize your assertions. This can be easily accomplished by changing the context of the DSL methods. By default they are executed on the whole page, however you can search for individual HTML tags and change the context to that tag in particular.

	<?php defined('SYSPATH') OR die('No direct script access.');

	/**
	 * @group   func
	 */
	class myTest extends FuncTest_TestCase {

		public function test_finders()
		{
			$this->visit('/my/url');

			$this
				->find('form.my-form')
					->fill_in('Name', 'John')
					->fill_in('Username', 'john-user')
					->select('Select Year', '2004')
					->check('Recieve Newsletter')
					->click_button('Create User');
		}

	}

All the DSL methods will be performed in the context of the form. Also, all of the DSL methods return their context so you can chain them together as in the example above. This works with the global context too so you can write it like this:

	$this
		->visit('/my/url')
		->assertHasCss('h1', array('text' => 'Hellow World'))
		->find('form.my-form')
			->fill_in('Name', 'John')
			->fill_in('Username', 'john-user')
			->select('Select Year', '2004')
			->check('Recieve Newsletter')
			->click_button('Create User');

Using this technique you can also make assertion for HTML tags only in a given context

	$this
		->visit('/my/url')
		->find('form.my-form')
			->assertHasCss('label', array('text' => 'Username'));


Multiple tags and tag counts
----------------------------

You can make assertions for multiple tags using the `->all()` method - it returns an iteratable list of HTML tags (contexts), each of which is a fully capable context and has the full DSL so all those things are possible:


	$this
		->visit('/my/url');

	$items = $this->all('ul.items li');

	$this->assertCount(3, $items);

	foreach ($items as $i => $item)
	{
		$item
			->assertHasCss('label', array('text' => 'Username'))
			->fill_in('Username', "User $i");
	}

Filters and Locators
--------------------

HTML Tags of the page are located by using several types of "locators"

	$this->find('.big-div'); // Find a HTML tag with class of .big-div
	$this->find_link('Link Button'); // Find an anchor tag with text of "Link Button"
	$this->find_button('Link Button'); // Find an button tag with text of "Link Button"

Most find and assert methods have similar methods for different locators (as shown above) and you can select the "default" locator in the configuration. Initially its set to "css"

__Locators__

* __css__ this is the basic locator and is nothing more than a css selector. It's not very advanced and does not handle pseudo classes and CSS 3 selectors so don't get really complicated here. Most of the time you can chain the selectors multiple times to find what you want. `$this->all('.item')->first()->find('a.link')->find('span')`
* __xpath__ you can use this if you need a really powerful selector. It's mostly used internally by FuncTest
* __link__ find HTML anchor tags. It searches for text inside anchors (including child elements), the title of the anchor, the id of the anchor or the alt text of a child img tag.
* __field__ this is used to find input fields - it searches for the id, name or title of the input, the text of the label tag, pointing to that input, the placeholder attribute or the text of the option with no value for select tags.
* __button__ its similar to the link selector, but searches for button tags (and inputs with type of button) - It searches for text inside button (including child elements), the title of the button, the id, name or value of the button or the alt text of a child img tag.

`find()`, `click_on()`, `hover_on()` and all the assertions have locator specific methods. You can see all of them in the [DSL Page](/OpenBuildings/functest/blob/master/guide/functest/dsl.md)

__Filters__

All of those locators can be supplemented with filters. They filter out the tags and select exactly the field you need. To use them simply add a second argument to finders (or third if its an action method)

	$this->find('.item', array('text' => 'Item One'));
	$this->find('.item', array('at' => 2));
	$this->fill_in('.item', 'Text', array('visible' => TRUE));

* __at__ simply select an element of the returned array of elements, if the page has more elements with the same selector. Starts at 0
* __text__ only allow elements that has the specified text inside them. Normalizes the text to remove new lines and multiple spaces
* __value__ only allow elements that have a value matching the specified one - works for all input fields - input, textarea and select
* __visible__ only select visible (or hidden) elements

> __Notice__ Filters are applied on all of the returned elements so its not very performant. Especially in the case of Selenium Driver - they require a round trip to the selenium server for each tag. Use them sparingly and with smaller collection of HTML tags.

Drivers
-------

FuncTest comes with 2 drivers out of the box - Native and Selenium - but you can write your own drivers (and send pull requests :)) by implementing the methods of the abstract driver. Drivers work even if not all of the methods have been implemented, for example native driver does not support any Javascript stuff.

To change drivers modify the public $driver_name property:

	<?php defined('SYSPATH') OR die('No direct script access.');

	/**
	 * @group   func
	 */
	class myTest extends FuncTest_TestCase {

		public $driver_name = 'selenium';

		// ...
	}

_Native Driver_

Native Driver is implemented around Kohana_Request and Kohana_Response methods thus reusing the Kohana classes between request which is significantly faster than sending external requests and starting other php threads. However the database and most of the objects stay the same and so there might be isolation issues. There is a lot of effort to keep all the environment variables different for each request but you will have to make sure your code does not keep state between requests. This driver does not support any Javascript.

_Selenium Driver_

This driver uses selenium 2 JSON wire protocol to communicate with real browsers but you'll have to have selenium server running that talks to the said browser. It acts as a lightweight Selenium WebDriver implementation. 

Logs and Failures
-----------------

Whenever you have a failure / error in a test, a snapshot of the current state of the HTML will be put in the APPPATH/logs/functest/ Folder with the text of the failure so you can easily find out why it occurred.


