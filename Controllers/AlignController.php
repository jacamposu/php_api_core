<?php 
/**
* ----------------------------------------------------------
* Align Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class AlignController extends MainGenericController
{
	private mixed $_body;
	private object $_config;

	public function __construct(object $config, array $headers)
	{
		try 
		{
			parent::__construct($config, $headers, 'Align');
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
}