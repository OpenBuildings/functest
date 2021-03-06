DSL
===

Each of those methods applies to the whole page or for each element that is returned with one of the finders.


Getters
-------

* `is_root()` - is this the main html page? 
* `dom()` - The DOMDocument or DOMElement representation of the current tag
* `id()` - The current internal ID, unique to this page
* `html()` -  The html source of the current tag
* `__toString()` - The html source of the current tag
* `tag_name()`  - The tag name of the current tag (body, div, input)
* `attribute()` - Attribute of the current tag
* `text()` - The text content of the current tag (similar to javascript's innerText)
* `is_visible()` - Is this element visible?
* `is_selected()` - Is this option element selected?
* `is_checked()` - Is this checkbox checked?
* `value()` - Get the value of the current form field


Setters
-------

Those are basic setters that operate on the current tag. These are low level methods and you should generally not have to use them at all. They are mostly used internally by the Actions.
 
* `set($value)` - Set the value for the current form field. 
* `append($value)` - Append to the current value - useful for textarea / input fields
* `click()` - Click on the current html tag, either a button or a link
* `select_option()` - Select an option for the current select tag
* `unselect_option()` - Unselect an option for the current select tag
* `hover()` - Hover over the current tag with the mouse


Actions
-------

* `hover_on($selector, array $filters)` - Hover the mouse over a specific tag child of the current tag
* `hover_link($selector, array $filters)` - Hover the mouse over a specific link child of the current tag
* `hover_field($selector, array $filters)` - Hover the mouse over a specific field child of the current tag
* `hover_button($selector, array $filters)` - Hover the mouse over a specific button child of the current tag
* `click_on($selector, array $filters)` - Click on a specifc tag child of the current tag
* `click_link($selector, array $filters)` - Click on a specifc link child of the current tag
* `click_button($selector, array $filters)` - Click on a specifc button child of the current tag
* `fill_in($selector, $with, array $filters)` - Set the value of the specific form field inside the current tag
* `choose($selector, array $filters)` - Choose a spesific radio tag inside the current tag
* `check($selector, array $filters)` - Check a spesific checkbox input tag inside the current tag
* `uncheck($selector, array $filters)` - Uncheck a spesific checkbox input tag inside the current tag
* `attach_file($selector, $file, array $filters)` - Attach a file to a spesific file input tag inside the current tag
* `select($selector, $option_filters, array $filters)` - Select an option of a spesific select tag inside the current tag. To select the option the second parameter can be either a string of the option text or a filter to be applied on the options e.g. `array('value' => 10)`
* `unselect($selector, $option_filters, array $filters)` - opposite to select
* `confirm($confirm)` - Confirm a javascript alert/confirm dialog box
* `execute($javascript)` - Execute arbitrary javascript on the page and get the result

Assertions
----------

* `assertHasCss($selector, array $filters = array(), $message = NULL)` - Assert that an html tag exists inside the current tag
* `assertHasNoCss($selector, array $filters = array(), $message = NULL)` - Assert that an html tag does not exist inside the current tag
* `assertHasField($selector, array $filters = array(), $message = NULL)` - Assert that an form field exists inside the current tag
* `assertHasNoField($selector, array $filters = array(), $message = NULL)` - Assert that an form field does not exist inside the current tag
* `assertHasXPath($selector, array $filters = array(), $message = NULL)` - Assert that an html tag exists inside the current tag, matched by xpath
* `assertHasNoXPath($selector, array $filters = array(), $message = NULL)` - Assert that an html tag does not exist inside the current tag matched by xpath
* `assertHasLink($selector, array $filters = array(), $message = NULL)` - Assert that an html anchor tag exists inside the current tag
* `assertHasNoLink($selector, array $filters = array(), $message = NULL)` - Assert that an html anchor tag does not exist inside the current tag
* `assertHasButton($selector, array $filters = array(), $message = NULL)` - Assert that an html button tag exists inside the current tag
* `assertHasNoButton($selector, array $filters = array(), $message = NULL)` - Assert that an html button tag does not exist inside the current tag

Finders
-------

* `find($selector, array $filters = array()` - Find an html tag child of the current tag. This is the basic find method that is used by all the other finders. To work with ajax requests it waits a bit (defualt 2 seconds) for the content to appear on the page before throwing an Functest_Exception_Notfound exception
* `find_field($selector, array $filters = array())` - Find an html form field child of the current tag
* `find_link($selector, array $filters = array()` - Find an html form field child of the current tag
* `find_button($selector, array $filters = array())` - Find an html button tag child of the current tag
* `not_present($selector, array $filters = array())` - Oposite to the find() method
* `all($selector, array $filters = array())` - Find a list of elements represented by the selector / filter. Returns an Functest_Nodelist which is an iterator of Functest_Node objects


Test Spesifict Methods
----------------------

These methods are used only by the testcase itself and cant be used from child tags

* `visit($uri, array $query = array())` - point the browser to the given url. You can use relative urls (to the bootstrap) or absolute ones. It is important to use `$query` for query url parameters (?test=1) as they are merged with other system-generated ones, for example logging in with selenium driver
* `current_path()` - Get the current URI path (without the domain)
* `current_url()` - Get the current URL (with the domain)
* `content()` - Get the raw html content of the page
* `driver()` - Get the current driver object


__Constraints__

Those create PHPUnit constraints and can be chained with other constraints to create elaborate logical conditions where nessesery

* `hasCss($selector, array $filters = array())`
* `hasXpath($selector, array $filters = array())`
* `hasField($selector, array $filters = array())`
* `hasButton($selector, array $filters = array())`
* `hasLink($selector, array $filters = array())`
* `hasNoCss($selector, array $filters = array())`
* `hasNoXpath($selector, array $filters = array())`
* `hasNoField($selector, array $filters = array())`
* `hasNoButton($selector, array $filters = array())`
* `hasNoLink($selector, array $filters = array())`
