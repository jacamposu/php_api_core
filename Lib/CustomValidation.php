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

class CustomValidation
{
	private $_lang;
	private $_value;
	private $_field;
	public $msgError;
	private $_password_min;
	private $_password_max;
	private $_textarea_max;
	private $_minYear;
	private $_maxlen_cell;
	private $_minLen_doc;

	public function __construct(string $value, array $field, array $headers)	
	{
		$loader = new LoaderResources();
		$this->_lang = $loader->GetLang($headers['user_lang']);
		$this->_value = $value;
		$this->_field = $field;
		$this->msgError = [];
		$this->_password_min = 6;
		$this->_password_max = 16;
		$this->_textarea_max = 200;
		$this->_minYear = 1900;
		$this->_maxlen_cell = 10;
		$this->_minLen_doc = 4;
	}

	public function ValidarNumeroDocumento()
	{
		$regex = '/^[1-9][0-9]+$/';

		if (!preg_match($regex, $this->_value))
			$this->messageError([
				'message' => $this->_lang->CustomValidation->wrong_document,
				'value' => $this->_value
			]);
		

		if(strlen($this->_value) < $this->_minLen_doc || strlen($this->_value) > $this->_field['length'])
			$this->messageError([
				'message' => $this->_lang->CustomValidation->min_max_document,
				'value' => $this->_value,
				'minLengthDoc' => $this->_minLen_doc,
				'length' => $this->_field['length']
			]);

		if(!$this->isValidCardId($this->_value))
			$this->messageError([
				'message' => $this->_lang->CustomValidation->nonZero_document,
				'value' => $this->_value
			]);

		if(strlen($this->_value) == 9)
			$this->messageError([
				'message' => $this->_lang->CustomValidation->nine_digits_document,
				'value' => $this->_value
			]);
	}

	public function ValidarNombresApellidos():void
	{
		if($this->_field['required'] || !empty($this->_value))
		{
			$regex = '/^[a-zA-Z����������������������������������������������������~�acceACCEeiEIlnLN�UUY�ZZ��O�CSZ ]{2,'.$this->_field['length'].'}$/';
			if(!preg_match($regex, $this->_value))
				$this->messageError([
					'message' => $this->_lang->CustomValidation->errorName,
					'desc' => $this->_field['desc']
				]);			
		}
	}

	public function ValidarDireccion():void
	{
		if(!$this->_field['required'] && !empty($this->_value))
		{
			$regex = '/^[\a-zA-Z1234567890#-\/� ]{2,'.$this->_field['length'].'}$/';
			if (!preg_match($regex, $this->_value))
				$this->msgError[$this->_field['fieldName']] = $this->_lang->CustomValidation->errorAddress;
		}
	}

	public function ValidarNumeroCelular():void
	{
		$regex = '/^[3][0-9]+$/';
		
		// Hace las validaciones solo cuando el campo es requerido o tiene datos.
		if($this->_field['required'] || !empty($this->_value))
		{
			if(empty($this->_value))
				$this->messageError([
					'message' => $this->_lang->CustomValidation->emptyField,
					'field' => $this->_field['desc']
				]);

			if (!preg_match($regex, $this->_value)) 
				$this->messageError([
					'message' => $this->_lang->CustomValidation->nonStartCell,
					'desc' => $this->_field['desc'],
					'value' => $this->_value
				]);

			if(strlen($this->_value)>$this->_maxlen_cell)
				$this->messageError([
					'message' => $this->_lang->CustomValidation->maxLengthCell,
					'desc' => $this->_field['desc'],
					'value' => $this->_value,
					'length' => $this->_field['length']
				]);
			
			if(strlen($this->_value)<$this->_maxlen_cell)
				$this->messageError([
					'message' => $this->_lang->CustomValidation->minLengthCell,
					'desc' => $this->_field['desc'],
					'value' => $this->_value,
					'length' => $this->_field['length']
				]);
		}
	}

	public function ValidarNumeroTelefono():void
	{
		$regex = '/^[2-9][0-9]+$/';
		
		if($this->_field['required'] && empty($this->_value))
			$this->messageError([
				'message' => $this->_lang->CustomValidation->emptyField,
				'field' => $this->_field['desc'],
				'value' => $this->_value,
				'length' => $this->_field['length']
			]);

		if (!empty($this->_value)) 
		{
			if (!preg_match($regex,$this->_value)) 
				$this->messageError([
					'message' => $this->_lang->CustomValidation->wrongNumberPhone,
					'desc' => $this->_field['desc'],
					'value' => $this->_value,
					'length' => $this->_field['length']
				]);
		}

		if(strlen($this->_value)>$this->_field['length'])
			$this->messageError([
				'message' => $this->_lang->CustomValidation->minLengthNumberPhone,
				'desc' => $this->_field['desc'],
				'value' => $this->_value,
				'length' => $this->_field['length']
			]);
		
		if(strlen($this->_value)<$this->_field['length'])
			$this->messageError([
				'message' => $this->_lang->CustomValidation->maxLengthNumberPhone,
				'desc' => $this->_field['desc'],
				'value' => $this->_value,
				'length' => $this->_field['length']
			]);
	}

	public function ValidarCorreoElectronico():void
	{
		if (!empty($this->_value)) 
		{
			$regex = '/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/';
			if (!preg_match($regex,$this->_value)) 
				$this->messageError([
					'message' => $this->_lang->CustomValidation->errorEmail,
					'value' => $this->_value
				]);
		}
	}

	public function ValidarPassword ():void
	{
		if($this->_field['required'] && empty($this->_value))
			$this->messageError([
				'message' => $this->_lang->CustomValidation->emptyField,
				'desc' => $this->_field['desc']
			]);
		
		if($this->IsValidPassword($this->_value))
			$this->messageError([
				'message' => $this->_lang->CustomValidation->incorrectPassword,
				'min' => $this->_password_min,
				'min' => $this->_password_max,
			]);
	}

	public function ValidarFecha ():void
	{
		if($this->_field['required'] && empty($this->_value)){
			$this->msgError[$this->_field['fieldName']][] = $this->_lang->CustomValidation->emptyDate;
		}

		if(!empty($this->_value))
		{
			$Regex = '/[-]/';
			
			if(preg_match($Regex, $this->_value))
				list($year, $month, $day) = explode("-", $this->_value);
			else
				list($year, $month, $day) = explode("/", $this->_value);

			if(empty($year) || empty($month) || empty($day))
				$this->msgError[$this->_field['fieldName']][] = $this->_lang->CustomValidation->incorrectDate;

			if($year < $this->_minYear || $month < 1 || $month > 12 || $day < 1 || $day > 31)
				$this->msgError[$this->_field['fieldName']][] = $this->_lang->CustomValidation->incorrectDate;
		}
	}

	private function isValidCardId($value):bool
	{
		if(strlen($value)===10 && strpos($value, '1')===0){
			return true;	
		}else{
			return true;
		}
	}

	private function IsValidPassword($password):bool
	{
		if(strlen($password) < $this->_password_min || 
			strlen($password) > $this->_password_max || 
			!preg_match('`[a-z]`',$password) || 
			!preg_match('`[A-Z]`',$password) || 
			!preg_match('`[0-9]`',$password))
			return false;

		return true;
	}

	private function isValidLengthTextarea($length):bool
	{
		if(strlen($length) > $this->_textarea_max)
			return false;
		
		return true;
	}

	private function messageError(array $array)
	{
		$message = $array['message'];
		unset($array['message']);
		$error = 0;
		$msg_error = '';
		foreach($array as $key => $value)
		{
			if($error == 0)
				$msg_error = str_replace('{{'.$key.'}}', $value, $message);
			else
				$msg_error = str_replace('{{'.$key.'}}', $value, $msg_error);

			$error++;
		}
		$this->msgError[$this->_field['fieldName']][] = $msg_error;
	}
}