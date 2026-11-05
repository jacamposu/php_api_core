<?php 
/**
* ----------------------------------------------------------
* Menu Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class MenuMetadata extends Metadata
{
	private $_lang;
	private $_icon;


	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Menu;
		$this->title = $this->_lang->Customize->Menu->Title;
		$this->subtitle = $this->_lang->Customize->Menu->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Menu->MsgHelpGrid;
		$this->msgHelpForm = $this->_lang->Customize->Menu->msgHelpForm;
		$this->num_page = $headers['page'];
		$this->urlPage = 'Menu';
		$this->bannerForm = '';
		$this->idProfile = '60a815c4a7b05';
		$this->main_table = 'menu';
		$this->key_field = 'MenId';
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
					->SetTitle($this->_lang->Customize->Menu->BtnAdd)
					->SetButtonName($this->_lang->Customize->Menu->BtnAdd),
				1 => (array)$opBtn
					->SetType('button')
					->SetIcon($this->_icon->Cogs)
					->SetEvent('GenerateMenu')
					->SetParams([
						'url' => $this->urlPage.'/GenerateMenu/',
						'text' => $this->_lang->Customize->Menu->GenerarFileMenuText,
						'btnYes' => $this->_lang->Button->Generate,
						'btnCancel' => $this->_lang->Button->Cancel 
						])
					->SetTitle($this->_lang->Customize->Menu->GenerateFileText)
					->SetButtonName($this->_lang->Customize->Menu->GenerateFile)
			],
			'edit' => [
				0 => (array)$edit
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('action.ShowFormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->Menu->BtnEditDesc)
					->SetButtonName($this->_lang->Customize->Menu->BtnEdit)
					->SetColor('text-info')
					->SetGroup(''),
				1 => (array)$edit
					->SetType('modal')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('action.ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->Menu->BtnInfoDesc)
					->SetButtonName($this->_lang->Customize->Menu->BtnInfo)
					->SetColor('text-success')
					->SetGroup(''),
				2 => (array)$edit
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('action.Delete')
					->SetParams([
						'url' => $this->urlPage.'/Delete',
				 		'title' => $this->_lang->Customize->Menu->BtnDelete,
				 		'text' => $this->_lang->Customize->Menu->WarningDelete
						])
					->SetTitle($this->_lang->Customize->Menu->BtnDeleteDesc)
					->SetButtonName($this->_lang->Customize->Menu->BtnDelete)
					->SetColor('text-danger')
					->SetGroup('')
			],
			'confirm' => [
				'add' => [
					'action' => $this->urlPage.'/SaveForm/',
					'state' => true, 
					'msg' => $this->_lang->Customize->Menu->WarningAdd
				],
				'update' => [
					'action' => $this->urlPage.'/UpdateForm/',
					'state' => true, 
					'msg' => $this->_lang->Customize->Menu->WarningEdit
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
			'hideFieldForm' => ['MenId'],
			'optionsGrid' => [
				'showAll' => true, // Btn recargar página
				'showColumns' => true, // Ver/Ocultar columnas
				'searchPage' => true, // Buscador
				'massiveContent' => false, // Carga masiva
				'downloadsOptions' => true // Descargas
			],
			'links' => [
				'enabled' => false,
				'options' => [
					'UseLastIp' => [ // este valor corresponde al campo que se va a afectar
						'field' => 'UseLastIp',
						'url' => "", // null para que el mismo contenido sea un enlace externo y tome el valor defecto de la base de datos. Not null, para tomar enlaces internos.
						'keyField' => 'UseId',
						'target' => '_blank', 
						'Event' => '',
						'desc' => '' // Es util si se desea omitir el contenido de bd y remplazar por este enunciado, afecta todos los campos
					]
				]
			]
		];
	}

	public function AddScripts()
	{ 
		return []; 
	}

	public function AddScriptsFooter()
	{ 
		return ['/App/Menu.js']; 
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