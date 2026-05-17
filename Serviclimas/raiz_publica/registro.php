<?php
// registro.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
session_start();

require_once 'db.php';

// Generar token Anti-CSRF para blindar el formulario de registro externo
if (empty($_SESSION['registro_csrf_token'])) {
    $_SESSION['registro_csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar Token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['registro_csrf_token']) {
        exit('Petición no autorizada por el protocolo de seguridad.');
    }

    $nombre   = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL));
    $telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT));
    $password = $_POST['password'] ?? '';

    if ($nombre && $correo && $telefono && !empty($password)) {
        if (strlen($password) < 8) {
            $error = "La contraseña debe tener mínimo 8 caracteres por seguridad, viejo.";
        } else {
            try {
                // Verificar si el correo ya existe para no duplicar datos
                $stmtCheck = $pdo->prepare("SELECT id FROM clientes WHERE correo = ? LIMIT 1");
                $stmtCheck->execute([$correo]);
                
                if ($stmtCheck->fetch()) {
                    $error = "Ese correo ya está registrado, intente con otro o inicie sesión.";
                } else {
                    // CIFRADO ULTRA-SEGURO CON ARGON2ID (Exclusivo para servidores modernos)
                    $password_hasheado = password_hash($password, PASSWORD_ARGON2ID);

                    $stmtInsert = $pdo->prepare("INSERT INTO clientes (nombre, correo, telefono, password, tipo_usuario) VALUES (?, ?, ?, ?, 'particular')");
                    $stmtInsert->execute([$nombre, $correo, $telefono, $password_hasheado]);

                    $success = "¡Cuenta creada al puro tiro! Ya puede iniciar sesión.";
                    unset($_SESSION['registro_csrf_token']); // Quemamos el token usado
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                $error = "Fallo interno en el motor de registro de usuarios.";
            }
        }
    } else {
        $error = "Por favor, complete todos los campos de forma correcta.";
    }
}

include __DIR__ . '/Vistas/registro.html.php';
?>
