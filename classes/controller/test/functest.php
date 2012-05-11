<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Test_Functest extends Controller_Template {

	public $template = 'functest/template';

	public function action_index()
	{
		$this->template->content = View::facotory('functest/template');
	}
}