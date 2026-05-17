<?php
// index.php

// 1. CONFIGURACIÓN DE MÁXIMA SEGURIDAD PARA SESIONES
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

// Conexión centralizada a la base de datos
require_once 'db.php';

$mensaje_reseña = "";

// 2. GENERAR EL TOKEN CSRF SI NO EXISTE EN LA SESIÓN
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 3. PROCESAR EL FORMULARIO DE RESEÑAS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_reseña'])) {
    
    // Validar el token estrictamente para frenar bots y ataques externos
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('¡Epa, plebe! Petición no autorizada por el sistema de seguridad.');
    }

    $nombre = trim($_POST['nombre_cliente']);
    $comentario = trim($_POST['comentario']);
    $estrellas = intval($_POST['estrellas']);

    // Validación de campos obligatorios en el servidor
    if (!empty($nombre) && !empty($comentario) && $estrellas >= 1 && $estrellas <= 5) {
        try {
            // Guardamos la reseña oculta (aprobada = 0) hasta que el admin la valide en su panel
            $stmt = $pdo->prepare("INSERT INTO reseñas (nombre_cliente, comentario, estrellas, aprobada) VALUES (?, ?, ?, 0)");
            $stmt->execute([$nombre, $comentario, $estrellas]);
            $mensaje_reseña = "success";
            
            // Rotamos el token para que no se pueda reutilizar en otro envío inmediato
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            error_log($e->getMessage());
            $mensaje_reseña = "error";
        }
    } else {
        $mensaje_reseña = "incompleto";
    }
}

// 4. CONSULTAR ÚNICAMENTE LAS RESEÑAS APROBADAS POR EL ADMINISTRADOR
try {
    $stmt = $pdo->query("SELECT nombre_cliente, comentario, estrellas FROM reseñas WHERE aprobada = 1 ORDER BY id DESC LIMIT 6");
    $reseñas_aprobadas = $stmt->fetchAll();
} catch (Exception $e) {
    error_log($e->getMessage());
    $reseñas_aprobadas = [];
}

// En su index.php, antes del include:

// Si no hay mensaje, lo dejamos vacío o en false
$mensaje_reseña = isset($mensaje_reseña) ? $mensaje_reseña : ""; 

// Si no vienen reseñas de la base de datos, inicializamos un arreglo vacío
$reseñas_aprobadas = isset($reseñas_aprobadas) ? $reseñas_aprobadas : []; 

// Ya que están declaradas, ahora sí jalamos la vista
include '/Vistas/inicio.html.php';
?>
