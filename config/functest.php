<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'default_driver' => 'native',
	'default_locator_type' => 'css',
	'default_wait_time' => 4000,
	'save_on_failure' => TRUE,
	'failures_dir' => APPPATH.'logs/functest/',
	'screanshots_dir' => APPPATH.'logs/functest-screenshots/',
	'database' => Kohana::TESTING,
	'apptests' => TRUE,
	'modules' => array(
	),
	'drivers' => array(
		'kohana' => array(
			'environment' => array(
				'Request::$client_ip' => '8.8.8.8', // VALID IP HERE
				'Request::$user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.162 Safari/535.19',
			)
		),
		'selenium' => array(
			'server' => 'http://localhost:4444/wd/hub/',
			'desired' => array(
				'browserName' => 'firefox' // for chrome install chrome driver: http://code.google.com/p/chromedriver/wiki/GettingStarted
			)
		),
		'phantomjs' => array(
			'server' => 'http://localhost/',
			'pid' => APPPATH.'logs/phantomjs.pid',
			'log' => APPPATH.'logs/phantomjs.log',
		),
	)
);
