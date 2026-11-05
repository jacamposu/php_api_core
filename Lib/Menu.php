<?php

//implements MenuInterface

class Menu 
{
    private $profile;
    private LoaderResources $_loader;

	public function __construct(string $userType)
    {
        $this->_loader = new LoaderResources();
        $this->profile =  $this->_loader->GetProfile($userType, true);
    }

    // Retorna las opciones de menu, a las que tiene acceso el usuario en session
    public function LinksMenu()
    {
        $menuList = $this->_loader->GetMenu('Es', true);
        $icons = $this->_loader->GetIcons();

        foreach ($menuList as $menu)
        {
            if($menu['MeMenuLevel'] == 1 && 
                $this->profile[$menu['MeId']]['enabled'])
            {
                $menuArrayLevel1[] = [
                    "MenId" => $menu['MeId'], 
                    "MenParentMenu" => $menu['IdParentMenu'], 
                    "MenName" => $menu['MeName'],//$langMenu->{$menu->MenId}, 
                    "MenUrl" => $menu['MeUrl'], 
                    "MenParams" => $menu['MeParams'], 
                    "MenIcon" => $icons->{$menu['IdIcon']},
                    "MenMenuType" => $menu['MeMenuType'], 
                    "MeMenuLevel" => $menu['MeMenuLevel'],
                    "MeCreated_at" => $menu['MeCreated_at']
                ];
            }
            
            if($menu['MeMenuLevel'] == 2 && 
                $this->profile[$menu['MeId']]['enabled'])
            {
                $menuArrayLevel2[] = [
                    "MenId" => $menu['MeId'], 
                    "MenParentMenu" => $menu['IdParentMenu'], 
                    "MenName" => $menu['MeName'],//$langMenu->{$menu->MenId}, 
                    "MenUrl" => $menu['MeUrl'], 
                    "MenParams" => $menu['MeParams'], 
                    "MenIcon" => $icons->{$menu['IdIcon']},
                    "MenMenuType" => $menu['MeMenuType'], 
                    "MeMenuLevel" => $menu['MeMenuLevel'],
                    "MeCreated_at" => $menu['MeCreated_at']
                ];
            }

            if($menu['MeMenuLevel'] == 3 && 
                $this->profile[$menu['MeId']]['enabled'])
            {
                $menuArrayLevel3[] = [
                    "MenId" => $menu['MeId'], 
                    "MenParentMenu" => $menu['IdParentMenu'], 
                    "MenName" => $menu['MeName'],//$langMenu->{$menu->MenId}, 
                    "MenUrl" => $menu['MeUrl'], 
                    "MenParams" => $menu['MeParams'], 
                    "MenIcon" => $icons->{$menu['IdIcon']},
                    "MenMenuType" => $menu['MeMenuType'], 
                    "MeMenuLevel" => $menu['MeMenuLevel'],
                    "MeCreated_at" => $menu['MeCreated_at']
                ];
            }
        }

        return [
            'ml1' => $menuArrayLevel1,
            'ml2' => $menuArrayLevel2,
            'ml3' => $menuArrayLevel3
        ];
    }
}