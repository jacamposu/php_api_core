<?php
/**
* ----------------------------------------------------------
* Cities Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class CitiesMetadata extends Metadata
{
	private $_lang;
	private $_icon;

	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Americas;
		$this->title = $this->_lang->Customize->Cities->Title;
		$this->subtitle = $this->_lang->Customize->Cities->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Cities->MsgHelpGrid;
		$this->msgTitleForm = $this->_lang->Customize->Cities->MsgTitleForm;
		$this->msgHelpForm = $this->_lang->Customize->Cities->MsgHelpForm;
		$this->num_page = $headers['page'];
		$this->urlPage = 'Cities';
		$this->bannerForm = '';
		$this->idProfile = '60a8122856573'; // Dato proveniente del id menu
		$this->main_table = 'cities';
		$this->key_field = 'CiId';
	}


	public function Options()
	{
		$btn = new ButtonOptionsPage();
		$btn1 = new ButtonOptionsPage();
		$btn2 = new ButtonOptionsPage();
		$btn3 = new ButtonOptionsPage();

		return [
			'optionsPage' => [
				0 => $btn
					->SetType('slider')
					->SetIcon($this->_icon->Add)
					->SetEvent('action.FormAdd')
					->SetParams(['url' => $this->urlPage.'/FormAdd/'])
					->SetTitle($this->_lang->Customize->Cities->CreateDesc ?? $this->_lang->Customize->Cities->Create)
					->SetButtonName($this->_lang->Customize->Cities->Create)
			],
			'edit' => [
				0 => $btn1
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('action.FormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->Cities->UpdateDesc ?? $this->_lang->Customize->Cities->Update)
					->SetButtonName($this->_lang->Customize->Cities->Update)
					->SetColor('text-primary')
					->SetGroup(''),
				1 => $btn2
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('action.ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->Cities->ReadDesc ?? $this->_lang->Customize->Cities->Read)
					->SetButtonName($this->_lang->Customize->Cities->Read)
					->SetColor('text-primary')
					->SetGroup(''),
				2 => $btn3
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('action.Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
				 		'title' => $this->_lang->Customize->Cities->DeleteDesc ?? $this->_lang->Customize->Cities->Delete,
				 		'text' => $this->_lang->Warning->Delete
						])
					->SetTitle($this->_lang->Customize->Cities->DeleteDesc ?? $this->_lang->Customize->Cities->Delete)
					->SetButtonName($this->_lang->Customize->Cities->Delete)
					->SetColor('text-danger')
					->SetGroup('')
			],
			'confirm' => [
				'add' => [
					'action' => $this->urlPage.'/Add/',
					'state' => true, 
					'msg' => $this->_lang->Warning->Create
				],
				'update' => [
					'action' => $this->urlPage.'/Edit/',
					'state' => true, 
					'msg' => $this->_lang->Warning->Update
				]
			],
			'massiveContent' => [
				'enabled' => false,
				'path' => ''
			],
			'submits' => [
				'enabled' => false,
				"options" => []
			],
			'badges' => [
				'enabled' => false,
				'options' => []
			],
			'multiChecks' => [
				'enabled' => false,
				'options' => []	
			],
			'hideFieldTable' => [],
			'hideFieldForm' => [],
			'optionsGrid' => [
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