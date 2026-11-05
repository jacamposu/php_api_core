<?php
/**
* ----------------------------------------------------------
* UserTypes Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class UserTypesMetadata extends Metadata
{
	private $_lang;
	private $_icon;

	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Users;
		$this->title = $this->_lang->Customize->UserTypes->Title;
		$this->subtitle = $this->_lang->Customize->UserTypes->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->UserTypes->MsgHelpGrid;
		$this->msgTitleForm = $this->_lang->Customize->UserTypes->MsgTitleForm;
		$this->msgHelpForm = $this->_lang->Customize->UserTypes->MsgHelpForm;
		$this->num_page = $headers['page'];
		$this->urlPage = 'UserTypes';
		$this->bannerForm = '';
		$this->idProfile = '60a824c6cbcdc';
		$this->main_table = 'usertypes';
		$this->key_field = 'UseTypId';
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
					->SetTitle($this->_lang->Customize->UserTypes->CreateDesc ?? $this->_lang->Customize->UserTypes->Create)
					->SetButtonName($this->_lang->Customize->UserTypes->Create)
			],
			'edit' => [
				0 => $btn1
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('action.FormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->UserTypes->UpdateDesc ?? $this->_lang->Customize->UserTypes->Update)
					->SetButtonName($this->_lang->Customize->UserTypes->Update)
					->SetColor('text-info')
					->SetGroup(''),
				1 => $btn2
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('action.ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->UserTypes->ReadDesc ?? $this->_lang->Customize->UserTypes->Read)
					->SetButtonName($this->_lang->Customize->UserTypes->Read)
					->SetColor('text-success')
					->SetGroup(''),
				2 => $btn3
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
						'title' => $this->_lang->Customize->UserTypes->Delete,
				 		'text' => $this->_lang->Warning->Delete
						])
					->SetTitle($this->_lang->Customize->UserTypes->DeleteDesc ?? $this->_lang->Customize->UserTypes->Delete)
					->SetButtonName($this->_lang->Customize->UserTypes->Delete)
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
		return [$this->key_field];  
	}
}