<?php

class Index
{
	private string $_route_config;
	private string $_controller;
	private string $_method;
	private string $_params;
	private object $_config;
	private array $_headers;
	
	public function __construct()
	{
		$this->_route_config = 'Config/Config.json';	
	}

	public function Main()
	{
		try{
			if(!file_exists($this->_route_config)) throw new Exception('CONFIG FILE NOT FOUND', 500);
			$this->_config = json_decode(file_get_contents($this->_route_config));
			$this->StartApp();
			$this->Autoload();
			$this->SetHeaders();
			$this->ValidateToken();
			$this->LoadController();
			$code = 200;
		}
		catch(Exception $ex)
        {
			$response = ['error' => $ex->getMessage()];
            $code = $ex->getCode();
        }
        finally
        {
            $this->Autoload();
            $request = new HttpRequest();
            $request->Response($code, $response);
        }
	}

	public function Init()
	{
		//error_reporting(E_ALL); // Error/Exception engine, always use E_ALL
		//ini_set('ignore_repeated_errors', TRUE); // always use TRUE
		//ini_set('display_errors', TRUE); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment
		//ini_set('log_errors', TRUE); // Error/Exception file logging engine.
	}

	public function StartApp()
	{
		$url = $_GET['url'] ?? '';
		$arrUrl = explode('/', $url);

		$this->_controller = $arrUrl[0];
		$this->_method = $arrUrl[0];
		$this->_params = '';

		// Se obienen los métodos
		if(!empty($arrUrl[1])){
			if($arrUrl[1] != ''){
				$this->_method = $arrUrl[1];
			}
		}

		// Se obtienen los parámetros
		if(!empty($arrUrl[2])){
			$arrGet = [];
			for($i=2; $i < count($arrUrl); $i++){
				$arrayUrl = explode('=', $arrUrl[$i]);
				if(isset($arrayUrl[0]) && isset($arrayUrl[1])){
					$arrGet[$arrayUrl[0]] = $arrayUrl[1];
				}
			}
			$_GET = $arrGet;
		}
	}

	public function Autoload()
	{
		spl_autoload_register(function($class)
		{
			$directories = [
				'Lib/Shared/',
				'Lib/',
				'Models/',
				'Metadata/',
				'Interfaces/'
			];

			foreach($directories as $directory)
				if(file_exists($directory.$class.'.php')) 
					require_once $directory.$class.'.php';
		});
	}

	public function SetHeaders()
	{
		if(!count(getallheaders()) > 0) throw new Exception("ERROR NOT HEADERS", 404);

		$this->_headers = getallheaders();

		if(!isset($this->_headers["token"])) throw new Exception("TOKEN UNDEFINED", 401);

		$token = json_decode(base64_decode(explode(".", $this->_headers["token"])[1]));

		$this->_headers['user_lang'] = $token->data->UserLang;
		$this->_headers['user_type'] = $token->data->UserType;
		$this->_headers['page'] = 1;
	}

	public function ValidateToken()
	{
		$tokenValidate = new JsonWebToken($this->_config);
		$respValidationToken = $tokenValidate->ValidateToken();
		if($respValidationToken['statusCode'] != 200) 
			throw new Exception('INVALID TOKEN',403);
	}

	public function LoadController()
	{
		$pathController = null;
		$controller = null;

		// CASO 1: [Directory_Controller/Controllers]
		if(strpos($this->_controller, '_'))
		{
			list($directory, $controller) = explode('_', $this->_controller);
			$pathController = $directory.'/Controllers/'.$controller.'Controller.php';
			$controller = $controller.'Controller';
		}
		
		// CASO 2: [Core/Controllers]
		if(file_exists('Controllers/'.$this->_controller.'Controller.php'))
		{
			$pathController = 'Controllers/'.$this->_controller.'Controller.php';
			$controller = $this->_controller.'Controller';	
		}
		
		// CASO 3: [Directory/Controllers]
		if(file_exists($this->_controller.'/Controllers/'.$this->_controller.'Controller.php'))
		{
			$pathController = $this->_controller.'/Controllers/'.$this->_controller.'Controller.php';	
			$controller = $this->_controller.'Controller';
		}

		if(is_null($pathController) || is_null($controller)) $this->PageNotFound();
		if(!file_exists($pathController)) $this->PageNotFound();

		// Instancia del controlador
		require_once $pathController;
		$objectController = new $controller($this->_config, $this->_headers);
		
		// Ejecucion del metodo
		if(!method_exists($objectController, $this->_method)) $this->PageNotFound();
		$objectController->{$this->_method}();
	}

	private function PageNotFound($msg = null)
	{
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Allow: GET, POST, PUT, DELETE");
        header('Content-Type: application/json');
        http_response_code(404);
		$response = new stdClass();
		$response->statusCode = 404;
		$response->statusText = 'ERROR';
		$response->description = 'NOT FOUND';
		$response->controller = is_null($this->controller) || is_null($this->method) ? '' : $this->controller.'/'.$this->method;
		$response->result = is_null($msg) ? 'Page not found' : $msg;
		die(json_encode($response));
	}
}

$index = new Index();
$index->Main();