<?php 
/**
* Generador de reportes
* @param Csv
* @param Xml
* @param Txt
* @param Excel
* @param Pdf
* @author Jose Antonio Campos Urquijo
*/

class Downloads
{
	private $result;
	private $limit;
	private $columns;
	private $join;

	public function __construct()
	{
		$relationships = json_decode(file_get_contents(), true);
		$concatenate = json_decode(file_get_contents(), true);
		$langResource = json_decode(file_get_contents(), true);
		$profile = json_decode(file_get_contents(), true);
		$config = json_decode(file_get_contents(), true);

		ini_set("memory_limit", "500M");
      set_time_limit(80000000000);
      $this->result = $result;

      $limit = null;
		$columns = null;
		$join = null;
	}

	public function VerifyDataInput()
	{
		$file = Util::VarPOST('file');
		$table = Util::VarPOST('table');
		$page = Util::VarPOST('Pag');
		$criteria = Util::VarPOST('DownloadsCriteria');

		// Verificación de tabla
		if($this->infoPage['sqlTable'] <> $table)
		{
			throw new Exception($langResource['Error']['DownloadsDiffTable'], 41);
		}

		// Borrado de campos no necesarios
		unset(
			$_POST['action'], 
			$_POST['table'], 
			$_POST['Pag'], 
			$_POST['file'], 
			$_POST['DownloadsCriteria'], 
			$_POST['url']
		);

		// Verificación de la variable [$_GET]
		if(empty($_POST))
		{
			throw new Exception($langResource['Info']['EmptyFieldsDwn'], 41);
		}

	}


	public function PrepareQuery()
	{
		// Extrae los campos del buscador
		$conditionSearch = null;
		foreach ($_POST as $key => $value) 
		{
			if(strpos($key, 'earch_'))
			{
				list($prefix, $fieldGet) = explode('_', $key);
				$conditionSearch .= 'AND '.$fieldGet.'='.$value;
				unset($_POST[$key]);
			}
		}

		// Obtiene las columnas a exportar
		foreach ($_POST as $key => $value) 
		{
			if(!empty($relationships[$key]['secondaryTable']))
			{
				$join .= 'join '.$relationships[$key]['secondaryTable'].' on '.$relationships[$key]['secKeyField'].' = '.$key.' ';
				$key = $relationships[$key]['secValueField'].' as '.$key;
			}
			if($value)
			{
				$columns .= $key.", ";
			}
		}

		if(!is_null($columns))
		{
			$columns = rtrim($columns, ", ");
		}

		// Establece el número de pagina
		if($criteria==='thisPage'){
			$numPage = (!$page ? 1 : $page == 1) ? 1 : $page;
			$startFrom = ($numPage - 1) * $config->app->rowsByPage;
			$limit = "LIMIT $startFrom,".$config->app->rowsByPage;
		}

		// Construcción del query		
		$query = "SELECT {$columns} FROM {$table} $join WHERE {$table}.deleted_at IS NULL {$conditionSearch} {$limit}";
		$result = $db->All($query, null, null, $this->strQuery['dbAlternative']);

		// Caso: consulta vacía
      	if(empty($result['data'] ))
      	{
        	appPrint('Downloads say: The query is empty, in file: '.__FILE__.', line: '.__LINE__);
      	}
	}

	public function CreateLog()
	{
		// Creacion de log en base de datos
		$tableLog = 'reports';
		$sql = "INSERT INTO {$tableLog} (RepResource, RepTypeFile, RepUser, RepIp, created_at) VALUES (?,?,?,?,?);";
		$values = [$table, $file, $_SESSION['appSessionUseId'], Util::GetIP(), date('Y-m-d H:i:s')];
		$db->insert($sql, $values, $tableLog, 'db2');
	}


	public function Report()
	{
		switch ($file) 
		{
			case 'CSV':
				$fileName = $dwn->GetFileNameDownloads($table, 'csv');
				$dwn->Csv($fileName);
				break;
			
			case 'Excel':
				$fileName = $dwn->GetFileNameDownloads($table, 'xls');
				$dwn->Excel($fileName);
				break;

			case 'PDF':
				$fileName = $dwn->GetFileNameDownloads($table, 'pdf');
				$dwn->Pdf($fileName, $table);
				break;

			case 'TXT':
				$fileName = $dwn->GetFileNameDownloads($table, 'txt');
				debug('reporte no disponible en el momento',1);
				$dwn->Txt($fileName);
				break;

			case 'XML':
				$fileName = $dwn->GetFileNameDownloads($table, 'xml');
				$dwn->Xml($fileName, $table);
				break;
		}
	}

	public function GetFileNameDownloads($table, $extension)
   	{
      	return 'REPORT_U-'.strtoupper($_SESSION['appSessionUseId']).'_T-'.strtoupper($table).'_D-'.date('YmdHis') .'.'.$extension;
   	}

   // DESCARGA DE REPORTES EN FORMATO (CSV)
   public function Csv($fileName)
   {
      global $dictionary;
      $showHeader = true;
      header("Content-Type: text/x-csv");
      header("Content-Disposition: attachment; filename=".$fileName);
      foreach ($this->result['data'] as $row) 
      {
         $Registros[] = $row;
         if(!empty($row))
         {
            if($showHeader) 
            {
               $columnName = array_keys($row);
               $columnCount = sizeof($columnName);
               $i = 0;
               foreach ($columnName as $value) 
               {
                  if($i < ($columnCount-1))
                  {
                     echo strtoupper($dictionary[$value]['desc']).", ";
                     $i ++;
                  }
                  else
                  {
                     echo strtoupper($dictionary[$value]['desc'])."\n";
                     $i ++;
                  }
               }
               $showHeader = false;
            }
            echo implode(", ", array_values($row)) . "\n";
         }
      }
   }

   // -----------------------------------------------------------------------

   // DESCARGA DE REPORTES EN FORMATO (EXCEL)
   public function Excel($fileName)
   {
      global $dictionary;
      $showHeader = true;

      header("Content-Type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=".$fileName);

      foreach ($this->result['data'] as $row) 
      {
         $Registros[] = $row;
         if(!empty($row))
         {
            if($showHeader) 
            {
               $ColumnName = array_keys($row);
               $NroColumnas = sizeof($ColumnName);
               $Contador = 0;
               foreach ($ColumnName as $value) 
               {
                  if($Contador < ($NroColumnas-1))
                  {
                     echo strtoupper($dictionary[$value]['desc'])."\t";
                     $Contador ++;
                  }
                  else
                  {
                     echo strtoupper($dictionary[$value]['desc'])."\n";
                     $Contador ++;
                  }
               }
               $showHeader = false;
            }
            echo implode("\t", array_values($row)) . "\n";
         }
      }
   }

   // -----------------------------------------------------------------------

   // DESCARGA DE REPORTES EN FORMATO (PDF)
   public function Pdf($NombreArchivo, $table)
   {
      global $config;
      global $dictionary;
      // includes
      require_once $config->path->app.'/Core/Public/Plugins/fpdf/fpdf.php';
      require_once 'PDF.php';
      // Variables
      $title = 'Report_'.$table;
      $fontFamily = 'arial';
      $fontSize = '12';
      $pageSize = 'letter';
      $orientation = $this->result['columnCount']>4 ? 'L' : 'P';
      // Generación del reporte
      $pdf = new PDF($orientation, 'mm', $pageSize);
      $pdf->PDFParams($title, $orientation, $fontFamily, $fontSize);
      $pdf->AddPage();
      $pdf->AliasNbPages();
      // Extrae el ancho de la data y forma un array
      foreach ($this->result['data'] as $key => $value) 
      {
         for ($i=0; $i < count($this->result['columnName']); $i++) 
         {
            $width[$this->result['columnName'][$i]][] = strlen($value[$this->result['columnName'][$i]]);
         }
      }
      // Extrae el ancho de los encabezados y forma un array
      for($i=0;$i<count($this->result['columnName']);$i++)
      {
         array_push($width[$this->result['columnName'][$i]], strlen($dictionary[$this->result['columnName'][$i]]['desc']));
      }
      // Forma un array con los valores maximos de cada campo
      foreach ($width as $key => $value) 
      {
         $maximo[$key] = max($width[$key]);
      }  
      // Imprime la cabecera en el PDF
      $pdf->PDFReportTitle("#",2);
      for($i=0;$i<count($this->result['columnName']);$i++)
      {
         $tamanio = $maximo[$this->result['columnName'][$i]];
         $NombreCampo = $dictionary[$this->result['columnName'][$i]]['desc']; 
         $pdf->PDFReportTitle($NombreCampo, $tamanio);
      }
      // Data
      foreach($this->result['data'] as $key => $value)
      {
         $pdf->Ln();
         $pdf->PDFCreatingDetails(true);
         for ($i=0; $i < count($this->result['columnName']); $i++) 
         {
            $valorCampo = $value[$this->result['columnName'][$i]];
            $tamanio = $maximo[$this->result['columnName'][$i]];
            $pdf->PDFReportDetail($valorCampo, $tamanio);
         }     
      }
      // Imprime el documento
      $pdf->Output($NombreArchivo, 'D');
   }

   // DESCARGA DE REPORTES EN FORMATO (TXT)
   public function Txt($fileName)
   {
      global $dictionary;
      $data = null;
      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="'.$fileName.'"');
      header('Content-Description: File Transfer');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      foreach ($this->result['columnName'] as $value) 
      {
         $data .= strtoupper($dictionary[$value]['desc'])."\t";
      }
      if(!is_null($data))
      {
         $data = rtrim($data, "\t");
         $data = $data."\n";
      }
      
      foreach ($this->result['data'] as $row) 
      {
         echo implode("\t", array_values($row))."\n";
      }
      
      
   }

   // DESCARGA DE REPORTES EN FORMATO (XML)
   public function Xml($fileName, $table)
   {
      header("Content-Type: application/xml");
      header("Content-Disposition: attachment; filename=".$fileName);
      // --- Genera el xml con la consulta
      $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
      $xml .= "<{$table}>\n";
      for ($j=0; $j < count($this->result['data']); $j++) 
      { 
         $xml .= "\t<row>\n";
         foreach ($this->result['columnName'] as $column) 
         {
            $xml .= "\t\t<".$column.">".$this->result['data'][$j][$column]."</".$column.">\n";
         }
         $xml .= "\t</row>\n";
      }
      $xml .= "</{$table}>";
      echo $xml;
   }
}


	