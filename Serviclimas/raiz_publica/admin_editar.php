<?php
// admin_editar.php

// 1. CONTROL DE MÁXIMA PRIVACIDAD PARA COOKIES DE SESIÓN
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

// 2. FILTRO DE ACCESO ADMINISTRATIVO CON EXCLUSIÓN ABSOLUTA
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    session_unset();
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

// 3. RE-VERIFICAR HUELLA DIGITAL PARA EVITAR SECUESTRO DE SESIÓN
$huella_actual = md5($_SERVER['HTTP_USER_AGENT'] . (ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.255.0')));
if (!isset($_SESSION['huella_seguridad']) || $_SESSION['huella_seguridad'] !== $huella_actual) {
    session_unset();
    session_destroy();
    header('Location: admin_login.php?error=seguridad');
    exit;
}

// Bloqueo total de caché para que no se queden guardados datos contables en el cel
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$id_orden = intval($_GET['id']);
$mensaje_cambio = "";

// 4. GENERAR TOKEN CSRF SI NO EXISTE EN LA SESIÓN ADMINISTRATIVA
if (empty($_SESSION['admin_csrf_token'])) {
    $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
}

// 5. PROCESAR EN CALIENTE LA ACTUALIZACIÓN DE LA ORDEN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_orden'])) {
    
    // Candado estricto contra falsificación de peticiones en segundo plano
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['admin_csrf_token']) {
        exit('¡Epa, plebe! Acción bloqueada de inmediato por los sistemas de seguridad del búnker.');
    }

    // Validación por lista blanca estricta para el estatus de atención
    $estatus_validos = ['Pendiente', 'Cotizado', 'En Camino', 'Completado'];
    $nuevo_estatus = in_array($_POST['estatus'], $estatus_validos) ? $_POST['estatus'] : 'Pendiente';

    // Validación estricta de fecha mediante formato regex (YYYY-MM-DD o vacío)
    $fecha_programada = null;
    if (!empty($_POST['fecha_programada'])) {
        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['fecha_programada'])) {
            $fecha_programada = $_POST['fecha_programada'];
        }
    }

    // CASTEO NUMÉRICO ESTRICTO CONTRA FRAUDES EN LA CAJA: No aceptamos letras ni números negativos
    $costo_final = abs(floatval($_POST['costo_servicio_final']));

    try {
        $stmtUpdate = $pdo->prepare("
            UPDATE ordenes 
            SET estatus = ?, fecha_programada = ?, costo_servicio_final = ? 
            WHERE id_orden = ?
        ");
        $stmtUpdate->execute([$nuevo_estatus, $fecha_programada, $costo_final, $id_orden]);
        $mensaje_cambio = "success";
        
        // Rotamos de inmediato la clave secreta CSRF para blindar futuras operaciones
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        error_log($e->getMessage());
        $mensaje_cambio = "error";
    }
}

// 6. CARGAR DATOS LIMPIOS PARA LA INTERFAZ DE EDICIÓN
try {
    $stmt = $pdo->prepare("
        SELECT o.*, c.nombre as cliente, c.telefono 
        FROM ordenes o 
        JOIN clientes c ON o.id_cliente = c.id 
        WHERE o.id_orden = ?
        LIMIT 1
    ");
    $stmt->execute([$id_orden]);
    $orden = $stmt->fetch();

    if (!$orden) {
        header('Location: admin_dashboard.php');
        exit;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Fallo del motor de base de datos al jalar la orden seleccionada.');
}

include_once '../admin_editar.html.php';
?>