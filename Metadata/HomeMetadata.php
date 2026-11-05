<?php 
/**
* ----------------------------------------------------------
* Dictionary Class Metadata
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class HomeMetadata extends Metadata
{
	private $_lang;
	private $_icon;
	private $_config;


	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_icon = $loader->GetIcons();
		$this->_config = $loader->GetConfig();
		$this->iconPage = $this->_icon->Home;
		$this->title = $this->_lang->Customize->Home->Title;
		$this->subtitle = $this->_lang->Customize->Home->Subtitle;
		$this->msgHelpGrid = $this->_lang->Customize->Home->MsgHelpGrid;
		$this->msgHelpForm = $this->_lang->Customize->Home->msgHelpForm;
		$this->urlPage = 'Home';
		$this->num_page = 1;
		$this->bannerForm = '';
		$this->idProfile = '';
		$this->main_table = '';
		$this->key_field = '';
	}

	public function Options()
	{
		return [];
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