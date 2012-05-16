<?php defined('SYSPATH') OR die('No direct access allowed.');

class Auth_Jelly_Dummy extends Auth_Clippings {

	static public function reload()
	{
		Auth::$_instance = NULL;
	}

	protected $_use_cookies = TRUE;

	public function enable_cookies($enabled)
	{
		$this->_use_cookies = $enabled;
		return $this;
	}

	public function enable_services($enabled)
	{
		foreach ($this->_services as $service) 
		{
			$service->enabled($enabled);
		}
		return $this;
	}

	public function auto_login()
	{
		if (Kohana::$environment === Kohana::TESTING AND Request::initial() AND ($user = Request::initial()->query('_logged_in')))
		{
			$user = $this->_load_user($user);
			$this->complete_login($user);
			return $user;
		}

		if ($user = parent::auto_login())
			return $user;
		
		return FALSE;
	}

	protected function _autologin_cookie($token = NULL, $expires = NULL)
	{
		if ($this->_use_cookies)
			return parent::_autologin_cookie($token, $expires);

		if ($token === FALSE)
		{
			unset($_COOKIE['authautologin']);
		}
		elseif ($token !== NULL) 
		{
			$_COOKIE['authautologin'] = $token;
		}
		else
		{
			return Arr::get($_COOKIE, 'authautologin');
		}
		return $this;
	}

	public function set_service($name, $service)
	{
		$this->_services[$name] = $service;
	}

}