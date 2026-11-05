<?php 
/**
* ----------------------------------------------------------
* Clase AppForms
* @author Jose Antonio Campos Urquijo
* ----------------------------------------------------------
*/


class Forms
{
    private $profile;
    private $metadata;

    function __construct($metadata)
    {
        $this->metadata = $metadata;
    }

    #region PUBLIC METHODS

    public function Fields(string $action, object $result, object $list, mixed $listSaved, $dictionary)
    {
        $formField = [];
        $genericList=[];
        $saveList=[];

        if($action == 'Edit' and isset($listSaved->result))
        {
            $i=0;
            foreach($listSaved->result as $column => $data)
            {
                /*
                    formulario editar
                    cuando el select este bloqueado solo envia el valor guardado en base de datos.
                    cuando el select no este bloqueado, envia toda la lista.
                    cuando el campo dependa de otro no envia datos
                */

                // if($dictionary->{$column}->dependentFrom == ""){
                //     $saveList[$column] = $this->DropDownSaved($column, $listSaved->result, $i);
                // }
            }
        }

        if($list->statusCode != 200)
            throw new Exception($list->result->error ?? "La peticion a la base de datos fallo", 404);

        if($action != 'Search' && isset($list->result))
        {
            foreach($list->result as $column => $data)
            {
                /*
                    formulario agregar
                */
                // if($dictionary->{$column}->dependentFrom == ""){
                //     $genericList[$column] = $this->DropDownList($column, $list->result, $saveList, $action);
                // }
            }
        }

        // Campos del formulario.
        foreach($result->result->columnName as $column)
        {
            if(!in_array($column, $this->metadata->Options()['hideFieldForm']))
            {
                if($action == 'Edit')
                {
                    $selected = $saveList[$column][0] ?? $result->result->data->{$column};
                }
                else
                {
                    $selected = isset($genericList[$column]) && isset($dictionary->{$column}) ? 
                        [
                            "value" => "", 
                            "text" => "- Seleccione ".$dictionary->{$column}->desc." -"
                        ] : '';
                }

                $formField[] = [
                    'fieldName' => $column,
                    'value' => $selected,
                    'disabled' => in_array($column, $this->metadata->HiddenFields()) && $action == 'Edit' ? 'disabled' : '',
                    'options' => $genericList[$column] ?? []
                    // 'metadata' => $dictionary->{$column} ?? []
                ];
            }
        }

        return [
            'visible' => $formField,
            'hiddenKey' => $this->KeyHiddenField(),
            'hidden' => $this->HiddenFields()
        ];
    }

    #endregion

    #region PRIVATE METHODS

    private function KeyHiddenField()
    {
        $formField = [];
        // if(isset($result->result->data->{$this->metadata->key_field}))
        // {
        //     $formField[0] = [
        //         'fieldDictionary' => $this->metadata->key_field,
        //         'value' => $result->result->data->{$this->metadata->key_field}
        //     ];
        // }
        return $formField;
    }

    private function HiddenFields()
    {
        $formField = [];
        // foreach ($this->metadata->HiddenFields() as $key => $value) 
        // {
        //     $formField[$key] = [
        //         'dictionary' => $key,
        //         'value' => $value
        //     ];
        // }
        return $formField;
    }

    private function DropDownList(string $column, object $result, array $saveList, string $action):array
    {
        $elements=[];
        $element=[];        
        $i=0;

        if(!isset($result->{$column}->data)) throw new Exception("El server no ha devuelto las listas (ValuesForDropDownList)",500);

        foreach($result->{$column}->data as $data)
        {
            $j = 0;
            foreach($result->{$column}->columnName as $kolumn)
            {
                if($action == 'formEdit')
                {
                    if($j == 0 && isset($saveList[$column][0]['value']) && $saveList[$column][0]['value'] != $data->{$kolumn}) 
                        $element['value'] = $data->{$kolumn};

                    if($j == 1 && isset($saveList[$column][0]['text']) && $saveList[$column][0]['text'] != $data->{$kolumn}) 
                        $element['text'] = $data->{$kolumn};
                }
                else
                {
                    if($j == 0)
                        $element['value'] = $data->{$kolumn};

                    if($j == 1)
                        $element['text'] = $data->{$kolumn};
                }

                $j++;    
            }
            if(count($element)>0) $elements[] = $element;
            $i++;
        }

        return $elements;
    }

    private function DropDownSaved(string $column, object $result, int $i):array
    {
        $elements=[];
        $count = 0;

        foreach($result->{$column}->columnName as $kolumn)
        {
            if($count == 0 && isset($result->{$column}->data[0]->{$kolumn})) $elements[$i]['value'] = $result->{$column}->data[0]->{$kolumn};
            if($count == 1 && isset($result->{$column}->data[0]->{$kolumn})) $elements[$i]['text'] = $result->{$column}->data[0]->{$kolumn};
            $count++;
        }

        return $elements;
    }

    #endregion

    /*

    // Formulario para editar perfilación (tipo arbol)
    public function appShowFormEditProfileMenu($recordEdit, $resultchecksSaved, $vectorMenuParent, $vectorMenuChild, $vectorMenuGrandChild){
        // Llamado de variables globales
        global $icon;
        global $langResource;
        
        $contChecked = 0;
        $checksSavedParent = [];

        // Se genera el contenido del formulario
        foreach ($resultchecksSaved as $key => $value) {
            $checksSaved[$value['ProMenu']] = $value['ProVisibleMenu'];
        }

        // Retorna ejecución si no hay datos
        if(empty($checksSaved)){
            die(appAlert('41', null, $langResource['msgProfilingProfilingAccessForbidden']));
        }

        $input = new HtmlInput();



        // Primera linea de menú
        $menu = "<ul>";
        for($f=0; $f<count($vectorMenuParent); $f++){ // primer for
            // Segunda linea de menú
            $listMenuChild = "<ul>";
            for($i=0; $i<count($vectorMenuChild); $i++){ // segundo for
                if($vectorMenuChild[$i]['MenMenuType'] == '2' && 
                    $vectorMenuChild[$i]['MenParentMenu'] == $vectorMenuParent[$f]['MenId'])
                {
                    // Tercera linea de menú
                    $listMenuGrandChild = '<ul>';
                    for($j=0; $j<count($vectorMenuGrandChild); $j++){ // tercer for
                        if($vectorMenuGrandChild[$j]['MenMenuType'] == '3' && $vectorMenuGrandChild[$j]['MenParentMenu'] == $vectorMenuChild[$i]['MenId']){
                            $listMenuGrandChild .= '<li>';
                            //$listMenuGrandChild .= '<span class="">';
                            if(array_key_exists($vectorMenuGrandChild[$j]['MenId'], $checksSaved)){
                                $valueCheck = $checksSaved[$vectorMenuGrandChild[$j]['MenId']] ? '1' : '';
                                $listMenuGrandChild .= $input->checkbox(
                                    $vectorMenuGrandChild[$j]['MenName'], 
                                    $valueCheck, 
                                    'input_'.$vectorMenuGrandChild[$j]['MenId'], 
                                    $vectorMenuGrandChild[$j]['MenId'], 
                                    "marcarinputpadre('".$vectorMenuChild[$i]['MenId']."');", 
                                    'check_hijo input_'.$vectorMenuChild[$i]['MenId'],
                                    '250px'
                                );
                            }
                            //$listMenuGrandChild .= '</span>';
                            $listMenuGrandChild .= "</li>";
                        }
                    } // cierre tercer for
                    $listMenuGrandChild .= '</ul>';

                    $listMenuChild .= '<li>';
                    $listMenuChild .= '<span class="">';
                    if(array_key_exists($vectorMenuChild[$i]['MenId'], $checksSaved)){
                        $valueCheck = $checksSaved[$vectorMenuChild[$i]['MenId']] ? '1' : '';
                        $checkChilds = $vectorMenuChild[$i]['MenUrl'] == 'javascript:void();' ? 'marcarinputshijos(this);' : '';
                        $listMenuChild .= $input->checkbox(
                            $vectorMenuChild[$i]['MenName'], 
                            $valueCheck, 
                            'input_'.$vectorMenuChild[$i]['MenId'], 
                            $vectorMenuChild[$i]['MenId'], 
                            "marcarinputpadre('".$vectorMenuParent[$f]['MenId']."'); ".$checkChilds, 
                            'check_hijo input_'.$vectorMenuParent[$f]['MenId'],
                            '250px'
                        );
                    }
                    $listMenuChild .= '</span>';
                    $listMenuChild .= $listMenuGrandChild;
                    $listMenuChild .= "</li>";
                }
            } // cierre segundo for
            $listMenuChild .= '</ul>';
            $menu .= '<li>';
            if(array_key_exists($vectorMenuParent[$f]['MenId'], $checksSaved)){
                $valueCheck = $checksSaved[$vectorMenuParent[$f]['MenId']] ? '1' : '';
                $menu .= $input->checkbox(
                    $vectorMenuParent[$f]['MenName'], 
                    $valueCheck, 
                    'input_'.$vectorMenuParent[$f]['MenId'], 
                    $vectorMenuParent[$f]['MenId'], 
                    'marcarinputshijos(this);', 
                    'check_padre', 
                    '200px'
                );

            }
            $menu .= $listMenuChild;
            $menu .= "</li>";
            
        } // cierre primer for

        $menu .= "</ul>";

        // Campos ocultos
        $hidden = appInputTypeHidden('typeUser', $recordEdit);

                
        // Botón cancelar
        $btnCancel = $this->Button('button', 'btnCancel', 'btn btn-outline-secondary btn-block', "history.back();", $icon['Return'], $langResource['Link']['FormBtnCancel']);
        // Botón enviar
        $btnSubmit = $this->Button('submit', 'editProfile', 'btn btn-outline-primary btn-block', '', 'fas fa-edit', $langResource['Link']['btnSaveProfiling']);

        $btnSubmits = '<div class="container"><div class="row">';
        $btnSubmits .= '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 py-1">'.$btnCancel.'</div>';
        $btnSubmits .= '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 py-1">'.$btnSubmit.'</div>';
        $btnSubmits .= '</div></div>';

        return [
            'menu' => $menu,
            'hidden' => $hidden,
            'submits' => $btnSubmits
        ];

    }

    // Formulario para editar perfilación
    public function appShowFormEditProfiling($action, $recordEdit){

        // Llamado de variables superglobales
        global $tableFields;
        global $langResource;
        global $icon;

        // Definición de variables
        $fields = null;
        $confirm = null;
        $typeButton = 'submit';
        
        // Campos del formulario
        $sql = "SELECT ProId, UseTypDescription as typeUserProfile, MenName as affectMenuProile, ProRead, ProCreate, ProUpdate, ProDelete, ProReports, ProWhere, ProJoin, ProStatusProcess from profiles join usertypes on UseTypId = ProUserType join menu on MenId = ProMenu where ProId = '$recordEdit'";
        $result = AppQuery::Row($sql);

        if(!$result){
            die(appAlert(42, '', 'No tiene un perfil generado'));
        }

        foreach ($result['data'] as $key => $fila) {
            $fields .= $this->appShowformFields($key, $fila, $action, $recordEdit);        
        }
        
        $recordEdit = $result['data'][$this->infoPage['keyField']];

        // Campos ocultos
        $hidden = appInputTypeHidden('recordEdit', $recordEdit);

        // Botón Cancelar
        $btnCancel = $this->Button('button', 'btnCancel', 'btn btn-outline-secondary btn-block', "history.back();", $icon['Return'], $langResource['Link']['FormBtnCancel']);
        
        // Botón agregar
        $confirm = $this->MsgConfirm($langResource['Info']['popupUpdateProfiling'], 'update');
        $btnSubmit = $this->profile[$this->infoPage['idProfile']]['create'] ? $this->Button($confirm['typeButton'], 'editProfile', 'btn btn-outline-primary btn-block', $confirm['actionConfirm'], 'fas fa-edit', $langResource['Link']['btnSaveProfiling']) : null;

        $btnSubmits  = '<div class="container"><div class="row">';
        $btnSubmits .= '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 py-1">'.$btnCancel.'</div>';
        $btnSubmits .= '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 py-1">'.$btnSubmit.'</div>';
        $btnSubmits .= '</div></div>';

        return[
            'fields' => $fields,
            'hidden' => $hidden,
            'submits' => $btnSubmits
        ];

    } // End trait creo que se puede borrar

    // FORMULARIO PARA CARGA DE ARCHIVOS
    public function appShowFormLoadFiles($action, $field){

        global $langResource;

        $hidden = null;

        // Campos de formulario
        $form = $this->file($field, 12, '', false);

        // Campos ocultos
        foreach ($this->hiddenFields as $clave => $valor) {
            $hidden .= appInputTypeHidden($clave, $valor);
        }
        $hidden .= appInputTypeHidden('MAX_FILE_SIZE', APP_MAX_FILE_SIZE);

        // Submits
        // Cancelar
        $btnCancel = appbutton('button', 'btn-cancel', 'btn btn-outline-secondary btn-block ', 'history.back(-1);', 'fas fa-undo', $langResource['Link']['FormBtnCancel']);

        // Agregar
        $btnSubmit = appbutton('submit', 'Accion', 'btn btn-outline-primary btn-block ', '', 'fas fa-edit', $langResource['Link']['FormBtnLoadFile']);

        $submits = '<div class="container"><div class="row"><div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 py-1">'.$btnCancel.'</div><div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 py-1">'.$btnSubmit.'</div></div></div>';


        // Llamado del formulario
        return [
            'fields' => $form,
            'hidden' => $hidden,
            'submits' => $submits
        ];

    }

    // METODO QUE RETORNA EL FORMULARIO DE CARGA DE ARCHIVOS POR MEDIO FTP
    public function appShowFormLoadFilesFTP($action){

        $headerPage = appHeaderPage('', '', 'body');
        $footPage = appFootPage('', 'body');

        $idPage = AppToken::Input($_SESSION['appSessionUseId']);

        $formFields = InputFile("", 12, 'value', true);

        // "<em class=\"fas fa-paperclip\"></em> Seleccione los archivos", "File", "", "loadFile", "loadFile", "anexe su documento", "Presione el botón anexar para cargar su archivo", "10", "",  "", "", "", "", "", "", "", "", "", "", "", "",

        $buttons = appbutton('submit', 'action', 'btn btn-outline-primary '.APP_SIZE_BUTTON, '', $icon['LoadFile'], 'Load file');

        $msgHelpForm = $this->MsgHelpGrid('Seleccione sus anexos y luego de click en load file');

        $formTitle = 'Carga de archivos por FTP';

        $action = "../CargaArchivos/CargaArchivoFTP.php";

        $txtRequireFields = $this->TextRequiredFields();        

        $page = appDrawMainForm('', $msgHelpForm, $txtRequireFields, $action, $formFields, $hiddenFields='', $idPage, $buttons);

        die($page);
    }

    private function ReturnValuesDependentDropDownList($key, $infoPage, $recordEdit, $action){

        global $relationships;
        global $langResource;

        $arrayValues = [];
        $arrayKeys = [];
        $valueSave = $langResource['Input']['DefaultValueSelect'];
        $keySave = null;

        // --- Extrae del array de relaciones, los campos asociados a ésta tabla mediante llaves foráneas
        $secondaryTable = $relationships[$key]['secondaryTable'];
        $secKeyField = $relationships[$key]['secKeyField'];
        $secValueField = $relationships[$key]['secValueField'];
        $deleteFieldFK = $relationships[$key]['secDeleteField'];

        if(empty($secondaryTable) || empty($secKeyField) || empty($secValueField) || empty($deleteFieldFK)){
            appPrint('El valor de las relaciones foraneas no esta definido, in file: '.__FILE__.', line: '.__LINE__, 1);
        }


        if($action != "showFormAdd"){
            $sql = "SELECT {$key} FROM {$infoPage['sqlTable']} WHERE {$infoPage['keyField']} = '{$recordEdit}'";
            $keySave = AppQuery::Value($sql);
            $sql2 = "SELECT (SELECT {$secValueField} FROM {$secondaryTable} WHERE {$secKeyField} = '{$keySave}') {$key} FROM {$infoPage['sqlTable']}";
            $valueSave = AppQuery::Value($sql2);
        }


        return [
            'arrayValues' => $arrayValues,
            'arrayKeys' => $arrayKeys,
            'valueSave' => $valueSave,
            'keySave' => $keySave
        ];

    }

    */
        
}