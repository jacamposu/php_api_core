<?php 
/**
* ----------------------------------------------------------
* Users Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class UsersController extends MainGenericController
{
	private mixed $_body;
	private object $_config;

	public function __construct(object $config, array $headers)
	{
		try 
		{
			parent::__construct($config, $headers, 'Users');
			$this->_body = json_decode(file_get_contents("php://input"));
			$this->_config = $config;
		}
		catch (Exception $e) 
		{
			$this->_response->Response($e->getCode(), $e->getMessage());
		}
	}

	/* [GET METHOD] */
	public function InfoPage()
	{
		$this->MainInfoPage();
	}

	/* [GET METHOD] */
	public function ReturnDataTable()
	{
		$this->mainReturnDataTable();
	}

	/* [GET METHOD] */
	public function FormAdd()
	{
		$this->MainFormAdd(["IdUserTypeUser", "IdUserStatus","IdCountryUser","IdIdentificationType"]);
	}

	/* [GET METHOD] */
	public function FormEdit()
	{
		$this->MainFormEdit(["IdUserTypeUser", "IdUserStatus","IdCountryUser","IdIdentificationType"], ["IdCity", "IdStateUser"]);
	}

	/* [GET METHOD] */
	public function ListView()
	{
		$this->MainListView();
	}

	/* [GET METHOD] */
	public function ModalColumns()
	{
		$this->MainModalColumns();
	}

	/* [POST METHOD] */
	public function AddUser()
	{
		$this->_body->UsrId = strtolower(substr($this->_body->UsrFirtsName, 0, 1).substr($this->_body->UsrSecondName, 0, 1).$this->_body->UsrSurname);
		$this->_body->IdLang = $this->_config->app->lang;
		$this->_body->IdUserStatus = 'A';
		$this->_body->IdAppStyle = "TB";
		$this->_body->UsrFailedAttempts = 0;
		$this->_body->UsrLastIp = Util::GetIP();
		$this->_body->UsrEncryptedId = Util::Encrypt($this->_body->UsrId);
		$passwordNewUser = uniqid();
		$this->_body->UsrPassword = Util::Encrypt(ScapeStr::Clean(($this->_config->security->tokenPw.$passwordNewUser)));
		$this->_body->UsrPhoto = "profile.png";
		$this->_body->UsrChangePasswordRequired = 1;

		$message = "\$message = <<< HTML\n";
		$message .= file_get_contents("MailTemplates/Es/UserCreated.html");
		$message .= "\nHTML;\n";
		eval($message);
		file_put_contents("C:\\xampp\\htdocs\\Desarrollo\\UserCreated.html", $message);

		$this->MainAddRecord($this->_body);
	}

	/* [PUT METHOD] */
	public function EditUser()
	{
		$this->MainEditRecord($this->_body);
	}

	/* [DELETE METHOD] */
	public function DeleteUser()
	{
		$this->MainDeleteRecord();
	}

	/* [GET METHOD] */
	public function ModalSearch()
	{
		$this->MainModalSearch();
	}

	/* [GET METHOD] */
	public function ModalMassiveContent()
	{
		$this->MainModalMassiveContent();
	}

	/* [GET METHOD] */
	public function ModalDownloads()
	{
		$this->MainModalDownloads();
	}

	/* [GET METHOD] */
	public function DownloadReport()
	{
		$this->MainDownloadReport();
	}

	/* [GET METHOD] */
	public function GetValuesList()
	{	
		$this->MainGetList();
	}
}