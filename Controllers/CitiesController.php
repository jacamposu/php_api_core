<?php 
/**
* ----------------------------------------------------------
* Cities Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class CitiesController extends MainGenericController
{
	private mixed $_body;
	private object $_config;

	public function __construct(object $config, array $headers)
	{
		try 
		{
			parent::__construct($config, $headers, 'Cities');
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
		$this->MainFormAdd(['IdStateCity']);
	}

	/* [GET METHOD] */
	public function FormEdit()
	{
		$this->MainFormEdit(['IdStateCity'], ['IdStateCity']);
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
	public function Add()
	{
		$this->MainAddRecord($this->_body);
	}

	/* [PUT METHOD] */
	public function Edit()
	{
		$this->MainEditRecord($this->_body);
	}

	/* [DELETE METHOD] */
	public function Delete()
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


	// private function VerifyDQP($idUser)
	// {
	// 	$UseDatId = $this->model->getUseDatId($idUser);
	// 	$response = [];
	// 	// Fuerza el direccionamiento a la interfaz de actualización de los datos personales:
	// 	$UseDatCellphone = $this->model->getUseDatCellphone($idUser);
	// 	if(empty($UseDatCellphone)){
	// 		return [
	// 			"status" => "error",
	// 			"url" => "/Users/ProfileUser/id={$UseDatId}"
	// 		];
	// 	}

	// 	// Fuerza el direccionamiento a la interfaz de pregunta de seguridad de recuperación de contraseña:
	// 	$QuestionId = $this->model->getQuestionId($idUser);
	// 	if(empty($QuestionId)){
	// 		return [
	// 			"status" => "error",
	// 			"url" => "/Users_SecurityQuestion/FormAdd/{$idUser}"
	// 		];
	// 	}

	// 	// Fuerza el direccionamiento a la interfaz de cambio de fotografía
	// 	$UsePhoto = $this->model->getUsePhoto($idUser);
	// 	if(empty($UsePhoto)){
	// 		return [
	// 			"status" => "error",
	// 			"url" => "/User/ChangePhoto"
	// 		];
	// 	}
	// }
}