<?php
// admin_login.php
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
            // Buscamos al usuario exigiendo estrictamente que sea del rol administrador
            $stmt = $pdo->prepare("SELECT id, nombre, password, tipo_usuario FROM clientes WHERE correo = ? AND tipo_usuario = 'admin' LIMIT 1");
            $stmt->execute([$correo]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                
                // Limpieza total y arranque del búnker
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = intval($admin['id']);
                $_SESSION['role']    = $admin['tipo_usuario'];
                $_SESSION['nombre']  = $admin['nombre'];
                
                // Amarrar huella digital única basada en el navegador e IP del patrón
                $_SESSION['huella_seguridad'] = md5($_SERVER['HTTP_USER_AGENT'] . (ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.255.0')));

                header('Location: admin_dashboard.php');
                exit;
            } else {
                // Por seguridad disuasoria, soltamos el mismo mensaje genérico si el correo no existe o si falló la contraseña
                $error = "Acceso denegado. Credenciales de administración no válidas.";
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = "Fallo de comunicación con la central de seguridad.";
        }
    } else {
        $error = "Los datos ingresados no cumplen con las normas contables.";
    }
}

include_once '../vistas/admin_login.html.php';
?>