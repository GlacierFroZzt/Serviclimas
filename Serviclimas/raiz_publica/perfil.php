<?php
// perfil.php

// 1. REFORZAR COOKIES DE SESIÓN ANTES DE ARRANCAR
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

require_once 'db.php';

// Si el plebe no se ha logueado, va para atrás de una
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

// 2. CANDADO ANTI-CACHÉ: Obliga al celular a borrar la pantalla de la memoria al salir
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$id_cliente = intval($_SESSION['cliente_id']);

try {
    // Consultar datos personales del perfil
    $stmtPerfil = $pdo->prepare("SELECT nombre, correo, telefono FROM clientes WHERE id = ? LIMIT 1");
    $stmtPerfil->execute([$id_cliente]);
    $perfil = $stmtPerfil->fetch();

    if (!$perfil) {
        // Si por algo se borró el cliente, matamos sesión
        header('Location: logout.php');
        exit;
    }

    // Consultar historial de órdenes (Sólo las de este cliente)
    $stmtOrdenes = $pdo->prepare("
        SELECT id_orden, direccion_entrega, estatus, fecha_creacion, costo_servicio_final, subtotal_productos 
        FROM ordenes 
        WHERE id_cliente = ? 
        ORDER BY fecha_creacion DESC
    ");
    $stmtOrdenes->execute([$id_cliente]);
    $historial_ordenes = $stmtOrdenes->fetchAll();

} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Error de comunicación con la base de datos, viejo.');
}

// Soltamos la vista responsiva
include_once '../perfil.html.php';
?>