<?php

class Pagination
{
    private object $_config;
    private object $_metadata;
    private string $_iconBackDouble;
    private string $_iconBack;
    private string $_iconDoubleNext;
    private string $_iconNext;
    private string $_iconEllipsis;
    private int $_rowsTable;
    private array $_pagination;
    private object $_lang;
    private LoaderResources $_loader;

    public function __construct(object $config, $metadata, array $headers, int $rowsTable)
    {
        $this->_config = $config;
        $this->_metadata = $metadata;
        $this->_loader = new LoaderResources();
        $this->_lang = $this->_loader->GetLang($headers['user_lang']);
        $icon = $this->_loader->GetIcons();
        $this->_iconBackDouble = $icon->BackDouble;
        $this->_iconBack = $icon->Back;
        $this->_iconDoubleNext = $icon->DoubleNext;
        $this->_iconNext = $icon->Next;
        $this->_iconEllipsis = $icon->Ellipsis;
        $this->_rowsTable = $rowsTable;
        $this->_pagination = [];
    }

    public function Pagination()
    {
        // Retorno de paginacion
        if($this->_rowsTable < $this->_config->app->rowsByPage)
        {
            return 'null';
        }

        $totalPages = ceil($this->_rowsTable / $this->_config->app->rowsByPage);
        $linksBeforeAfter = intval($this->_config->app->linksPagination/2);
        
        $i=1;
        $params = null;

        // Construccion de parametros que se pasan por url para el buscador
        // if($action=='browser' && !empty($_GET)){
        //     foreach ($_GET as $key => $value) {
        //         if(!empty($value)){
        //             $params .= '&'.$key.'='.$value;
        //         }
        //     }
        // }

        // if(isset($_GET['recordEdit'])){
        //     $params .= '&recordEdit='.$_GET['recordEdit'];
        // }

        /////////////////////////// ENLACES A PAGINAS ///////////////////////////
        $this->Back($params, $i);

        $this->Link($params, $totalPages, $linksBeforeAfter);
        
        $this->Forward($params, $totalPages);

        return $this->_pagination;        
    }

    private function Back(mixed $params, int $i):void
    {
        if($this->_metadata->num_page > 1)
        {
            // Atras doble (Activo)
            $this->_pagination[] = [
                'class' => '', 
                'value' => '', 
                'title' => $this->_lang->pagination->goToFirtsPage, 
                'icon' => $this->_iconBackDouble, 
                'href' => $this->GetHref($i, $params),
                'idLink' => $this->GetIdLink($params)
            ];
            
            // Atras sencillo (Activo)
            $this->_pagination[] = [
                'class' => '', 
                'value' => '', 
                'title' => $this->_lang->pagination->goToBackPage, 
                'icon' => $this->_iconBack, 
                'href' => $this->GetHref($this->_metadata->num_page - 1, $params),
                'idLink' => $this->GetIdLink($params)
            ];
        }
        elseif($this->_rowsTable > $this->_config->app->rowsByPage)
        {
            // Atras doble (Inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => '', 
                'title' => '', 
                'icon' => $this->_iconBackDouble, 
                'href' => '',
                'idLink' => $this->GetIdLink($params)
            ];
            
            // Atras sencillo (Inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => '', 
                'title' => '', 
                'icon' => $this->_iconBack, 
                'href' => '',
                'idLink' => $this->GetIdLink($params)
            ];
        }
    }

    private function Link(mixed $params, int $totalPages, int $linksBeforeAfter):void
    {
        if($this->_rowsTable > $this->_config->app->rowsByPage)
        {
            if ($totalPages<=$this->_config->app->linksPagination)
            {
                // Paginacion: si el numero de paginas es menor a 10
                for($i=1; $i<=$totalPages; $i++){
                    if($i == $this->_metadata->num_page){
                        $this->_pagination[] = [
                            'class' => 'active', 
                            'value' => $i,
                            'title' => '', 
                            'icon' => '', 
                            'href' => $this->GetHref($i, $params),
                            'idLink' => $this->GetIdLink($params)
                        ];
                    }else{
                        $this->_pagination[] = [
                            'class' => '', 
                            'value' => $i,
                            'title' => '', 
                            'icon' => '', 
                            'href' => $this->GetHref($i, $params),
                            'idLink' => $this->GetIdLink($params)
                        ];
                    } 
                }
            }
            else
            {
                // (...) puntos iniciales
                if($this->_metadata->num_page - 1 > 4)
                {
                    $this->_pagination[] = [
                        'class' => 'disabled', 
                        'value' => null, 
                        'title' => '', 
                        'icon' => $this->_iconEllipsis, 
                        'href' => '',
                        'idLink' => $this->GetIdLink($params)
                    ];
                }  

                // operacion para conocer las posiciones; inicial y final, respecto a la paginacion.
                
                if($this->_metadata->num_page <= $linksBeforeAfter) 
                {
                    $startPage = 1;
                    $endPage = $this->_config->app->linksPagination;
                }
                else 
                {
                    $startPage = $this->_metadata->num_page - ($linksBeforeAfter-1);
                    if($this->_metadata->num_page + $linksBeforeAfter <= $totalPages)
                    {
                        $endPage = $this->_metadata->num_page + $linksBeforeAfter;
                    }
                    else
                    {
                        $endPage = $totalPages;
                        $diff = $this->_config->app->linksPagination-($totalPages - $this->_metadata->num_page)-1;
                        $startPage = $this->_metadata->num_page-$diff;
                    }
                }

                // Paginacion
                for($x=$startPage; $x<=$endPage; $x++)
                {
                    if($x == $this->_metadata->num_page)
                    {
                        $this->_pagination[] = [
                            'class' => 'active', 
                            'value' => $x,
                            'title' => str_replace('{{page}}', $x, $this->_lang->pagination->currentPage), 
                            'icon' => '', 
                            'href' => '',
                            'idLink' => $this->GetIdLink($params)
                        ];
                    }
                    else
                    {
                        $this->_pagination[] = [
                            'class' => '', 
                            'value' => $x,
                            'title' => str_replace('{{page}}', $x, $this->_lang->pagination->goToPage), 
                            'icon' => '', 
                            'href' => $this->GetHref($x, $params),
                            'idLink' => $this->GetIdLink($params)
                        ];
                    }  
                }

                // (...) puntos finales
                if ($this->_metadata->num_page <= $endPage && $endPage < $totalPages) 
                {
                    $this->_pagination[] = [
                        'class' => 'disabled', 
                        'value' => null, 
                        'title' => '', 
                        'icon' => $this->_iconEllipsis, 
                        'href' => '',
                        'idLink' => $this->GetIdLink($params)
                    ];
                }       
            }
            
        }
        else
        {
            // Atras doble (inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => null, 
                'title' => '', 
                'icon' => $this->_iconBackDouble, 
                'href' => '',
                'idLink' => $this->GetIdLink($params)
            ];
            
            // Atras sencillo (inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => null, 
                'title' => '', 
                'icon' => $this->_iconBack, 
                'href' => '',
                'idLink' => $this->GetIdLink($params)
            ];

            // Adelante sencillo (inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => null, 
                'title' => '', 
                'icon' => $this->_iconNext, 
                'href' => '',
                'idLink' => $this->GetIdLink($params)
            ];


            // Adelante doble (inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => null, 
                'title' => '', 
                'icon' => $this->_iconDoubleNext, 
                'href' => '',
                'idLink' => $this->GetIdLink($params)
            ];
        }
    }

    private function Forward(mixed $params, int $totalPages):void
    {
        if($this->_metadata->num_page < $totalPages)
        {
            // adelante sencillo (activo)
            $j=$this->_metadata->num_page+1;
            $this->_pagination[] = [
                'class' => null, 
                'value' => null, 
                'title' => $this->_lang->pagination->goToNextPage, 
                'icon' => $this->_iconNext, 
                'href' => $this->GetHref($j, $params),
                'idLink' => $this->GetIdLink($params)
            ];
            
            // adelante doble (activo)
            $this->_pagination[] = [
                'class' => null, 
                'value' => null, 
                'title' => $this->_lang->pagination->goToLastPage, 
                'icon' => $this->_iconDoubleNext, 
                'href' => $this->GetHref($totalPages, $params),
                'idLink' => $this->GetIdLink($params)
            ];
        }
        elseif($this->_rowsTable > $this->_config->app->rowsByPage)
        {
            // adelante sencillo (inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => null, 
                'title' => null, 
                'icon' => $this->_iconNext, 
                'href' => null,
                'idLink' => $this->GetIdLink($params)
            ];

            // adelante doble (inactivo)
            $this->_pagination[] = [
                'class' => 'disabled', 
                'value' => null, 
                'title' => null, 
                'icon' => $this->_iconDoubleNext, 
                'href' => null,
                'idLink' => $this->GetIdLink($params)
            ];
        }
    }

    private function GetIdLink($params)
    {
        return !empty($params) ? 'id="PaginationAjax"' : null;
    }

    private function GetHref(int $numPage=null, $params)
    {
        return !is_null($numPage) ? "page={$numPage}" : 1;
    }
}