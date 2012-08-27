<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Func_Test Exception for not implemented driver features
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Exception_Webdriver extends Kohana_Exception {

	public $status;

	public static $codes = array(
		'0'	=> array('Success', 'The command executed successfully.'),
		'7'	=> array('NoSuchElement', 'An element could not be located on the page using the given search parameters.'),
		'8'	=> array('NoSuchFrame', 'A request to switch to a frame could not be satisfied because the frame could not be found.'),
		'9'	=> array('UnknownCommand', 'The requested resource could not be found, or a request was received using an HTTP method that is not supported by the mapped resource.'),
		'10'	=> array('StaleElementReference', 'An element command failed because the referenced element is no longer attached to the DOM.'),
		'11'	=> array('ElementNotVisible', 'An element command could not be completed because the element is not visible on the page.'),
		'12'	=> array('InvalidElementState', 'An element command could not be completed because the element is in an invalid state (e.g. attempting to click a disabled element).'),
		'13'	=> array('UnknownError', 'An unknown server-side error occurred while processing the command.'),
		'15'	=> array('ElementIsNotSelectable', 'An attempt was made to select an element that cannot be selected.'),
		'17'	=> array('JavaScriptError', 'An error occurred while executing user supplied JavaScript.'),
		'19'	=> array('XPathLookupError', 'An error occurred while searching for an element by XPath.'),
		'21'	=> array('Timeout', 'An operation did not complete before its timeout expired.'),
		'23'	=> array('NoSuchWindow', 'A request to switch to a different window could not be satisfied because the window could not be found.'),
		'24'	=> array('InvalidCookieDomain', 'An illegal attempt was made to set a cookie under a different domain than the current page.'),
		'25'	=> array('UnableToSetCookie', 'A request to set a cookie\'s value could not be satisfied.'),
		'26'	=> array('UnexpectedAlertOpen', 'A modal dialog was open, blocking this operation'),
		'27'	=> array('NoAlertOpenError', 'An attempt was made to operate on a modal dialog when one was not open.'),
		'28'	=> array('ScriptTimeout', 'A script did not complete before its timeout expired.'),
		'29'	=> array('InvalidElementCoordinates', 'The coordinates provided to an interactions operation are invalid.'),
		'30'	=> array('IMENotAvailable', 'IME was not available.'),
		'31'	=> array('IMEEngineActivationFailed', 'An IME engine could not be started.'),
		'32'	=> array('InvalidSelector', 'Argument was an invalid selector (e.g. XPath/CSS).'),
	);

	public function error()
	{
		return FuncTest_Exception_Webdriver::$codes[$this->status][0];
	}

	public function message()
	{
		return FuncTest_Exception_Webdriver::$codes[$this->status][1];
	}

	public function __construct($status)
	{
		$this->status = $status;
		parent::__construct("Selenium webdriver got exception :error - ':message'", array(':error' => $this->error(), ':message' => $this->message()));
	}

}
