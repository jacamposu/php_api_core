<?php
/**
* ----------------------------------------------------------
* Users Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class UsersMetadata extends Metadata
{
	private $_lang;
	private $_icon;

	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->iconPage = $this->_icon->Users;
		$this->title = $this->_lang->Customize->Users->Title;
		$this->subtitle = $this->_lang->Customize->Users->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Users->MsgHelpGrid;
		$this->msgTitleForm = $this->_lang->Customize->Users->MsgTitleForm;
		$this->msgHelpForm = $this->_lang->Customize->Users->MsgHelpForm;
		$this->num_page = $headers['page'];
		$this->urlPage = 'Users';
		$this->bannerForm = '';
		$this->idProfile = '60a824c6cbcdc'; // Dato proveniente del id menu
		$this->main_table = 'users';
		$this->key_field = 'UsrId';
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
				0 => $btn
					->SetType('slider')
					->SetIcon($this->_icon->UserAdd)
					->SetEvent('action.FormAdd')
					->SetParams(['url' => $this->urlPage.'/FormAdd/'])
					->SetTitle($this->_lang->Customize->Users->CreateDesc ?? $this->_lang->Customize->Users->Create)
					->SetButtonName($this->_lang->Customize->Users->Create)	
					->SetMode("Submit")
			],
			'edit' => [
				0 => $btn1
					->SetType('slider')
					->SetIcon($this->_icon->RecordEdit)
					->SetEvent('FormEdit')
					->SetParams(['url' => $this->urlPage.'/FormEdit'])
					->SetTitle($this->_lang->Customize->Users->UpdateDesc ?? $this->_lang->Customize->Users->Update)
					->SetButtonName($this->_lang->Customize->Users->Update)
					->SetColor('text-primary')
					->SetGroup(''),
				1 => $btn2
					->SetType('slider')
					->SetIcon($this->_icon->Binoculars)
					->SetEvent('ShowRecord')
					->SetParams(['url' => $this->urlPage.'/ListView'])
					->SetTitle($this->_lang->Customize->Users->ReadDesc ?? $this->_lang->Customize->Users->Read)
					->SetButtonName($this->_lang->Customize->Users->Read)
					->SetColor('text-primary')
					->SetGroup(''),
				2 => $btn3
					->SetType('confirm')
					->SetIcon($this->_icon->RecordDelete)
					->SetEvent('Delete')
					->SetParams([
						'url' => $this->urlPage.'/DeleteUser',
						'title' => $this->_lang->Customize->Users->Delete,
				 		'text' => $this->_lang->Warning->Delete
						])
					->SetTitle($this->_lang->Customize->Users->DeleteDesc ?? $this->_lang->Customize->Users->Delete)
					->SetButtonName($this->_lang->Customize->Users->Delete)
					->SetColor('text-danger')
					->SetGroup(''),
				4 => $btn4
					->SetType('confirm')
					->SetIcon($this->_icon->Key)
					->SetEvent('ChangePassword')
					->SetParams([
						'url' => $this->urlPage.'/ChangePassword',
						'title' => $this->_lang->Customize->Users->RecoveryPassword,
				 		'text' => 'Confirma que desea recuperar la contraseña para el usuario id: '
						])
					->SetTitle($this->_lang->Customize->Users->RecoveryPassword)
					->SetButtonName($this->_lang->Customize->Users->RecoveryPassword)
					->SetColor('text-primary')
					->SetGroup('')
			],
			'confirm' => [
				'add' => [
					'action' => $this->urlPage.'/AddUser/',
					'state' => true, 
					'msg' => $this->_lang->Warning->Create
				],
				'update' => [
					'action' => $this->urlPage.'/EditUser/',
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
				"options" => [
					0 => [
						'Accion' => 'Siguiente',
						'ConfirmarAccion' => 1,
						'icon_class' => 'fas fa-chevron-left',
						'boton_class' => 'btn btn-primary'		
					]
				]
			],
			'badges' => [
				'enabled' => true,
				'options' => [
					'UseUserStatus' => [
						'Activo' => ['TipoBadge' => 'success'],
						'Bloqueado' => ['TipoBadge' => 'danger'],
						'Inactivo' => ['TipoBadge' => 'warning'],
						'Eliminado' => ['TipoBadge' => 'secondary']
					]
				]
			],
			'multiChecks' => [
				'enabled' => false,
				'options' => [
					0 => ['url' => 'url', 'optionValue' => 'valor', 'optionDesc' => 'desc']
				]	
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
		return []; 
	}

	public function AddLinks()
	{ 
		return []; 
	}

	public function HiddenFields()
	{ 
		return [$this->key_field, 'UsrIdentificationNumber', 'IdCountryUser', 'IdStateUser', 'IdCity']; 
	}
}