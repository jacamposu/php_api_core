<?php 
/**
* ----------------------------------------------------------
* Class DATABASE_FUNCTIONS
* @return queries
* @version 1.0
* @package Model
* @author Jose Antonio Campos Urquijo
* @copyright  Copyright (c)
* ----------------------------------------------------------
*/


class ValidateForm
{
	private $post;
	private $datafield;
	private $msgError;
	private $msgErrors;
	private $dictionary;
	private $_lang;
	private $_loader;
	private array $_headers;

	public function __construct(mixed $post, array $headers)
	{
		$loader = new LoaderResources();
		$this->_loader = $loader;
		$this->_headers = $headers;
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->dictionary = $loader->GetDictionary(true);
		$this->msgErrors = [];
		$this->msgError = [];

		// Retorna el post sin caracteres especiales ni espacios
		foreach ($post as $key => $value) 
		{
			$this->post[$key] = /*ScapeStr::Clean(*/$value/*)*/;
		}
	}

	public function validateForm()
	{
		foreach ($this->post['params'] as $key => $value) 
		{
			if($key == 'keyfield') continue;
			
			// data dictionary
			$field = $this->dictionary[$key];

			// mandatory fields and empty value field
			$this->EmptyField($field,$value);
			
			if(!empty($value))
			{
				// length validate
				$this->MaxLen($field, $value);

				// data type validate
				$this->datatype($field, $value);

				// custom validation
				if(!empty($field['validationForm']))
				{
					$validate = new CustomValidation($value, $field, $this->_headers);
					$validate->{$field['validationForm']}();
					if(!empty($validate->msgError)) $this->msgError[] = $validate->msgError;
				}
			}
		}

		return count($this->msgError) > 0 ? ['error' => $this->msgError, 'text' => 'Se encontraron ('.count($this->msgError).') errores en el formulario, por favor revise y corrija cada uno de ellos.'] : ['post' => $this->post];
	}

	private function datatype($field, $value)
	{
		switch ($field['fieldType']) {
			case 'CHAR':
			case 'VARCHAR':
			case 'LONGTEXT':
				$RegexCharVarchar = '/^[a-zA-Z���������������������������������������������������~�acceACCEeiEIlnLN�UUY�ZZ��O�CSZ0-9#@.\-\/ ()_]{1,'.$field['length'].'}$/';
				if(!preg_match($RegexCharVarchar, $value, $matches))
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
				break;

			case 'DATE':
				$RegexDate = '/^[0-9]{4}-[0|1][0-9]-[0-3][0-9]/';
	            if(!preg_match($RegexDate, $value))
	            {
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
	            }
	            else
	            {
	                list($year, $month, $day) = preg_split('/\D+/', $value);
	                $dateValidation = checkdate($month, $day, $year);
	                if(!$dateValidation)
						$this->ErrorWrongDate($value, $field['desc']);
	            }
	            break;
			
			case 'DATETIME':
				$RegexDatetime = '/^[0-9]{4}-[0|1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]/';
				if(!preg_match($RegexDatetime, $value))
				{
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
	            }
	            else
	            {
	                list($year, $month, $day, $hour, $minutes, $seconds) = preg_split('/\D+/', $value);
	                $dateValidate = checkdate($month, $day, $year);
	                $hourValidate = ($hour < 0 || $hour > 23 || $minutes < 0 || $minutes > 59 || $seconds < 0 || $seconds > 59) ? false : true;
	                if (!$dateValidate || !$hourValidate) 
						$this->ErrorWrongDate($value, $field['desc']);
	            }
				break;

			case 'DECIMAL':
			case 'FLOAT':
			case 'NUMERIC':
				if (strval((float)$value) != $value) 
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
				break;

			case 'BIGINT':
			case 'TINYINT':
			case 'INTEGER':
	        case 'SMALLINT':
	            $RegexInt = '/^[-\+]?[0-9]{1,'.$field['length'].'}$/';
				if(!preg_match($RegexInt, $value))
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
				
				if (strval((int)$value) != $value) 
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
	            break;

	        case 'BOOL':
				$RegexBool = '/^[0-1]{1}$/';
				if(!preg_match($RegexBool, $value))
					$this->ErrorDataType($value, $field['desc'], $field['fieldType']);
				break;
		}
	}

	private function ErrorDataType(string $value, string $field, string $datatype):string
	{
		$errorDataType = str_replace("{{value}}", $value, $this->_lang->Validate->errorDataType);
		$errorDataType = str_replace("{{field}}", $field, $errorDataType);
		return str_replace("{{datatype}}", $datatype, $errorDataType);
	}

	private function ErrorWrongDate(string $value, string $field)
	{
		$errorWrongDate = str_replace("{{value}}", $value, $this->_lang->Validate->wrongDate);
	    $this->msgError[][$field['fieldName']] = str_replace("{{field}}", $field, $errorWrongDate);
	}

	private function EmptyField($field, $value)
	{
		if($field['required'] && empty($value))
			$this->msgError[][$field['fieldName']][] = str_replace("{{desc}}", $field['desc'], $this->_lang->Validate->emptyField);
			
	}

	private function MaxLen($field, $value)
	{
		if(!empty($field['length']) && strlen($value) > $field['length'])
		{
			$msg = str_replace("{{desc}}", $field['desc'], $this->_lang->Validate->exceedsLength);
			$msg = str_replace("{{length}}", $field['length'], $msg);
			$this->msgError[][$field['fieldName']][] = $msg;
		}
	}
}