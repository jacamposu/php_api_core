<?php 


class NavigationBar 
{
	private $metadata;
    private $profile;
    private $icon;
    private $options;
    private $_icons;
    private $_config;
    private $_lang;
    private LoaderResources $_loader;
    public array $navbar;

	function __construct(object $metadata, array $headers)
    {
		$this->metadata = $metadata;
        $this->options = $metadata->Options();
        $this->_loader = new LoaderResources();
        $this->_config = $this->_loader->GetConfig();
        $this->_lang = $this->_loader->GetLang($headers['user_lang']);
        $this->profile = $this->_loader->GetProfile($headers['user_type'], true);
        $this->_icons = $this->_loader->GetIcons();
        $this->navbar = [];
	}

    // barra de navegacion
	public function navbarOptions($showButtons=false, $aditional='')
    {
        if(!$showButtons)
        {
            return [];
        }
        
        $columns = [
            'type_button' => 'modal',
            'nameButton' => 'modalOptionsPageColumns', 
            'icon' => $this->_icons->Columns,
            'text_button' => $this->_lang->Modal->Columns->NameButton,
            'placeholder' => $this->_lang->Modal->Columns->Placeholder,
            'id' => 'shc',
            'url' => $this->_config->url->app.'/'.$this->metadata->urlPage."/ModalColumns/"
        ];

        $search = [
            'type_button' => 'modal', 
            'nameButton' => 'modalOptionsPageSearch',
            'icon' => $this->_icons->Search,
            'text_button' => $this->_lang->Modal->Search->NameButton,
            'placeholder' => $this->_lang->Modal->Search->Placeholder,
            'id' => 'msp',
            'url' => $this->_config->url->app.'/'.$this->metadata->urlPage."/ModalSearch/"
        ];

        $massive = [
            'type_button' => 'collapsible', 
            'nameButton' => 'modalOptionsPageMassive', 
            'icon' => $this->_icons->Load,
            'text_button' => $this->_lang->Modal->Massive->NameButton,
            'placeholder' => $this->_lang->Modal->Massive->Placeholder,
            'id' => 'mc',
            'url' => $this->_config->url->app.'/'.$this->metadata->urlPage."/MassiveContent/"
        ];

        $downloads = [
            'type_button' => 'modal', 
            'nameButton' => 'modalOptionsPageDownloads', 
            'icon' => $this->_icons->DownloadCloud,
            'text_button' => $this->_lang->Modal->Downloads->NameButton,
            'placeholder' => $this->_lang->Modal->Downloads->Placeholder,
            'id' => 'do',
            'url' => $this->_config->url->app.'/'.$this->metadata->urlPage."/ModalDownloads/"
        ];

        $this->OptionsPage();
        
        // 'aditional' => [], //$aditional

        if($this->ValidateOption('showColumns'))
        {
            $this->navbar['options'][] = $columns;
        }

        if($this->ValidateOption('searchPage'))
        {
            $this->navbar['options'][] = $search;
        }

        if($this->ValidateOption_Profile('massiveContent', 'create'))
        {
            $navbar['optionsDefault']['massive'] = $massive;
        }

        if($this->ValidateOption_Profile('downloadsOptions', 'downloads'))
        {
            $this->navbar['options'][] = $downloads;
        }

        return $this->navbar;
    }

    private function ValidateOption($item)
    {
        return isset($this->options['optionsGrid'][$item]) && $this->options['optionsGrid'][$item];
    }

    private function ValidateOption_Profile($item, $resource)
    {
        return isset($this->options['optionsGrid'][$item]) && $this->options['optionsGrid'][$item] && $this->profile[$this->metadata->idProfile][$resource];
    }

    // Accion a otros recursos
    private function OptionsPage()
    {
        $optionPage = [];

        if(isset($this->options['optionsPage']) && is_array($this->options['optionsPage']) && count($this->options['optionsPage']) > 0)
        {
            foreach($this->options['optionsPage'] as $option)
            {
                if(isset($option->buttonName) && !empty($option->buttonName) && $this->profile[$this->metadata->idProfile]['create'])
                {
                    $option->params->url = $this->_config->url->app.'/'.$option->params->url;
                    $arrayOption = [
                        'type_button' => $option->type,
                        'action' => $option->event,
                        'params' => $option->params,
                        'icon' => $option->icon,
                        'placeholder' => $option->title,
                        'text_button' => $option->buttonName
                    ];
                    $optionPage =  $arrayOption;

                    $this->navbar['options'][] = $optionPage;
                }
            }
        }
    }
}