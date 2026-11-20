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

			/* Obtener la lista de perfiles */
			$headers->SetModel('UserTypesModel')->SetResoruceModel('Profiles')->SetWithoutLimit();
			$profiles = $headers->Get();

			if(is_null($profiles)) 
				throw new Exception("No existen perfiles para generar los archivos", 400);
		
			$profiles = $profiles->result->data;

			/* obtener la data de menu */
			$headers->SetModel('MenuModel')->SetResoruceModel('GenerateMenu')->SetWithoutLimit();
			$menuList = $headers->Get();

			if(is_null($menuList)) 
				throw new Exception("No se ha generado información para generar los archivos", 400);

			$menuList = $menuList->result->data;

			foreach ($profiles as $profile)
			{
				$menu = $this->BuilFileMenu($menuList, $this->_config->url->app_front, $profile->UtId);
				$fileName = $this->_config->path->pathFront.'/Templates/Menu_'.$profile->UtId.'.html';
				file_put_contents($fileName, $menu);
			}

			$this->_response->Response(200, [
				'title' => 'Archivo generado exitosamente',
				'text' => 'Se han generado el archivo Menu.htm correctamente.',
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

	private function BuilFileMenu($menuList, $ulrProject, $profileId)
	{
		$div = '';

		foreach ($menuList as $key => $menu) {				
			if($menu->MeMenuLevel == 1 && $menu->MeMenuType == 1 && $menu->IdUserTypeProfile == $profileId){
				$submenuOptions = ''; 
				$submenuOptionsFloat = '';

				foreach ($menuList as $keyS => $submenu){
					if($submenu->MeMenuLevel == 2 && $submenu->IdParentMenu == $menu->IdParentMenu){
						$url = $ulrProject.$submenu->MeUrl;
						$submenuOptions .= '<a href="'.$url.'">';
						// $submenuOptions .= '<i class="'.$submenu->IcFontAwesome.'"></i> ';
						$submenuOptions .= $submenu->MeName;
						$submenuOptions .= '</a>';
						$submenuOptionsFloat .= '<a href="'.$url.'" data-title="Cambios BD" style="text-decoration: none;">';
						// $submenuOptionsFloat .= '<i class="'.$submenu->IcFontAwesome.'"></i> ';
						$submenuOptionsFloat .= $submenu->MeName;
						$submenuOptionsFloat .= '</a>';
					}
				}

				$div .= '<div class="menu-item">';
				$div .= '<div class="menu-link">';
				$div .= '<div class="menu-content">';
				$div .= '<i class="'.$menu->IcFontAwesome.'"></i>';
				$div .= '<span>'.$menu->MeName.'</span>';
				$div .= '</div>';
				$div .= '<i class="fas fa-chevron-down arrow"></i>';
				$div .= '</div>';
				$div .= '<div class="submenu">';
				$div .= $submenuOptions;
				$div .= '</div>';
				$div .= '<div class="floating-submenu">';
				$div .= '<div class="floating-title">'.$menu->MeName.'</div>';
				$div .= $submenuOptionsFloat;
				$div .= '</div>';
				$div .= '</div>';
			}
		}

		return $div;
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