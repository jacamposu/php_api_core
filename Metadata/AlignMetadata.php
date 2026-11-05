<?php
/**
* ----------------------------------------------------------
* Align Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class AlignMetadata extends Metadata
{
	private $_lang;
	private $_icon;

	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->AlignCenter;
		$this->title = $this->_lang->Customize->Align->Title;
		$this->subtitle = $this->_lang->Customize->Align->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Align->MsgHelpGrid;
		$this->msgHelpForm = $this->_lang->Customize->Align->msgHelpForm;
		$this->num_page = $headers['page'];
        $this->urlPage = 'Align';
		$this->bannerForm = '';
		$this->idProfile = '60a837e47d279';
		$this->main_table = 'align';
		$this->key_field = 'AliId';
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
					->SetEvent('ShowFormAdd')
					->SetParams(['url' => $this->urlPage.'/FormAdd/'])
					->SetTitle($this->_lang->Customize->Align->BtnAddDesc)
					->SetButtonName($this->_lang->Customize->Align->BtnAdd)
			],
			'edit' => [
				0 => $btn1
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('ShowFormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->Align->BtnEditDesc)
					->SetButtonName($this->_lang->Customize->Align->BtnEdit)
					->SetColor('text-info')
					->SetGroup(''),
				1 => $btn2
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->Align->BtnInfoDesc)
					->SetButtonName($this->_lang->Customize->Align->BtnInfo)
					->SetColor('text-success')
					->SetGroup(''),
				2 => $btn3
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
						'title' => $this->_lang->Customize->Align->BtnDelete,
				 		'text' => $this->_lang->Customize->Align->WarningDelete
						])
					->SetTitle($this->_lang->Customize->Align->BtnDeleteDesc)
					->SetButtonName($this->_lang->Customize->Align->BtnDelete)
					->SetColor('text-danger')
					->SetGroup('')
			],
			'confirm' => [
				'add' => [
					'action' => $this->urlPage.'/SaveForm/',
					'state' => true, 
					'msg' => $this->_lang->Customize->Align->WarningAdd
				],
				'update' => [
					'action' => $this->urlPage.'/UpdateForm/',
					'state' => true, 
					'msg' => $this->_lang->Customize->Align->WarningEdit
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