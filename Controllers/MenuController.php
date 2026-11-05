<?php 
/**
* ----------------------------------------------------------
* Menu Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class MenuController extends MainGenericController
{
	private mixed $_body;
	private object $_config;

	public function __construct(object $config, array $headers)
	{
		try 
		{
			parent::__construct($config, $headers, 'Menu');
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
		$this->MainFormAdd();
	}

	/* [GET METHOD] */
	public function FormEdit()
	{
		$this->MainFormEdit();
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

	public function GenerateMenu()
	{
		try
		{
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel('MenuModel')->SetResoruceModel('GenerateMenu')->SetWithoutLimit();
			$totalRecords = $headers->Get();
			if(is_null($totalRecords)) throw new Exception("No se ha generado información para generar los archivos", 400);
			$menuList = $totalRecords->result->data;
			$fileName = $this->_config->path->pathApp.'/Config/Lang/Es/Menu.json';
			file_put_contents($fileName, json_encode($menuList));

			$this->_response->Response(200, [
				'title' => 'Archivo generado exitosamente',
				'text' => 'Se han generado los archivos: MenuLevel1.json, MenuLevel2.json, MenuLevel3.json, para los proyectos: ApirCore y Prototipo',
				'icon' => 'success'
			]);
		}catch(Exception $ex)
		{
			$this->_response->Response(200, [
				'title' => 'Se ha producido un error',
				'text' => $ex->getMessage(),
				'icon' => 'error'
			]);
		}
	}

	// public function DeleteMenu()
	// {
	// 	$recordEdit = AppUtil::VarGet('recordEdit');
	// 	switch (APP_DELETE_TYPE) {
	// 		case 'logical':
	// 			// borrado logico de perfiles
	// 			$sqlProfile = "UPDATE profiles SET deleted_at = ? WHERE ProMenu = '{$recordEdit}'";
	// 			$values = [date('Y-m-d H:i:s')];
	// 			AppQuery::Update($sqlProfile, $values, 'profiles');

	// 			// borrado logico de opciones de menu
	// 			$sqlMenu = "UPDATE menu SET deleted_at = ? WHERE MenId = '{$recordEdit}'";
	// 			$values = [date('Y-m-d H:i:s')];
	// 			AppQuery::Update($sqlMenu, $values, 'menu');
	// 			break;
			
	// 		default:
	// 			// borrado fisico de perfiles
	// 			$sqlProfile = "DELETE FROM profiles WHERE ProMenu = '{$recordEdit}'";
	// 			AppQuery::Delete($sqlProfile, 'profiles');

	// 			// borrado fisico de opciones de menu
	// 			$sqlMenu = "DELETE FROM menu WHERE MenId = '{$recordEdit}'";
	// 			AppQuery::Delete($sqlMenu, 'menu');
	// 			break;
	// 	}
		
	// 	$idPage = AppToken::Get($_SESSION['appSessionUseId']);
	// 	$idPage = APP_ENABLED_CSRF_TOKEN ? "?idPage=".$idPage : null;
	// 	appAlert(21, $infoPage['urlPage'].$idPage, $langResource['Success']['DeleteMenuProfiles']);
	// }


	// public function AddMenu()
	// {
	// 	$_POST['MenId'] = substr(uniqid(), 0, 13);

	// 	// Obtiene el n�mero de orden siguiente seg�n el grupo al que se vaya asignar
	// 	$sql = "SELECT max(MenOrder) FROM menu where MenParentMenu = '{$_POST['MenParentMenu']}' AND MenMenuType={$_POST['MenMenuType']}";
	// 	$_POST['MenOrder'] = AppQuery::Value($sql)+1;

	// 	// Estado del submen�
	// 	$_POST['MenState'] = isset($_POST['MenState']) ? $_POST['MenState'] : 1;

	// 	$controller->add($action);
	// }


}