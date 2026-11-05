<?php 


class ConnectionApiController extends MainController 
{

	public function __construct($config)
	{
		parent::__construct($config, []);
	}

	// [GET METHOD]
	public function Verify():void
	{
		$this->response->VerifyMethod("GET");
		$this->response->Response(200, ['service' => 'ON']);
	}
}