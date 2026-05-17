<?php
// admin_logout.php
session_start();

// Vaciamos todas las variables de memoria por completo
$_SESSION = array();

// Si el servidor usa cookies de sesión (lo estándar), las caducamos en caliente de una vez
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruimos la sesión en el servidor
session_destroy();

// Redirección directa al login pelón de administración
header("Location: admin_login.php");
exit;
?>