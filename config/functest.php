<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'default_driver' => 'native',
	'default_locator_type' => 'css',

	'drivers' => array(
		'native' => array(
			'environment' => array(
				'Request::$client_ip' => '95.87.212.88',
				'Request::$user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.162 Safari/535.19',
			)
		),
		'selenium' => array(
			'url' => 'http://localhost:4444/selenium-server/driver/',
			'timeout' => 2000
		),
	)
);