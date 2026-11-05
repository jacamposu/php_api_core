<?php

class IconsMetadata extends Metadata
{
	private $_lang;
	private $_icon;

	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Icons;
		$this->title = $this->_lang->Customize->Icons->Title;
		$this->subtitle = $this->_lang->Customize->Icons->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Icons->MsgHelpGrid;
		$this->msgHelpForm = $this->_lang->Customize->Icons->msgHelpForm;
		$this->num_page = $headers['page'];
		$this->urlPage = 'Icons';
		$this->bannerForm = '';
		$this->idProfile = '60a81f49b595a'; // Dato proveniente del id menu
		$this->main_table = 'icons';
		$this->key_field = 'IcId';
	}

	public function Options()
	{
		$opBtn = new ButtonOptionsPage();
		$edit = new ButtonOptionsPage();

		return [
			'optionsPage' => [
				0 => (array)$opBtn
					->SetType('slider')
					->SetIcon($this->_icon->Add)
					->SetEvent('action.ShowFormAdd')
					->SetParams(['url' => $this->urlPage.'/FormAdd/'])
					->SetTitle($this->_lang->Customize->Icons->BtnAddDesc)
					->SetButtonName($this->_lang->Customize->Icons->BtnAdd),
				1 => (array)$opBtn
					->SetType('button')
					->SetIcon($this->_icon->Add)
					->SetEvent('GenerateIcons')
					->SetParams([
						'url' => $this->urlPage.'/GenerateIcons/',
						'text' => $this->_lang->Customize->Icons->GenerarFileIconsText,
						'btnYes' => $this->_lang->Button->Generate,
						'btnCancel' => $this->_lang->Button->Cancel 
						])
					->SetTitle($this->_lang->Customize->Icons->BtnAddDesc)
					->SetButtonName($this->_lang->Customize->Icons->GenerateFile)
			],
			'edit' => [
				0 => (array)$edit
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('action.ShowFormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->Icons->BtnEditDesc)
					->SetButtonName($this->_lang->Customize->Icons->BtnEdit)
					->SetColor('text-info')
					->SetGroup(''),
				1 => (array)$edit
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('action.ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->Icons->BtnInfoDesc)
					->SetButtonName($this->_lang->Customize->Icons->BtnInfo)
					->SetColor('text-success')
					->SetGroup(''),
				2 => (array)$edit
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('action.Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
				 		'title' => $this->_lang->Customize->Icons->BtnDelete,
				 		'text' => $this->_lang->Customize->Icons->WarningDelete
						])
					->SetTitle($this->_lang->Customize->Icons->BtnDeleteDesc)
					->SetButtonName($this->_lang->Customize->Icons->BtnDelete)
					->SetColor('text-danger')
					->SetGroup('')
			],
			'confirm' => [
				'add' => [
					'state' => true, 
					'msg' => $this->_lang->Customize->Icons->BtnDeleteDesc
				],
				'update' => [
					'state' => true, 
					'msg' => $this->_lang->Customize->Icons->BtnDeleteDesc
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
				'showAll' => true,
				'showColumns' => false,
				'searchPage' => true,
				'massiveContent' => false,
				'downloadsOptions' => true
			],
			'links' => [
				'enabled' => false,
				'options' => []
			]
		];
	}

	public function AddScripts(){ return []; }

	public function AddScriptsFooter(){ return []; }

	public function AddLinks(){ return []; }

	public function HiddenFields(){ return []; }

}