<?php
/**
* ----------------------------------------------------------
* Dictionary Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class DictionaryMetadata extends Metadata
{
	private $_lang;
	private $_icon;


	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Book;
		$this->title = $this->_lang->Customize->Dictionary->Title;
		$this->subtitle = $this->_lang->Customize->Dictionary->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Dictionary->MsgHelpGrid;
		$this->msgTitleForm = $this->_lang->Customize->Dictionary->MsgTitleForm;
		$this->msgHelpForm = $this->_lang->Customize->Dictionary->MsgHelpForm;
		$this->num_page = $headers['page'];
		$this->urlPage = 'Dictionary';
		$this->bannerForm = '';
		$this->idProfile = '60a82158cc3d1';
		$this->main_table = 'dictionary';
		$this->key_field = 'DicId';
	}

	public function Options()
	{
		$btn = new ButtonOptionsPage();
		$btn1 = new ButtonOptionsPage();
		$btn2 = new ButtonOptionsPage();
		$btn3 = new ButtonOptionsPage();
		$btn4 = new ButtonOptionsPage();

		return [
			'optionsPage' => [
				// 0 => $btn
				// 	->SetType('slider')
				// 	->SetIcon($this->_icon->Add)
				// 	->SetEvent('action.FormAdd')
				// 	->SetParams(['url' => $this->urlPage.'/FormAdd/'])
				// 	->SetTitle($this->_lang->Customize->Dictionary->CreateDesc ?? $this->_lang->Customize->Dictionary->Create)
				// 	->SetButtonName($this->_lang->Customize->Dictionary->Create),
				0 => $btn1
					->SetType('button')
					->SetIcon($this->_icon->Cogs)
					->SetEvent('action.GenerateDictionary')
					->SetParams([
						'url' => $this->urlPage.'/GenerateDictionary/',
						'text' => $this->_lang->Customize->Dictionary->GenerarFileDictionaryText,
						'btnYes' => $this->_lang->Button->Generate,
						'btnCancel' => $this->_lang->Button->Cancel
						])
					->SetTitle($this->_lang->Customize->Dictionary->GenerateFileText)
					->SetButtonName($this->_lang->Customize->Dictionary->GenerateFile)
			],
			'edit' => [
				0 => $btn2
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('action.FormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->Dictionary->UpdateDesc ?? $this->_lang->Customize->Dictionary->Update)
					->SetButtonName($this->_lang->Customize->Dictionary->Update)
					->SetColor('text-info')
					->SetGroup(''),
				1 => $btn3
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->Dictionary->ReadDesc ?? $this->_lang->Customize->Dictionary->Read)
					->SetButtonName($this->_lang->Customize->Dictionary->Read)
					->SetColor('text-success')
					->SetGroup(''),
				2 => $btn4
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
				 		'title' => $this->_lang->Customize->Dictionary->Delete,
				 		'text' => $this->_lang->Warning->Delete
						])
					->SetTitle($this->_lang->Customize->Dictionary->DeleteDesc ?? $this->_lang->Customize->Dictionary->Delete)
					->SetButtonName($this->_lang->Customize->Dictionary->Delete)
					->SetColor('text-danger')
					->SetGroup('')
			],
			'confirm' => [
				'add' => [
					'action' => $this->urlPage.'/SaveForm/',
					'state' => true, 
					'msg' => $this->_lang->Warning->Create
				],
				'update' => [
					'action' => $this->urlPage.'/UpdateForm/',
					'state' => true, 
					'msg' => $this->_lang->Warning->Update
				]
			],
			'massiveContent' => [
				'enabled' => true,
				'path' => '',
				'title' => 'Import content',
				'msg' => 'The correct column order is (image, name*, code*, type*, brand, category*, unit_code*, cost*, price*, product_details, variant_name, item_code, additional_price) and you must follow this.<br><br>To display Image it must be stored in public/images/product directory. Image name must be same as product name'
			],
			'submits' => [
				'enabled' => false,
				"options" => []
			],
			'badges' => [
				'enabled' => true,
				'options' => [
					'DicFieldType' => [
						'Bool' => ['TipoBadge' => 'badge badge-success'],
						'Char' => ['TipoBadge' => 'badge badge-info'],
						'Date' => ['TipoBadge' => 'badge badge-warning'],
						'Datetime' => ['TipoBadge' => 'badge badge-warning'],
						'Float' => ['TipoBadge' => 'badge badge-primary'],
						'Integer' => ['TipoBadge' => 'badge badge-secondary'],
						'Longtext' => ['TipoBadge' => 'badge badge-dark'],
						'Mediumint' => ['TipoBadge' => 'badge badge-secondary'],
						'Smallint' => ['TipoBadge' => 'badge badge-secondary'],
						'Varchar' => ['TipoBadge' => 'badge badge-info'],
					],
					'DicInputType' => [
						'Default' => ['TipoBadge' => 'badge badge-secondary'],
						'Badge' => ['TipoBadge' => 'badge badge-info'],
						'Password' => ['TipoBadge' => 'badge badge-dark'],
						'Checkbox' => ['TipoBadge' => 'badge badge-success'],
						'Font' => ['TipoBadge' => 'badge badge-danger'],
						'FontFile' => ['TipoBadge' => 'badge badge-danger'],
						'Image' => ['TipoBadge' => 'badge badge-danger'],
						'Link' => ['TipoBadge' => 'badge badge-primary'],
					],
					'DicInputTypeForm' => [
						'Checkbox' => ['TipoBadge' => 'badge badge-success'],
						'Clave' => ['TipoBadge' => 'badge badge-danger'],
						'Date' => ['TipoBadge' => 'badge badge-warning'],
						'Datetime' => ['TipoBadge' => 'badge badge-warning'],
						'File' => ['TipoBadge' => 'badge badge-secondary'],
						'Hidden' => ['TipoBadge' => 'badge badge-danger'],
						'Number' => ['TipoBadge' => 'badge badge-secondary'],
						'Password' => ['TipoBadge' => 'badge badge-dark'],
						'Radio' => ['TipoBadge' => 'badge badge-success'],
						'Select' => ['TipoBadge' => 'badge badge-primary'],
						'SelectMultiple' => ['TipoBadge' => 'badge badge-primary'],
						'Textarea' => ['TipoBadge' => 'badge badge-dark'],
						'TextareaEdit' => ['TipoBadge' => 'badge badge-dark'],
						'Text' => ['TipoBadge' => 'badge badge-info'],
					]
				]
			],
			'multiChecks' => [
				'enabled' => false,
				'options' => []	
			],
			'hideFieldTable' => [],
			'hideFieldForm' => ['DicId'],
			'optionsGrid' => [
				'showAll' => true, // Btn recargar p�gina
				'showColumns' => true, // Ver/Ocultar columnas
				'searchPage' => true, // Buscador
				'massiveContent' => true, // Carga masiva
				'downloadsOptions' => true // Descargas
			],
			'links' => [
				'enabled' => false,
				'options' => []
			]
		];
	}

	public function AddScripts()
	{ 
		return []; 
	}

	public function AddScriptsFooter()
	{ 
		return []; 
	}

	public function AddLinks()
	{ 
		return []; 
	}

	public function HiddenFields()
	{ 
		return []; 
	}
}