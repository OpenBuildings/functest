DSL
===

Each of those methods applies to the whole page or for each element that is returned with one of the finders.


Getters
-------

* `is_root()` _boolean_ is this the main html page? 
* `dom()` _DOMDocument|DOMElement_ The DOMDocument or DOMElement representation of the current tag
* `id()` _mixed_ The current internal ID, unique to this page
* `html()` - _string_ -  * The html source of the current tag
* `__toString()` - _string_ - The html source of the current tag
* `tag_name()` - _string_ - The tag name of the current tag (body, div, input)
* `attribute()` - _string_ - Attribute of the current tag
* `text()` - _string _ - The text content of the current tag (similar to javascript's innerText)
* `is_visible()` - _boolean_ - Is this element visible?
* `is_selected()` - _boolean _ - Is this option element selected?
* `is_checked()` - _boolean_ - Is this checkbox checked?
* `value()` - _string _ - Get the value of the current form field


Setters
-------

Those are basic setters that operate on the current tag
 
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
* `select($selector, $option_filters, array $filters)` - Select an option of a spesific select tag inside the current tag. To select the option the second parameter can be either a string of the option text or a filter to be applied on the options e.g. array('value' => 10)
unselect($selector, $option_filters, array $filters)` - opposite to select
* `confirm($confirm)` - Confirm a javascript alert/confirm dialog box
* `execute($javascript)` - _mixed_ Execute arbitrary javascript on the page and get the result

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

* `find($selector, array $filters = array()` - Find an html tag child of the current tag. This is the basic find method that is used by all the other finders. To work with ajax requests it waits a bit (defualt 2 seconds) for the content to appear on the page before throwing an FuncTest_Exception_NotFound exception
* `find_field($selector, array $filters = array())` - Find an html form field child of the current tag
* `find_link($selector, array $filters = array()` - Find an html form field child of the current tag
* `find_button($selector, array $filters = array())` - Find an html button tag child of the current tag
* `not_present($selector, array $filters = array())` - Oposite to the find() method
* `all($selector, array $filters = array())` - Find a list of elements represented by the selector / filter. Returns an FuncTest_NodeList which is an iterator of FuncTest_Node objects
