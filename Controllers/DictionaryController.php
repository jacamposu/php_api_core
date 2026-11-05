<?php 
/**
* ----------------------------------------------------------
* Dictionary Class Controller
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class DictionaryController extends MainGenericController
{
	private mixed $_body;
	private object $_config;

	public function __construct(object $config, array $headers)
	{
		try 
		{
			parent::__construct($config, $headers, 'Dictionary');
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
		$this->MainFormAdd(["IdFieldType", "IdIcon","IdAlign","IdInputType","IdInputTypeForm"]);
	}

	/* [GET METHOD] */
	public function FormEdit()
	{
		$this->MainFormEdit([''],['']);
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



	public function GenerateDictionary()
	{		
		$headers = new HttpDatabase($this->_config);
		$headers->SetModel('Usp_Generate_Dictionary')
						->SetResoruceModel('SP')
						->SetParams([
							':_DB_NAME' => 'prototipo',
							':_LANGUAGE' => 'Es'
						]);

		$dictionary = json_decode($headers->SP($this->_config), false);

		$metadata = [];
		$metadataRow = [];

		foreach ($dictionary->result as $key => $field) 
		{
			$metadataRow['table'] = $field->TableName;
			$metadataRow['fieldName'] = $field->FieldName;
			$metadataRow['fieldType'] = $field->FieldType;
			$metadataRow['length'] = $field->Length;
			$metadataRow['format'] = $field->Format;
			$metadataRow['align'] = $field->Align;
			$metadataRow['inputType'] = $field->InputTypeGrid;
			$metadataRow['inputTypeForm'] = $field->InputTypeForm;
			$metadataRow['sizeInput'] = $field->SizeInputForm;
			$metadataRow['required'] = $field->Required;
			$metadataRow['unsigned'] = $field->Unsigned;
			$metadataRow['textareaChars'] = $field->TextareaChars;
			$metadataRow['textareaRows'] = $field->TextareaRows;
			$metadataRow['icon'] = $field->Icon;
			$metadataRow['disabled'] = $field->Disabled;
			$metadataRow['readonly'] = $field->ReadOnly;
			$metadataRow['dependentList'] = $field->DependentList;
			// $metadataRow['dependentFrom'] = $field->DependentFrom;
			$metadataRow['pattern'] = $field->Pattern;
			$metadataRow['validationForm'] = $field->ValidationForm;
			$metadataRow['desc'] = $field->Description;
			$metadataRow['helpField'] = $field->Help;
			
			$metadata[$field->FieldName] = $metadataRow;
		}

		file_put_contents('D:/DEV/ApiCore/Config/Dictionary.json', json_encode($metadata));
				
		$this->_response->Response(200, $metadata);

	}
}