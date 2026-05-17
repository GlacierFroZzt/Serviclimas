<?php
// cotizador.php

// 1. CONTROL DE SEGURIDAD EN COOKIES DE SESIÓN
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

require_once 'db.php';

// Si no trae sesión iniciada, va para atrás directito al login
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

// 2. GENERAR EL TOKEN CSRF EXCLUSIVO PARA EL COTIZADOR
if (empty($_SESSION['cotizador_csrf_token'])) {
    $_SESSION['cotizador_csrf_token'] = bin2hex(random_bytes(32));
}

$mensaje_cotizacion = "";

// 3. PROCESAR ENVÍO DEL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_cotizacion'])) {
    
    // Validar el token CSRF para evitar peticiones fantasmas de otros sitios
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['cotizador_csrf_token']) {
        exit('Petición denegada por seguridad del servidor, viejo.');
    }

    // 4. VALIDACIÓN POR LISTA BLANCA (Mata cualquier truco de manipulación del HTML)
    $pisos_validos = ['Planta Baja', 'Segundo Piso', 'Tercer Piso / Azotea'];
    $piso = in_array($_POST['piso_instalacion'], $pisos_validos) ? $_POST['piso_instalacion'] : 'Planta Baja';

    $luz_valida = ['Ya cuenta con 220v', 'Requiere cableado desde centro de carga', 'Solo cuenta con 110v'];
    $luz = in_array($_POST['preparacion_electrica'], $luz_valida) ? $_POST['preparacion_electrica'] : 'Ya cuenta con 220v';

    $motor_valido = ['Piso / Patio', 'Colgado en pared exterior', 'Techo / Azotea'];
    $motor = in_array($_POST['ubicacion_motor'], $motor_valido) ? $_POST['ubicacion_motor'] : 'Piso / Patio';

    // Filtramos y limpiamos el texto libre de las notas y dirección manual
    $detalles_adicionales = trim(filter_input(INPUT_POST, 'detalles_adicionales', FILTER_SANITIZE_SPECIAL_CHARS));
    $direccion_entrega    = trim(filter_input(INPUT_POST, 'direccion_manual', FILTER_SANITIZE_SPECIAL_CHARS));
    
    // 5. VALIDACIÓN CON EXPRESIÓN REGULAR PARA COORDENADAS GPS (Formato estricto Lat,Lng decimal)
    $coordenadas_gps = trim($_POST['coordenadas_gps']);
    if (!preg_match('/^-?[0-9]{1,2}\.[0-9]{4,7},-?[0-9]{1,3}\.[0-9]{4,7}$/', $coordenadas_gps)) {
        $coordenadas_gps = ""; // Si trae mañas o caracteres raros, se limpia por completo
    }

    if (!empty($direccion_entrega)) {
        try {
            // Arrancamos transacción atómica para asegurar ambas inserciones
            $pdo->beginTransaction();

            // Empaquetamos el entorno técnico en formato JSON estructurado
            $entorno = [
                'piso'                  => $piso,
                'preparacion_220v'      => $luz,
                'ubicacion_condensador' => $motor,
                'detalles_adicionales'  => $detalles_adicionales
            ];
            $especificaciones_json = json_encode($entorno, JSON_UNESCAPED_UNICODE);

            // Valores de arranque del negocio
            $subtotal_productos  = 0.00; 
            $costo_servicio_base = 1200.00; // Costo base estándar por instalación de clima

            // Inserción 1: Encabezado de la Orden
            $stmtOrden = $pdo->prepare("
                INSERT INTO ordenes (id_cliente, direccion_entrega, coordenadas_gps, estatus, subtotal_productos, costo_servicio_final) 
                VALUES (?, ?, ?, 'Pendiente', ?, ?)
            ");
            $stmtOrden->execute([intval($_SESSION['cliente_id']), $direccion_entrega, $coordenadas_gps, $subtotal_productos, $costo_servicio_base]);
            $id_nueva_orden = $pdo->lastInsertId();

            // Inserción 2: Detalle de Especificación del Servicio
            $stmtDetalle = $pdo->prepare("
                INSERT INTO orden_detalles (id_orden, tipo_item, item_nombre, precio_unitario, cantidad, especificaciones_servicio) 
                VALUES (?, 'servicio', 'Instalación y Diagnóstico Base', ?, 1, ?)
            ");
            $stmtDetalle->execute([$id_nueva_orden, $costo_servicio_base, $especificaciones_json]);

            $pdo->commit();
            
            // Destruimos el token usado para que no se dupliquen envíos si recarga la página
            unset($_SESSION['cotizador_csrf_token']);
            
            // Brincamos en caliente a la Pantalla 4 de confirmación
            header("Location: detalle_orden.php?id=" . $id_nueva_orden);
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
            $mensaje_cotizacion = "error";
        }
    } else {
        $mensaje_cotizacion = "incompleto";
    }
}

include_once '../cotizador.html.php';
?>