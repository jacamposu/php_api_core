<?php 

/**
	* @author José Antonio Campos Urquijo
	* @package Funciones de validación formularios
	* @package Motor Base de Datos: MySQL
	*
	* En éste script se construyen las funciones de validación de los formularios que se llaman a través del diccionario de datos (campo[Validacion_Form])
*/

function ValidarNumeroDocumento($post, $datatype, $length, $required, $field, $desc){
	$msgError = null;
	$regex = '/^[1-9][0-9]+$/';
	$minLengthDoc = 4;

	if (!preg_match($regex, $post[$field])){
		$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> es incorrecto debe ser de tipo numérico, no debe tener caracteres especiales y no debe comenzar por cero (0).';
	}

	if(strlen($post[$field])<$minLengthDoc || strlen($post[$field])>$length){
		$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> debe contener entre ('.$minLengthDoc.') y ('.$length.') dígitos.';
	}

	if(!isValidCardId($post[$field])){
		$value = substr($post[$field],0,1);
		$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> no debe comenzar por el número <strong>('.$value.')</strong>.';
	}

	if(strlen($post[$field])==9){
		$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> no debe ser de nueve(9) dígitos.';
	}

	return $msgError;
}


function ValidarNombresApellidos($post, $datatype, $length, $required, $field, $desc){
	$msgError = null;
	if($required || !empty($_POST[$field])){
		$regex = '/^[a-zA-ZàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚâêîôûÂÊÎÔÛäëïöüÄËÏÖÜñÑãõåÃÅÿıÕø¢~çacceACCEeiEIlnLNØUUYİZZßÇOÆCSZ ]{2,'.$length.'}$/';
		if(!preg_match($regex, $_POST[$field])){
			$msgError .= 'El ['.$desc.'] no debe contener números ni caracteres especiales, excepto la tílde.';
		}
	}
	return $msgError;
}

# --- Validaciones para direccion de domicilio
function ValidarDireccion($post, $datatype, $length, $required, $field, $desc){
	$msgError='';
	$regex = '/^[\a-zA-Z1234567890#-\/° ]{2,'.$length.'}$/';

	if(!$required && !empty($post[$field])){
		if (!preg_match($regex, $post[$field])){
			$msgError = 'La ['.$desc.'] debe contener únicamente letras, números y estos signos (#, -, /, °).';
		}
	}

	return $msgError;
}


# --- Validaciones para telefono celular
function ValidarNumeroCelular($post, $datatype, $length, $required, $field, $desc){
	$msgError='';
	$regex = '/^[3][0-9]+$/';
	$lengthCell = 10;

	// Hace las validaciones solo cuando el campo es requerido o tiene datos.
	if($required || !empty($post[$field])){
		if(empty($post[$field])){
			$msgError .= 'El ['.$desc.'] es obligatorio.';
		}

		if (!preg_match($regex, $post[$field])) {
			$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> debe comenzar por el número tres (3).';
		}

		if(strlen($post[$field])>$lengthCell){
			$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> no debe tener más de diez ('.$lengthCell.') dígitos.';
		}

		if(strlen($post[$field])<$lengthCell){
			$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> no debe tener menos de diez ('.$lengthCell.') dígitos.';
		}
	}

	return $msgError;
}


# --- Validaciones para telefono fijo
function ValidarNumeroTelefono($post, $datatype, $length, $required, $field, $desc){
	$msgError='';
	$regex = '/^[2-9][0-9]+$/';
	
	if($required && empty($post[$field])){
		$msgError .= 'El ['.$desc.'] es obligatorio.';
	}

	if (!empty($post[$field])) {
		if (!preg_match($regex,$post[$field])) {
			$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> debe contener '.$length.' dígitos y no debe comenzar por (0) o (1)';
		}
	}

	if(strlen($post[$field])>$length){
		$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> no debe superar los diez ('.$length.') dígitos.';
	}

	if(strlen($post[$field])<$length){
		$msgError .= 'El ['.$desc.'] <strong>('.$post[$field].')</strong> no debe ser menor a diez ('.$length.') dígitos.';
	}

	return $msgError;
}

# --- Validaciones para correo electronico
function ValidarCorreoElectronico ($post, $datatype, $length, $required, $field, $desc){
	$msgError='';
	$regex = '/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/';
	
	if (!empty($post[$field])) {
		if (!preg_match($regex,$post[$field])) {
			$msgError = 'El ['.$desc.'] ('.$post[$field].') debe contener al menos un signo (@), un punto (.) y un dominio.';
		}
	}

	return $msgError;
}


function ValidarPassword ($post, $datatype, $length, $required, $field, $desc){
	$msgError='';
	if($required && empty($post[$field])){
		$msgError .= 'El ['.$desc.'] es obligatorio.';
	}
	$msgError .= IsValidPassword($post[$field], $desc);
	return $msgError;
}


function ValidarFecha ($post, $datatype, $length, $required, $field, $desc){
	$msgError='';
	if($required && empty($post[$field])){
		$msgError .= 'El ['.$desc.'] es obligatorio.';
	}
	$msgError .= IsValidDate($post[$field], $desc);
	return $msgError;
}



?>