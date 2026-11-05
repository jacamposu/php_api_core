<?php 
/**
* ----------------------------------------------------------
* Controller class Repository
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class IconsController extends MainGenericController
{
	private mixed $_body;
	private object $_config;

	public function __construct(object $config, array $headers)
	{
		try 
		{
			parent::__construct($config, $headers, 'Icons');
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


	public function GenerateIcons()
	{
		$headers = new HttpDatabase($this->_config);
		$headers->SetModel('IconsModel')->SetResoruceModel('GenerateIcons');
		$totalRecords = $headers->Get();
		$icons = $totalRecords->result->data;

		$iconList = [];
		foreach($icons as $icon)
		{
			$iconList[$icon->IcId] = $icon->IcFontAwesome;
		}

		file_put_contents($this->_config->path->pathApp.'/Config/IconsPrueba.json', json_encode($iconList));
		file_put_contents($this->_config->path->pathFront.'/Config/IconsPrueba.json', json_encode($iconList));

		$this->_response->Response(200, [
			'title' => 'Archivo generado exitosamente',
			'text' => 'Se ha generado el archivo Icons.json para los proyectos: ApirCore y Prototipo',
			'icon' => 'success'
		]);
	}
}