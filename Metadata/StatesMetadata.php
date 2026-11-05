<?php
/**
* ----------------------------------------------------------
* Align Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class StatesMetadata extends Metadata
{
	private $_lang;
	private $_icon;
	private $_table;

	public function __construct(array $headers)
	{
		$this->_table = 'States';
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Americas;
		$this->title = $this->_lang->Customize->{$this->_table}->Title;
		$this->subtitle = $this->_lang->Customize->{$this->_table}->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->{$this->_table}->MsgHelpGrid;
		$this->msgTitleForm = $this->_lang->Customize->{$this->_table}->MsgTitleForm;
		$this->msgHelpForm = $this->_lang->Customize->{$this->_table}->MsgHelpForm;
		$this->num_page = $headers['page'];
        $this->urlPage = $this->_table;
		$this->bannerForm = '';
		$this->idProfile = '60a8111d6dd10';
		$this->main_table = $this->_table;
		$this->key_field = 'StId';
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
					->SetTitle($this->_lang->Customize->{$this->_table}->CreateDesc ?? $this->_lang->Customize->{$this->_table}->Create)
					->SetButtonName($this->_lang->Customize->{$this->_table}->Create)
			],
			'edit' => [
				0 => $btn1
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('action.FormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->{$this->_table}->UpdateDesc ?? $this->_lang->Customize->{$this->_table}->Update)
					->SetButtonName($this->_lang->Customize->{$this->_table}->Update)
					->SetColor('text-info')
					->SetGroup(''),
				1 => $btn2
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('action.ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->{$this->_table}->ReadDesc ?? $this->_lang->Customize->{$this->_table}->Read)
					->SetButtonName($this->_lang->Customize->{$this->_table}->Read)
					->SetColor('text-success')
					->SetGroup(''),
				2 => $btn3
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('action.Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
						'title' => $this->_lang->Customize->{$this->_table}->Delete,
				 		'text' => $this->_lang->Warning->Delete
						])
					->SetTitle($this->_lang->Customize->{$this->_table}->DeleteDesc ?? $this->_lang->Customize->{$this->_table}->Delete)
					->SetButtonName($this->_lang->Customize->{$this->_table}->Delete)
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
		return [$this->key_field];  
	}
}