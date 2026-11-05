<?php 

/**
	* @author Jos� Antonio Campos Urquijo
	* @package Funciones generales de validaci�n de formularios
	* Corresponde a funciones de apoyo que pueden ser invocadas por ValidateFunctions.
*/



function isValidCardId($value){
	if(strlen($value)===10){
		if(strpos($value, '1')===0){
			return true;
		}
	}else{
		return true;
	}
}


function IsValidPassword($password):bool
{
	$min = 6;
	$max = 16;

	if(strlen($password) < $min || 
		strlen($password) > $max || 
		!preg_match('`[a-z]`',$password) || 
		!preg_match('`[A-Z]`',$password) || 
		!preg_match('`[0-9]`',$password)) return false;
	
	return true;
}


function IsValidDate($date, $desc){
	$msgError = null;
	$Regex = '/[-]/';
	$minYear = 1900;

	if(preg_match($Regex, $date)){
		list($year, $month, $day) = explode("-", $date);
	}else{
		list($year, $month, $day) = explode("/", $date);
	}

	if(empty($year) || empty($month) || empty($day)){
		$msgError .= returnMsgEmptyField($desc);
	}

	if($year < $minYear || $month < 1 || $month > 12 || $day < 1 || $day > 31){
		$msgError .= returnMsgWrongDateDesc($date, $desc, $minYear);
	}

	return $msgError;
}

function isValidLengthTextarea($length){
	$max = 200;
	if(strlen($length)>$max){
		return returnMsgValidateLength($max);
	}
}



