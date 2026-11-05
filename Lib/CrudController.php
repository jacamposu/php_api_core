<?php



class CrudController implements ICrud
{
	protected object $config;
	protected object $metadata;
	protected $navBarOptions;
	protected $table;
	private object $_lang;
	private $_dictionary;
	private $_profile;
	private LoaderResources $_loader;
	private array $_headers;

	function __construct(object $config, object $metadata, array $headers)
	{
		$this->ValidateInputs($headers);
		$this->config = $config;
		$this->metadata = $metadata;
		$this->_headers = $headers;
		$this->_loader = new LoaderResources();	
		$this->_lang = $this->_loader->GetLang($headers['user_lang']);
		$this->_dictionary = $this->_loader->GetDictionary();
		$this->_profile = $this->_loader->GetProfile($headers['user_type'], true);
	}

	public function GetInfoPage()
	{
		$this->navBarOptions = new NavigationBar($this->metadata, $this->_headers);

		return [
			'PageInformation' => [
				'Title' => $this->metadata->title,
                'Subtitle' => $this->metadata->subtitle,
                'IconPage' => $this->metadata->iconPage,
                'MsgHelpGrid' => $this->metadata->msgHelpGrid,
                'MsgHelpForm' => $this->metadata->msgHelpForm,
				'Links' => $this->metadata->AddLinks(),
				'ScriptsHead' => $this->metadata->AddScripts(),
				'ScriptsFooter' => $this->metadata->AddScriptsFooter(),
				'NavBarOptions' => $this->navBarOptions->navbarOptions(true)
			]
		];
	}

	public function GetDataTable(object $result, object $totalRecords, int $start):array
	{
		$this->validateProfile('read');

		$table = new DataTable($this->config, $this->metadata, $this->_headers);

		$pagination = new Pagination($this->config, $this->metadata, $this->_headers, $totalRecords->result->tableRecordsQuantity);

		return [
			'table' => $table->Table($result->result, $totalRecords->result->tableRecordsQuantity, $start),
			'pagination' => $pagination->Pagination()
		];
	}
	
	public function GetDataForm(string $action, object $resultForm, object $list, object $listSaved):array
	{
		$form = new Forms($this->metadata);

		$buttons = $this->ValidateSubmits() ? $this->Submits($action, $this->metadata) : [];

		return [
	    	'Form' => [
				'Title' => $this->metadata->msgTitleForm,
				'Subtitle' => $this->metadata->msgHelpForm,
				'Fields' => $form->Fields($action, $resultForm, $list, $listSaved, $this->_dictionary),
	    		'Buttons' => $buttons
			]
	    ];
	}

	public function ValidateForm(mixed $body):mixed
	{
		$validate = new ValidateForm(["params" =>  $body], $this->_headers);
		$validation = $validate->validateForm();
		if(isset($validation['error']))
			return $validation;

		return null;
	}
	
	public function Add(object $result)
	{	
		if((isset($result->statusCode) && $result->statusCode == 200) && $result->result->InsertedRecords > 0)
			return ["response" => str_replace("{{count}}", $result->result->InsertedRecords, $this->_lang->Crud->recordInserted)];
		
		throw new Exception($this->_lang->Crud->NonInserted,400);
	}

	public function Edit(object $result)
	{
		if($result->statusCode == 200 && $result->result->UpdatedRecords > 0)
			return ["response" => str_replace("{{count}}", $result->result->UpdatedRecords, $this->_lang->Crud->recordUpdated)];
		
		throw new Exception($this->_lang->Crud->NonUpdated,400);
	}

	public function Delete(object $result)
	{
		if($result->statusCode == 200 && $result->result->DeletedRecords > 0)
			return ["response" => str_replace("{{count}}", $result->result->DeletedRecords, $this->_lang->Crud->recordDeleted)];
		
		throw new Exception($this->_lang->Crud->NonDeleted,400);
	}

	public function LogicalDelete(object $result)
	{
		if($result->statusCode == 200 && $result->result->UpdatedRecords > 0)
			return ["response" => str_replace("{{count}}", $result->result->UpdatedRecords, $this->_lang->Crud->recordDeleted)];
		
		throw new Exception($this->_lang->Crud->NonDeleted,400);
	}

	public function oneRecord(object $result)
	{
		if($result->statusCode != 200) throw new Exception($this->_lang->Error->ResultQueryEmpty, $result->statusCode);

		$list = [];

		foreach($result->result->columnName as $column)
		{
			$record['metadata'] = $this->_dictionary->{$column};
			$record['value'] = $result->result->data->{$column};
			array_push($list, $record);
		}

		$button = new Button();
		$button->text = $this->_lang->Button->Close;
		return [
			'List' => [
				'Title' => $this->_lang->Modal->ListView->Title,
				'Data' => $list, 
				'Buttons' => $button->Cancel()
			]
		];
	}

	public function GetDataModalColumns(object $result): array
	{
		$button = new Button();
		$arrayColumns = [];

		foreach ($result->result->columnName as $field) 
		{
			$arrayColumns[] = ['field' => $field, 'desc' => $this->_dictionary->$field->desc];
		}

		return [
			'Modal' => [
				'title' => $this->_lang->Modal->Columns->Title,
				'data' => $arrayColumns,
				'buttons' =>  $button->Cancel($this->_lang->Button->Cancel)
			]
		];
	}

	public function GetDataModalSearch(object $result, object $list): array
	{
		$form  = new Forms($this->metadata);
		$fields['visible'] = $form->Fields('Search', $result, $list,[], $this->_dictionary);
		
		$submits = $this->ValidateSubmits() ? $this->Submits('Search', $this->metadata) : [];

		return [
			'Modal' => [
				'Title' => $this->_lang->Modal->Search->Title,
				'Fields' => $fields,
				'Buttons' =>  $submits
			]
		];
	}

	public function GetDataModalDownloads(object $result):array
	{
		return [
			'Modal' => [
				'Title' => $this->_lang->Modal->Downloads->Title,
				'ChooseFormat' => $this->_lang->Modal->Downloads->ChooseFormat,
				'AboutDownload' => $this->_lang->Modal->Downloads->AboutDownload,
				'AddRemoveColumns' => $this->_lang->Modal->Downloads->AddRemoveColumns,
				'BtnClose' => $this->_lang->Button->Close,
				'BtnSend' => $this->_lang->Button->Download,
				'Options' => [
					[
						'index' => 'excel', 
						'text' => 'Excel', 
						'icon' => 'fa fa-file-excel-o', 
						'colorIcon' => 'text-success', 
						'default' => 'checked'
					],
					[
						'index' => 'pdf', 
						'text' => 'PDF', 
						'icon' => 'fa fa-file-pdf-o', 
						'colorIcon' => 'text-danger', 
						'default' => ''
					],
					[
						'index' => 'txt', 
						'text' => 'TXT', 
						'icon' => 'fa fa-file-text', 
						'colorIcon' => 'text-secondary', 
						'default' => ''
					]
				],
				'Modality' => [
					[
						'index' => 'view', 
						'text' => $this->_lang->Modal->Downloads->ThisView, 
						'default' => 'checked'
					],
					[
						'index' => 'all', 
						'text' => $this->_lang->Modal->Downloads->All, 
						'default' => ''
					]
				],
				'Columns' => $this->GetDataModalColumns($result),
				'Buttons' => []
			]
		];
	}














	/*
	public function LoadMassiveContent()
	{
		
		global  $tableFields;
		$sqlTable = Util::VarPOST('sqlTable');
		$campo_clave = "UseStaId";
		$vectorColumns = $tableFields[$sqlTable];
		$campos_temporales = null;
		foreach ($vectorColumns as $value) {
			$campos_temporales .= "tmp_{$value}, ";
		}
		$campos_temporales = trim($campos_temporales, ', ');

		$fileName = MassiveContent::MoveFileTmpFolder();

		$resultLoad = MassiveContent::LoadDataDB($fileName, $sqlTable, $campos_temporales, $vectorColumns, $campo_clave, 'tmp_UseStaStatusProcess');

		$RespuestaVC = MassiveContent::ValidateContent($sqlTable, $vectorColumns, $resultLoad, $campo_clave);

		MassiveContent::SaveContentBD($resultLoad, $sqlTable, $campos_temporales, $vectorColumns);

	}*/


	public function ValidateProfile(string $action):void
	{		
	    if(!$this->_profile[$this->metadata->idProfile][$action])
	    {
	    	throw new Exception($this->_lang->Validate->RestrictedAccessCreate, 403);
	    }
	}

	private function ValidateInputs(array $headers):void
	{
		if(!isset($headers['page']))
		{
			throw new Exception($this->_lang->Validate->EmptyParams, 403);
		}
	}

	private function ValidateSubmits():bool
	{
		// Botones de acción de formulario dependientes de la perfilación
        if($this->_profile[$this->metadata->idProfile]['create'] || 
            $this->_profile[$this->metadata->idProfile]['update'])
        {
            return true;
        }
		return false;
	}









	public function Submits($action, $metadata)
    {
        $submits = [];

        $PF = $this->ProcessForm($action, $metadata);

        if($metadata->Options()['submits']['enabled'])
        {
            foreach ($metadata->Options()['submits']['options'] as $key => $value)
            {
                if(empty($value['eventJS'])) $value['eventJS'] = $PF['actionConfirm'];

				$btn = new Button();
				$btn->text = $value['valueButton'];
				// $btn->actionJs = $value['eventJS'];
				$btn->icon = $value['icon'];
				$btn->id = $value['nameButton'];
				$btn->type = $PF['typeButton'];
				$btn->mode = "";
                array_push($submits, $btn->Button());
            }
        }
        else
        {
			// $btnCancel = new Button();
			// $btnCancel->text = 'Cancelar';
			// array_push($submits, $btnCancel->Cancel());

			$btn = new Button();
			$btn->text = $PF['buttonName']['message'];
			$btn->keyField = $metadata->key_field;
			// $btn->actionJs = $PF['actionConfirm'];
			$endpoint = $action == 'Edit' ? 'update' : 'add';
			$btn->endpoint = $metadata->Options()['confirm'][$endpoint]['action'];
			$btn->action = $action;
			$btn->mode = "Submit";
			array_push($submits,  $btn->Button());

			$btn = new Button();
			$btn->text = "Cancelar";
			$btn->keyField = $metadata->key_field;
			// $btn->actionJs = $PF['actionConfirm'];
			$endpoint = $action == 'Edit' ? 'update' : 'add';
			$btn->endpoint = $metadata->Options()['confirm'][$endpoint]['action'];
			$btn->action = $action;
			$btn->mode = "Cancel";
			array_push($submits,  $btn->Button());

			
        }
        
        return $submits;
    }


	private function ProcessForm($action, $metadata)
    {
        $typeButton = 'submit';

        switch ($action)
        {
            case 'Edit':
                $buttonName = ['group' => 'Link', 'message' => 'Actualizar'];
                $confirm = $this->MsgConfirm($buttonName, 'update', $metadata);
                break;
            
            case 'Add':
                $buttonName = ['group' => 'Link', 'message' => 'Guardar'];
                $confirm = $this->MsgConfirm($buttonName, 'add', $metadata);
                break;

            case 'Search':
                $buttonName = ['group' => 'Link', 'message' => 'Buscar'];
                $confirm = $this->MsgConfirm($buttonName, 'add', $metadata);
                break;

            default:
                throw new Exception("Action [{$action}] not allowed. File: ".__FILE__. ", Line: ".__LINE__);
                break;
        }

        $actionConfirm = $confirm['actionConfirm'];
        $typeButton = $confirm['typeButton'] ? $confirm['typeButton'] : $typeButton;
 
        return [
            'actionConfirm' => $actionConfirm, 
            'typeButton' => $typeButton, 
            'buttonName' => $buttonName
        ];
    }


	private function MsgConfirm ($buttonName, $action, $metadata)
    {
        $actionConfirm = false;
        $typeButton = '';
        $msg = '';

        if($metadata->Options()['confirm'][$action]['state'])
        {
            $msg = $metadata->Options()['confirm'][$action]['msg'];
            $actionConfirm = true;
            $typeButton = "button";
        }

        return [
            'actionConfirm' => [
                'title' => 'Enviar Formulario',
                'btnSendText' => $buttonName['message'],
                'btnCancelText' => 'Cancelar',
                'messageConfirm' => $msg,
                'confirm' => $actionConfirm
            ],
            'typeButton' => $typeButton
        ];
    }
	
}