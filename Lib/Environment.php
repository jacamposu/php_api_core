<?php 

class Environment
{
	private object $_config;
	private array $_session;
	private $_headersDB;
	private LoaderResources $_loader;

	public function __construct($config, $headers)
	{
		$this->_config = $config;
		$this->_session = [
			'app' => $this->_config->app->name, 
			'lang' => $headers->UserLang, 
			'type' => $headers->UserType,
			'user' => $headers->UseId,
			'host' => 'localhost' /** redefinir desde el front */
		];
		$this->_loader = new LoaderResources();
	}

	public function GetVariables(string $UserEncrypted):array
	{
		$params = [':UseEncryptedId' => $UserEncrypted];
		$this->_headersDB = new HttpDatabase($this->_config);
		$this->_headersDB->SetModel('UsersModel')
				->SetResoruceModel('GetUserType_Photo')
				->SetParams($params);
		$result = $this->_headersDB->Get($this->_config, $this->_headersDB, $this->_session);

		if(empty($result)){
			throw new Exception("falla en la consulta de GetUserType_Photo", 404);
		}

		$userType = $result->result->data->IdUserTypeUser;
		$UsePhoto = $result->result->data->UsrPhoto;


		return [
			'pathPhoto' => !empty($UsePhoto) ? $UsePhoto : $this->_config->app->defaultPhoto
		]; 
	}
}