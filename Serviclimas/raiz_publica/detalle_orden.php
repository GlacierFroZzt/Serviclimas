<?php
// detalle_orden.php

// 1. CONTROL EXTRACTO DE COOKIES DE SESIÓN
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

require_once 'db.php';

// Si no trae sesión iniciada, va para atrás de una al login
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

// Validar que venga el ID de la orden en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: perfil.php');
    exit;
}

$id_orden = intval($_GET['id']);
$id_cliente = intval($_SESSION['cliente_id']);

try {
    // 2. EL ESCUDO MAESTRO ANTI-IDOR: Validamos que la orden pertenezca estrictamente al cliente logueado
    $stmt = $pdo->prepare("
        SELECT o.*, c.nombre, c.telefono, c.tipo_usuario 
        FROM ordenes o
        JOIN clientes c ON o.id_cliente = c.id
        WHERE o.id_orden = ? AND o.id_cliente = ?
        LIMIT 1
    ");
    $stmt->execute([$id_orden, $id_cliente]);
    $orden = $stmt->fetch();

    // Si arroja falso, significa que la orden no existe O es de otro cliente. ¡Bloqueo fulminante!
    if (!$orden) {
        header('Location: perfil.php');
        exit;
    }

    // Consultar el detalle del servicio para extraer el JSON de especificaciones
    $stmtDetalles = $pdo->prepare("SELECT * FROM orden_detalles WHERE id_orden = ?");
    $stmtDetalles->execute([$id_orden]);
    $detalles = $stmtDetalles->fetchAll();

    // Array por defecto si el JSON viniera vacío o corrupto por algún motivo externo
    $entorno = [
        'piso'                  => 'No especificado',
        'preparacion_220v'      => 'No especificada',
        'ubicacion_condensador' => 'No especificada',
        'detalles_adicionales'  => 'Ninguno'
    ];

    foreach ($detalles as $d) {
        if (!empty($d['especificaciones_servicio'])) {
            $json_parsed = json_decode($d['especificaciones_servicio'], true);
            if (is_array($json_parsed)) {
                $entorno = array_merge($entorno, $json_parsed);
            }
        }
    }

    // 3. ARMADO DE LINK SEGURO Y PARAMETRIZADO PARA LA API DE WHATSAPP
    // Número del negocio con código de país (52 para México)
    $telefono_negocio = "526671234567"; 
    
    // Construimos la plantilla de texto limpio con negritas de WhatsApp
    $texto_ws = "¡Qué tal, viejo! Vengo de la página web. Ocupo el servicio para una cuenta Tipo: *" . strtoupper($orden['tipo_usuario']) . "*.\n\n";
    $texto_ws .= "*Orden:* # " . $orden['id_orden'] . "\n";
    $texto_ws .= "*Cliente:* " . $orden['nombre'] . "\n";
    $texto_ws .= "*Dirección:* " . $orden['direccion_entrega'] . "\n";
    
    if (!empty($orden['coordenadas_gps'])) {
        $texto_ws .= "*Mapa GPS:* https://www.google.com/maps/search/?api=1&query=" . $orden['coordenadas_gps'] . "\n";
    }
    
    $texto_ws .= "\n*── DETALLES DEL ENTORNO ──*\n";
    $texto_ws .= "• Piso: " . $entorno['piso'] . "\n";
    $texto_ws .= "• Luz 220v: " . $entorno['preparacion_220v'] . "\n";
    $texto_ws .= "• Motor en: " . $entorno['ubicacion_condensador'] . "\n";
    $texto_ws .= "• Notas: " . $entorno['detalles_adicionales'] . "\n\n";
    $texto_ws .= "Quedo al puro tiro para la confirmación de la fecha.";

    // Aplicamos urlencode completo para que los espacios y saltos viajen limpios sin romper la URL
    $url_whatsapp = "https://api.whatsapp.com/send?phone=" . $telefono_negocio . "&text=" . urlencode($texto_ws);

} catch (Exception $e) {
    // Registramos el error internamente en el log, jamás se lo mostramos en pantalla al usuario
    error_log($e->getMessage());
    exit('Fallo de comunicación con los módulos logísticos de la base de datos.');
}

include_once './/detalle_orden.html.php';
?>