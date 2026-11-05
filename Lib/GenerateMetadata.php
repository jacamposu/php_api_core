<?php 

class GenerateMetadata
{

	public function HttpCodes()
	{
		$result = $this->model->prueba();

		$metadata = '{"code":{';

		foreach ($result->data as $key => $row) {
			
$metadata .= '
	"'.$row["HtCoMessage"].'":{
		"message":"'.$row["LanResSpanish"].'", 
		"color":"'.$row["HtCoColor"].'", 
		"icon":"'.$row["IcoFontAwesome"].'"
	},';
	
		}

$metadata .= "}}";

$fileName = 'C:HttpCodes.json';
$dataFile = $metadata;

			if(file_exists($fileName)){
            unlink($fileName);
        }

        if($file = fopen($fileName, "a")){
            if(fwrite($file, $dataFile)){
                fclose($file);
                return true;
                die();
            }else{
                fclose($file);
                return false;
            }
        }
		print_r("fin"); die();

	}


	public function prueba()
	{
		$sql = "SELECT HtCoMessage, LanResSpanish, HtCoColor, IcoFontAwesome 
from prototipologs.httpcodes
join prototipologs.langresources on LanResItem=HtCoMessage and LanResResource='AlertCode'
join prototipo.icons on HtCoIcon=IcoName 
where prototipologs.httpcodes.deleted_at is null";

		return $this->All($sql, [], PDO::FETCH_ASSOC);	
	}



	public function Dictionary()
	{
		$result = $this->model->prueba();

		$metadata = "{";

		//$row = $result['data'];

		 foreach ($result['data'] as $key => $row) {
			
$metadata .= '
	"'.$row["DicFieldName"].'":{
		"table":"'.$row["DicTable"].'", 
		"fieldName":"'.$row["DicFieldName"].'", 
		"fieldType":"'.$row["FieTypDescription"].'", 
		"length":"'.$row["DicLength"].'", 
		"desc":"'.$row["LanDicSpanishDesc"].'", 
		"format":"'.$row["DicFormat"].'", 
		"align":"'.$row["AliDescription"].'", 
		"inputType":"'.$row["InpTypDescription"].'", 
		"inputTypeForm":"'.$row["InpTypFrmDescription"].'", 
		"required":"'.$row["DicRequired"].'", 
		"unsigned":"'.$row["DicUnsigned"].'", 
		"placeholder":"'.$row["LanDicSpanishDesc"].'", 
		"textareaChars":"'.$row["DicTextareaChars"].'", 
		"textareaRows":"'.$row["DicTextareaRows"].'", 
		"icon":"'.$row["IcoFontAwesome"].'", 
		"disabled":"'.$row["DicDisabled"].'", 
		"readonly":"'.$row["DicReadOnly"].'", 
		"dependentList":"'.$row["DicDependentList"].'", 
		"pattern":"'.$row["DicPattern"].'", 
		"validationForm":"'.$row["DicValidationForm"].'", 
		"helpField":"'.$row["LanDicSpanishHelp"].'"
	},';
}

$metadata .= "}";

$fileName = 'C:Dictionary.json';
$dataFile = $metadata;

			if(file_exists($fileName)){
            unlink($fileName);
        }

        if($file = fopen($fileName, "a")){
            if(fwrite($file, $dataFile)){
                fclose($file);
                return true;
                die();
            }else{
                fclose($file);
                return false;
            }
        }
		print_r("fin"); die();

	}



	public function prueba()
	{
		$sql = "SELECT DicTable, DicFieldName,
		ld.LanDicSpanishDesc, ld.LanDicSpanishHelp,
		 FieTypDescription, DicLength, DicFormat, AliDescription, 
				InpTypDescription, InpTypFrmDescription, DicRequired, DicUnsigned, DicTextareaChars, 
				DicTextareaRows, IcoFontAwesome, DicDisabled, DicReadOnly, DicDependentList, DicPattern, 
				DicValidationForm 
				FROM dictionary 
					JOIN fieldtype ON FieTypId=DicFieldType 
					JOIN align ON AliId=DicAlign 
					JOIN inputtype ON InpTypId=DicInputType 
					JOIN inputtypeform ON InpTypFrmId=DicInputTypeForm 
					JOIN icons ON IcoName=DicIcon 
					JOIN prototipologs.langdictionary ld ON DicFieldName=ld.LanDicField
				WHERE dictionary.deleted_at IS NULL";

		return $this->All($sql, [], PDO::FETCH_ASSOC);	
	}


	public function GetError()
	{
		$read = file_get_contents("C:\\Desarrollos\\AppStorage\\Errors\\Database\\2022-09\\631974c382866.log");
		echo "<pre>";
		print_r(json_decode($read, true));
	}
	
}

?>