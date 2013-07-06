<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'default_driver' => 'native',
	'default_locator_type' => 'css',
	'default_wait_time' => 4000,
	'save_on_failure' => TRUE,
	'failures_dir' => APPPATH.'logs/functest/',
	'screanshots_dir' => APPPATH.'logs/functest-screenshots/',
	'database' => Kohana::TESTING,
	'apptests' => FALSE,
	'modules' => array(
		'functest',
	),
	'drivers' => array(
		'native' => array(
			'environment' => array(
				'Request::$client_ip' => '8.8.8.8', // VALID IP HERE
				'Request::$user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.162 Safari/535.19',
			)
		),
		'selenium' => array(
			'url' => 'http://localhost:4444/wd/hub/',
			'reuse_session' => TRUE,
			'desired' => array(
				'browserName' => 'firefox' // for chrome install chrome driver: http://code.google.com/p/chromedriver/wiki/GettingStarted
			)
		),
		'phantomjs' => array(
			'user_agent' => 'Mozilla/5.0 (Unknown; Linux i686) AppleWebKit/534.34 (KHTML, like Gecko) PhantomJS/1.9.1 Safari/534.34',
			'command' => 'phantomjs --ignore-ssl-errors=true '.MODPATH.'functest/assets/phantom.js '.MODPATH.'functest/assets/phantomjs-connection.js',
			'url' => 'http://localhost:4445/',
			'pid' => APPPATH.'logs/phantomjs.pid',
			'autostart' => TRUE,
		),
	)
);