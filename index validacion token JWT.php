
<?php
require __DIR__ . '/vendor/autoload.php'; // Ruta a la carpeta donde se encuentra la librería Firebase JWT

use \Firebase\JWT\JWT;

// Función para validar un token JWT y su expiración
function validarToken($token, $claveSecreta) {
    try {
        // Decodificar el token JWT con la clave secreta
        $decoded = JWT::decode($token, $claveSecreta, array('HS256'));
        
        // Verificar la expiración del token
        $ahora = time();
        if ($decoded->exp > $ahora) {
            return $decoded; // El token es válido y no ha expirado
        } else {
            return null; // El token ha expirado
        }
    } catch (Exception $e) {
        return null; // El token no es válido
    }
}

// Ejemplo de uso
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiZXhwIjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";
$claveSecreta = "secreto";

$tokenValidado = validarToken($token, $claveSecreta);

if ($tokenValidado) {
    echo "El token es válido.";
    var_dump($tokenValidado); // Aquí puedes acceder a los datos del token decodificado
} else {
    echo "El token no es válido o ha expirado.";
}
?>