<?php
/**
* ----------------------------------------------------------
* Class AppTableView
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/

class DataTable //implements InterfaceTableView
{
    private $config;
    private $metadata;
    private $mime;
    private $profile;
    private $options;
    private $_lang;
    private $_dictionary;
    private $_icon;

    /* Constructor */
    public function __construct($config, $metadata, array $headers)
    {
        $this->config = $config;
        $this->metadata = $metadata;
        $this->options = $metadata->Options();
        $loader = new LoaderResources();
        $this->mime = $loader->GetMimeType(true);
        $this->profile = $loader->GetProfile($headers['user_type'],true);
        $this->_lang = $loader->GetLang($headers['user_lang']);
        $this->_dictionary = $loader->GetDictionary(true);
        $this->_icon = $loader->GetIcons();
    }

    /* Retorno de todos los valores de la tabla */
    public function Table(object $result, int $totalRecords, int $startFrom):array
    {
        $multiCheck = $this->GetMulticheckColumn();
        if($result->recordsReturned > 0){
            return [
                'caption' => $this->DescriptionTable($totalRecords, $startFrom),
                'thead' => $this->TheadInfo($result->columnName, $multiCheck),
                'tbody' => $this->TbodyInfo($result, $result->columnName),
                'metadata' => $this->Metadata($result->columnName)
            ];
        }else{
            return [
                'icon' => $this->_icon->UserFriends,
                'text' => $this->_lang->Table->EmptyResult
            ];
        }
    }

    private function Metadata($columns)
    {
        $metadata = [];
        foreach($columns as $column)
        {
            if(!in_array($column, $this->options['hideFieldTable']))
            {
                $metadata[$column] = $this->_dictionary[$column];
            }
        }        
        return $metadata;
    }

    /* Informacion del cuerpo de la tabla */
    private function TbodyInfo($result, $fieldsTable)
    {
        $tbody = [];
        $j=0;

        // Filas de tabla
        foreach ($result->data as $row) 
        {
            $row = (array) $row;
            // Boton lista de acciones
            $tbody[$j]['actionColumn'] = $this->ActionEdit(
                $row, 
                $fieldsTable, 
                $this->metadata,  
                $this->metadata->key_field
            );
            
            // Informacion de las celdas   
            for ($i=0; $i<sizeof($row); $i++) 
            {
                $tbody[$j]['row'][$i]['fieldDictionary'] = $fieldsTable[$i];
                $tbody[$j]['row'][$i]['text'] = $row[$fieldsTable[$i]];
                $class = '';
                $sizeIcons = $this->config->style->sizeIconsRepository;

                /* BADGES */
                if(isset($this->options['badges']['options'][$fieldsTable[$i]][$row[$fieldsTable[$i]]]['TipoBadge']))
                {
                    $class = $this->options['badges']['options'][$fieldsTable[$i]][$row[$fieldsTable[$i]]]['TipoBadge'];
                }

                /* ARCHIVOS Y DIRECTORIOS */
                if(isset($row['FilType']))// Archivos
                {
                    // $headers = new Headers('GetQuery', 'RepositoryModel', 'GetTypeMime', 'Row');
                    // $headers->params = [':keyfield' => $row['FilId']];
                    // $api = new ApiDatabase($this->config, '');
                    // $result = $api->GetDatabase((array)$headers);

                    // $IconFile = $this->mime[$result->result->data->MimTypeMime]['Icon'];
                    // $Color = $this->mime[$result->result->data->MimTypeMime]['Color'];

                    // $class = "{$this->_icon->$IconFile} {$sizeIcons}";
                    // $tbody[$j]['cell'][$i]['color'] = $Color;
                }
                
                if(isset($row['NameRepository']))// Directorios
                {
                    $IconFile = $this->mime['FolderOpen']['Icon'];
                    $Color = $this->mime['FolderOpen']['Color'];

                    $class = "{$IconFile} {$sizeIcons}";
                    $tbody[$j]['row'][$i]['color'] = $Color;
                }

                /* Creacion de enlaces tipo <a href> */
                if($this->options['links']['enabled'] == true)
                {
                    //     if(count(array_key_exists($fieldsTable[$i], $this->options['links']['options'])) > 0)
                    //     {
                    //         $tbody[$j]['cell'][$i]['field'] = $this->options['links']['options'][$fieldsTable[$i]]['field'];
                    //         $tbody[$j]['cell'][$i]['url'] = $this->options['links']['options'][$fieldsTable[$i]]['url'] == '' ? $row[$fieldsTable[$i]] : $this->options['links']['options'][$fieldsTable[$i]]['url'].$row[$fieldsTable[$i]];
                    //         $tbody[$j]['cell'][$i]['keyField'] = $this->options['links']['options'][$fieldsTable[$i]]['keyField'];
                    //         $tbody[$j]['cell'][$i]['target'] = $this->options['links']['options'][$fieldsTable[$i]]['target'];
                    //         $tbody[$j]['cell'][$i]['eventJS'] = $this->options['links']['options'][$fieldsTable[$i]]['Event'];
                    //         $tbody[$j]['cell'][$i]['text'] = empty($this->options['links']['options'][$fieldsTable[$i]]['desc']) ? $row[$fieldsTable[$i]] : $this->options['links']['options'][$fieldsTable[$i]]['desc'];
                    //     }
                }

                /* LONGTEXT & FORMAT FIELD */
                if (!empty($this->_dictionary[$fieldsTable[$i]]['format'])) 
                {
                    $tbody[$i] = Util::CurrencyFormat($this->_dictionary[$fieldsTable[$i]]['format'], $row[$fieldsTable[$i]]);
                }
                else 
                {
                    // Valor defecto
                    // if(strlen($row[$fieldsTable[$i]]) > 100)
                    // {
                    //     $tbody[$j]['row'][$i]['length'] = strlen($row[$fieldsTable[$i]]);
                    //     $tbody[$j]['row'][$i]['url'] = $this->metadata->urlPage."?action=showListView&recordEdit=".$row[$fieldsTable[0]];
                    //     $tbody[$j]['row'][$i]['title'] = 'Abrir para leer mas contenido';
                    //     $tbody[$j]['row'][$i]['text'] = substr($row[$fieldsTable[$i]], 0,100);
                    // }
                }

                $tbody[$j]['row'][$i]['class'] = $class;
            }

            $j++;
        }

        return $tbody;
    }

    /* Informacion del encabezado de la tabla */
    private function TheadInfo($fieldsTable, $columnCheck=null)
    {
        $thead = [];

        // action column
        if(isset($this->options['edit'][0]->buttonName))
        {
            if($this->profile[$this->metadata->idProfile]['update'] || 
                $this->profile[$this->metadata->idProfile]['delete'] || 
                $this->profile[$this->metadata->idProfile]['read'])
            {
                array_push($thead, [
                    'description' => $this->_lang->Table->actionColumnText,
                    'value' => '',
                    'showSortColumnButton' => false,
                    'checkColumn' => $columnCheck
                ]);
            }
        }

        // columns
        foreach($fieldsTable as $value)
        {
            if(!in_array($value, $this->options['hideFieldTable']))
            {
                array_push($thead, [
                    'description' => '',
                    'fieldName' => isset($this->_dictionary[$value]['fieldName']) ? $this->_dictionary[$value]['fieldName'] : $value,
                    'showSortColumnButton' => true,
                    'checkColumn' => false
                ]);
            }
        }        
        return $thead;
    }

    /* Verifacion de columna multicheck */
    private function GetMulticheckColumn()
    {
        $dropdown = '';

        if(isset($this->options['edit'][0]->buttonName))
        {
            $columnCheck = false;
            $dropdown = null;
            if($this->options['multiChecks']['enabled'])
            {
                $columnCheck = true;
                $dropdown = $this->BotonAccionChecksMultiple(
                        $this->options, 
                        $this->metadata->sqlTable, 
                        $this->metadata->keyField, 
                        $this->metadata->deleteField, 
                        $this->metadata->dbAlternative);
            }
        }
        return[
            //'columnCheck' => $columnCheck,
            'thead' => $dropdown
        ];
    }

    /* Informacion descriptiva de la tabla */
    private function DescriptionTable(int $rowsTable, int $startFrom):string
    {
        $page = $this->config->app->rowsByPage * $this->metadata->num_page;
        $startFrom = $startFrom == 0 ? 1 : $startFrom;
        $pagination = str_replace('{{startFrom}}', $startFrom, $this->_lang->pagination->showInfo);
        $pagination = str_replace('{{to}}', $page < $rowsTable ? $page : $rowsTable, $pagination);
        $pagination = str_replace('{{rowsTable}}', $rowsTable, $pagination);
        $pagination = str_replace('{{startPage}}', $this->metadata->num_page, $pagination);
        $pagination = str_replace('{{finalPage}}', ceil($rowsTable/$this->config->app->rowsByPage), $pagination);
        
        return $pagination;
    }

    /* Lista desplegable de acciones para cada fila de tabla */
    private function ActionEdit($row, $tableFields, $infoPage, $keyField)
    {
        $row = (array) $row;
        $idPage = NULL;
        $linkEdit = [];
        $optionsEdit = $this->options["edit"];

        // Verifica que al menos exista un enlace
        //if(!isset($options[0]->buttonName)) return null;
        
        // Verifica la prefilacion del usuario
        if(!$this->ValidateProfile()) return null;

        // Construccion de multiChecks
        // if($options['multiChecks']['enabled'])
        // {
        //     // $linkEdit .= $this->htmlUtil->Multichecks($row[$keyField]);
        // }

        // print_r($optionsEdit);

        foreach($optionsEdit as $option)
        {
            // print_r($option);
            if(empty($option->group) && isset($option->type))
            {
                switch ($option->type) 
                {
                    case 'confirm':
                        $eventOnclick = [
                            'functionName' => $option->event,
                            'params' => [
                                'message' => $option->params->text,
                                'keyfield' => $keyField,
                                'record' => $row[$tableFields[0]],
                                'table' => $this->metadata->main_table,
                                'action' => $option->params->url,
                                //'title' => $options['edit'][$i]['params']->title,
                                'idPage' => $idPage
                            ]
                        ];
                        $url = "";
                        break;

                    case 'modal':
                        $eventOnclick = [
                            'functionName' => $option->event,
                            'params' => [
                                'keyfield' => $keyField,
                                'record' => $row[$tableFields[0]],
                                'action' => $option->params->url
                            ]
                        ];
                        $url = "";
                        break;

                    case 'slider':
                        $eventOnclick = [
                            'functionName' => $option->event,
                            'params' => [
                                'keyfield' => $keyField,
                                'record' => $row[$tableFields[0]],
                                'action' => $option->params->url
                            ]
                        ];
                        $url = "";
                        break;
                    
                    default:
                        $eventOnclick = "";
                        $id = !empty($option->params) ? "/id=".$row[$tableFields[0]] : "recordEdit=".$row[$tableFields[0]];
                        $url = $option->url.$option->params.$id.$idPage;
                        break;
                }

                $link['class'] = "dropdown-item {$option->color}";
                $link['title'] = $option->title;
                $link['icon'] = $option->icon;//$this->icon->{};
                $link['name'] = $option->buttonName;
                $link['color'] = $option->color;
                $link['type'] = $option->type;
                $link['onclick'] = $eventOnclick;
                $link['href'] = $url;

                array_push($linkEdit, $link);
            }
        }
        /*
        // Construccion del enlace
        for ($i=0; $i < count($options); $i++)
        {
            if(empty($options[$i]->buttonName)) return null;

            // if(is_array($options['edit'][$i]['group']) && 
            //     in_array($options['edit'][$i]['group']['field'], $tableFields) && 
            //     $options['edit'][$i]['group']['value'] == $row[$options['edit'][$i]['group']['field']])
            // {
            //     // URLS AGRUPADAS
            //     if(isset($options['edit'][$i]['event']))
            //     {
            //         $eventOnclick = $options['edit'][$i]['event']."(
            //             '".$params['msg']."', 
            //             '".$keyField."', 
            //             '".$row[$tableFields[0]]."', 
            //             '".$infoPage['sqlTable']."', 
            //             '".$this->config->url->app.'Repository/'.$params['action']."', 
            //             '".$params['title']."', 
            //             '".$idPage."'
            //         );";

            //         // Link
            //         // $linkEdit .= "<a class=\"dropdown-item {$color}\" onclick=\"{$eventOnclick}\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$title}\" style=\"cursor:pointer;\">{$iconLink} {$name}</a>";
            //     } 
            //     elseif(isset($options['edit'][$i]['url'])) 
            //     {
            //         $id = !empty($params) ? "/id=".$row[$tableFields[0]] : "recordEdit=".$row[$tableFields[0]];                    
            //         $url = $options['edit'][$i]['url']."/".$params.$id.$idPage;
            //         // Link
            //         // $linkEdit .= "<a class=\"dropdown-item {$color}\" href=\"{$url}\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$title}\">{$iconLink} {$name}</a>";
            //     }
            //     else
            //     {
            //         // die('accion no permitida');
            //     }
            // }else


        }
        */

        return $linkEdit;
    }

    // Seleccion multiple en tabla
    private function BotonAccionChecksMultiple ($options, $sqlTable, $keyField, $deleteField, $dbAlternative)
    {

        $dropdown = null;
        $dropdown .= '<div class="dropdown" style="display: inline;">';
        $dropdown .= '<button class="btn btn-link text-white dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $dropdown .= '</button>';
        $dropdown .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
        for($i=0; $i<count($options['multiChecks']['options']); $i++){
            if(
                isset($options['multiChecks']['options'][$i]['url']) && 
                isset($options['multiChecks']['options'][$i]['iconOption']) && 
                isset($options['multiChecks']['options'][$i]['optionDesc'])
            ){
                $url = $options['multiChecks']['options'][$i]['url'];
                $desc = $options['multiChecks']['options'][$i]['optionDesc'];
                $iconOption = $options['multiChecks']['options'][$i]['iconOption'];
                // Enlace
                $dropdown .= "<a class=\"dropdown-item\" href=\"#\" onclick=\"appSendFormChecks(this, '$sqlTable', '$keyField', '$url', '$deleteField', '$dbAlternative');\">".$iconOption.'&nbsp;'.$desc."</a>";
            }
        }
        $dropdown .= "</div>";
        $dropdown .= "</div>";
        return $dropdown;

    }


    private function ValidateProfile():bool
    {
        return 
            $this->profile[$this->metadata->idProfile]['update'] && 
            $this->profile[$this->metadata->idProfile]['delete'] && 
            $this->profile[$this->metadata->idProfile]['read'] 
            ? true : false;
    }

}