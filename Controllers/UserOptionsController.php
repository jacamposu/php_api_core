<?php 
/**
* ----------------------------------------------------------
* Users Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class UserOptionsController extends MainGenericController
{
    private int $_responseCode;
	private $_config;
	private $_session;
	private $_headersDB;
	protected $metadata;
	private array $_rToken;
	private $_crud;

    public function __construct($config, array $headers)
    {
		try
		{
			parent::__construct($config, $headers, 'UserOptions');
			$this->_config = $config;
			$this->metadata = new UserOptionsMetadata($headers);
			$tokenValidate = new JsonWebToken($this->_config);
			$this->_rToken = $tokenValidate->DecodeToken();
			$this->_headersDB = $headers;
			$this->_crud = new CrudController($config, $this->metadata, $headers);
		}
		catch(Exception $e)
		{
			$this->_response->Response($e->getCode(), $e->getMessage());
		}
    }

    public function ProfileUserView()
    {
        try
		{
			$this->_response->VerifyMethod("GET");

			$params = [':UsrEncryptedId' => $this->_rToken['response']['data']['UserIdEncrypt']];
			$this->_headersDB = new HttpDatabase($this->_config);
			$this->_headersDB->SetModel('UserOptionsModel')
					->SetResoruceModel('ProfileUserView')
					->SetParams($params);
			$data = $this->_headersDB->Get();

			$this->_responseCode = 200;
			$responseText = [
				'page' => 'profile', 
				'data' => $data->result
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}

		$this->_response->Response($this->_responseCode, $responseText);
    }

	public function UpdatePasswordForm()
    {
        try 
		{
			$this->_response->VerifyMethod("GET");
			$meta = new ChangePasswordMetadata();
			$this->_responseCode = 200;
			$responseText = $meta->Form();
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}

		$this->_response->Response($this->_responseCode, $responseText);
    }

	public function ProfileUserEdit()
    {
		
        try 
		{
			$this->_response->VerifyMethod("GET");

			// $this->ValidateHeader('id');
			
			$params[':idUser'] = $this->_headersDB['id']; //
			$query = new HttpDatabase($this->_config);
			$query->SetModel('UserOptionsModel')->SetResoruceModel('ProfileUserEdit')->SetParams($params);
			$resultForm = $query->Get();

			
			// $params = ["fieldsList" => $fields];
			// $headers2 = new HttpDatabase($this->_config);
			// $headers2->SetModel($this->_model)->SetResoruceModel('list')->SetResourceGeneric('all')->SetParams($params);
			// $resultList = $headers2->Get();
			
			// $params = [':keyfield' => $this->_headers['id'], "fieldsList" => $fields2];		
			// $headers3 = new HttpDatabase($this->_config);
			// $headers3->SetModel($this->_model)->SetResoruceModel('list')->SetResourceGeneric('save')->SetParams($params);
			// $resultListSaved = $headers3->Get();
			
			
			$data = $this->_crud->GetDataForm('Edit', $resultForm, new stdClass(), new stdClass());

			$this->_responseCode = 200;
			$responseText = [
				'page' => 'profileEdit', 
				'data' => $data->result
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}

		$this->_response->Response($this->_responseCode, $responseText);
    }




	
	public function ViewInfoApplication()
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$this->_responseCode = 200;
			$responseText = [
				'page' => 'InfoApp', 
				'data' => $this->metadata->InfoApplication()
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}
		$this->_response->Response($this->_responseCode, $responseText);
	}

	

	public function Customize()
    {
        try 
		{
			$this->_response->VerifyMethod("GET");
			// $headers = new HeadersDatabase();
			// $headers->SetModel($this->_model)->SetResoruceModel('All');

			$this->_responseCode = 200;
			$responseText = [
				'page' => 'customize', 
				'data' => "Personalizar aplicación - en construcción"
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}
		$this->_response->Response($this->_responseCode, $responseText);
    }

	public function SetLangUser()
    {
        try 
		{
			$this->_response->VerifyMethod("GET");
			// $headers = new HeadersDatabase();
			// $headers->SetModel($this->_model)->SetResoruceModel('All');
			$this->_responseCode = 200;
			$responseText = [
				'page' => 'langUser', 
				'data' => "cambiar idioma - en construcción"
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}
		$this->_response->Response($this->_responseCode, $responseText);
    }

	public function AboutMe()
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$this->_responseCode = 200;
			$responseText = [
				'page' => 'InfoApp', 
				'data' => ''
			];


			$this->_responseCode = 200;
			$responseText = [
				'page' => 'aboutMe', 
				'data' => "cambiar idioma - en construcción"
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}
		$this->_response->Response($this->_responseCode, $responseText);
	}

    public function UpdatePassword()
    {
        try 
		{
			$this->_response->VerifyMethod("POST");
			
			$body = [':idUser' => 'admin'];
			$this->_headersDB = new HttpDatabase($this->_config);
			$this->_headersDB->SetModel('UserModel')
					->SetResoruceModel('ChangePassword');
			$data = $this->_headersDB->Post($body);

			$this->_responseCode = 200;
			$responseText = [
				'page' => 'InfoApp', 
				'data' => $data->result
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}

		$this->_response->Response($this->_responseCode, $responseText);
    }

    public function SetPassUsers()
    {
        try 
		{
			$this->_response->VerifyMethod("GET");
			// $headers = new HeadersDatabase();
			// $headers->SetModel($this->_model)->SetResoruceModel('All');
			$responseText = "";
            throw new Exception("Opción no permitida para éste usuario - en construcción", 404);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$responseText = [
                "error" => true,
                "statusCode" => $e->getCode(),
                "message" => $e->getMessage(),
				"title" => '$this->_lang->Login->TitleWrongLogin'
            ];
		}
		$this->_response->Response($this->_responseCode, $responseText);
	}

	public function ChangeMyPhoto()
	{
		$this->_response->Response(200, 'La foto ha sido cambiada');
	}

	public function ChangeMyProfile()
	{
		$this->_response->Response(200, 'El perfil ha sido cambiado');
	}
}