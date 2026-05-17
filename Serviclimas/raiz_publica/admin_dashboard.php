<?php
// admin_dashboard.php

// 1. CONFIGURACIÓN EXTREMA DE PRIVACIDAD PARA COOKIES DE SESIÓN
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

// 2. FILTRO DE ACCESO MAESTRO: Si no es administrador, ¡pafuera de inmediato!
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    session_unset();
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

// 3. SELLO DE HUELLA DIGITAL (Evita el Secuestro de Sesión o Session Hijacking)
$huella_actual = md5($_SERVER['HTTP_USER_AGENT'] . (ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.255.0')));
if (!isset($_SESSION['huella_seguridad'])) {
    $_SESSION['huella_seguridad'] = $huella_actual;
} elseif ($_SESSION['huella_seguridad'] !== $huella_actual) {
    // Si la huella no cuadra, es un comportamiento sospechoso. ¡Tumbamos todo!
    session_unset();
    session_destroy();
    header('Location: admin_login.php?error=seguridad');
    exit;
}

// 4. BLOQUEO RADICAL DE CACHÉ ADMINISTRATIVA
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'db.php';
$fecha_hoy = date('Y-m-d');
$metricas = ['totales_hoy' => 0, 'pendientes' => 0, 'en_camino' => 0];

try {
    // Consultar métricas del día de forma segura
    $stmtMetricas = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN DATE(fecha_creacion) = ? THEN 1 END) as totales_hoy,
            COUNT(CASE WHEN estatus = 'Pendiente' THEN 1 END) as pendientes,
            COUNT(CASE WHEN estatus = 'En Camino' THEN 1 END) as en_camino
        FROM ordenes
    ");
    $stmtMetricas->execute([$fecha_hoy]);
    $metricas = $stmtMetricas->fetch();

    // Traer el listado general de órdenes uniendo la tabla de clientes con consultas preparadas
    $stmtLista = $pdo->query("
        SELECT o.id_orden, o.direccion_entrega, o.estatus, o.fecha_programada, o.subtotal_productos, o.costo_servicio_final, c.nombre as cliente
        FROM ordenes o
        JOIN clientes c ON o.id_cliente = c.id
        WHERE o.estatus IN ('Pendiente', 'Cotizado', 'En Camino')
        ORDER BY o.fecha_creacion DESC
    ");
    $ordenes_control = $stmtLista->fetchAll();

} catch (Exception $e) {
    // Registramos la falla real en las entrañas del servidor, nunca en la pantalla pública
    error_log($e->getMessage()); 
    exit('Fallo técnico crítico en los servicios de la consola central.');
}

include_once '../admin_dashboard.html.php';
?>