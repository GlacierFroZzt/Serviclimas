<?php
// login.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
session_start();

require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL));
    $password = $_POST['password'] ?? '';

    if ($correo && !empty($password)) {
        try {
            // Buscamos al cliente por su correo
            $stmt = $pdo->prepare("SELECT id, nombre, password FROM clientes WHERE correo = ? LIMIT 1");
            $stmt->execute([$correo]);
            $user = $stmt->fetch();

            // Verificación segura del hash de la contraseña
            if ($user && password_verify($password, $user['password'])) {
                
                // BOMBAZO ANTI-FIJACIÓN: Borra la sesión vieja y crea una nueva con ID aleatorio
                session_regenerate_id(true);
                
                $_SESSION['cliente_id'] = intval($user['id']);
                $_SESSION['cliente_nombre'] = $user['nombre'];
                
                header('Location: perfil.php');
                exit;
            } else {
                $error = "Credenciales incorrectas, viejo. Intente de nuevo.";
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = "Error interno en los servidores de acceso.";
        }
    } else {
        $error = "Por favor, llene los campos con un formato de correo válido.";
    }
}

// Jalar el cascarón visual
include_once '../vistas/login.html.php';
?>