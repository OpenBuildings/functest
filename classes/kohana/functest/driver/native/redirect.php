<?php
/**
* Native Driver exception for avoiding exit on redirect
*/
class Kohana_FuncTest_Driver_Native_Redirect extends Kohana_Exception
{
	protected $response;

	function __construct(Response $response)
	{
		$this->response = $response;
		parent::__construct("Redirected");
	}

	public function getResponse()
	{
		return $this->response;
	}
}