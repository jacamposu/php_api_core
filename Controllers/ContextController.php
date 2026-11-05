<?php 
/**
* ----------------------------------------------------------
* Users Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class ContextController extends MainController 
{
	private $headers;
	protected $config;
	private $responseCode;
	private $environment;	

	public function __construct($config)
	{
		try 
		{
			$this->headers = getallheaders();
			parent::__construct($config, $this->headers);
			$this->config = $config;
			$this->responseCode = 200;
		} 
		catch (Exception $e) 
		{
			$this->response->Response($this->responseCode, $e->getMessage());
		}
	}

	/* [GET METHOD] */
	public function All ()
	{
		try 
		{
			$this->response->VerifyMethod("GET");

			$session = json_decode(base64_decode(explode(".", $this->headers["token"])[1]));
			
			$this->environment = new Environment($this->config, $session->data);

			$data = $this->environment->GetVariables($session->data->UserIdEncrypt);
		} 
		catch (Exception $e) 
		{
			$this->responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
			
		$this->response->Response($this->responseCode, $data);
	}
}