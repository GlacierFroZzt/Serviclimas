<?php
// ==========================================================================
// ARCHIVO: todas_las_reseñas.php (Pura lógica de programación)
// ==========================================================================
session_start();

// Aquí va a requerir su conexión real a la base de datos más adelante
/*
require_once 'conexion.php'; 
*/

// --- SIMULACIÓN DE DATOS DE LA BASE DE DATOS ---
$reseñas_todas = [
    ['nombre' => 'Juan Carlos M.', 'iniciales' => 'JC', 'fecha' => 'Hace 2 días', 'estrellas' => 5, 'comentario' => 'Tenia un calorón feo en la sala y los plebes vinieron en caliente. Instalaron el minisplit de 1.5 toneladas bien rápido y quedó jalando al puro tiro.'],
    ['nombre' => 'Sergio R.', 'iniciales' => 'SR', 'fecha' => 'Hace 1 semana', 'estrellas' => 5, 'comentario' => 'Muy profesionales los compas de los climas. El aparato no hacia ruido y la instalación limpia, sin dejar desmadre de polvo. El precio está excelente.'],
    ['nombre' => 'Luis Alberto G.', 'iniciales' => 'LA', 'fecha' => 'Hace 3 semanas', 'estrellas' => 5, 'comentario' => 'El sistema de cotización express está bien cura, te calcula los metros al instante. Vinieron al siguiente día a instalar el Mirage. Ya duermo agusto.'],
    ['nombre' => 'Ramón A.', 'iniciales' => 'RA', 'fecha' => 'Hace 1 mes', 'estrellas' => 4, 'comentario' => 'Buen jale, llegaron a la hora que dijeron y traían buena herramienta. El aire enfría machine.'],
    ['nombre' => 'Checo Verdugo', 'iniciales' => 'CV', 'fecha' => 'Hace 1 mes', 'estrellas' => 5, 'comentario' => 'Excelente servicio, me limpiaron tres minisplits que ya tiraban agua y quedaron como nuevos.'],
    ['nombre' => 'María José L.', 'iniciales' => 'MJ', 'fecha' => 'Hace 2 meses', 'estrellas' => 5, 'comentario' => 'Me daba miedo que me cobraran un dineral, pero la cotización fue justa y rápida. Super recomendados.'],
];

// --- SISTEMA DE PAGINACIÓN INTELIGENTE ---
$reseñas_por_pagina = 9;
$total_reseñas = count($reseñas_todas); 
$total_paginas = ceil($total_reseñas / $reseñas_por_pagina);

// Conseguir la página actual por la URL (ej. todas_las_reseñas.php?p=2)
$pagina_actual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
if ($pagina_actual > $total_paginas && $total_paginas > 0) $pagina_actual = $total_paginas;

// Cortar el pastel de los datos para la página correspondiente
$offset = ($pagina_actual - 1) * $reseñas_por_pagina;
$reseñas_pantalla = array_slice($reseñas_todas, $offset, $reseñas_por_pagina);

/* // --- REEMPLAZO CUANDO CONECTE SU BD REAL (PDO) ---
$offset = ($pagina_actual - 1) * $reseñas_por_pagina;
$stmt = $pdo->prepare("SELECT nombre_cliente, estrellas, comentario, fecha FROM reseñas ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $reseñas_por_pagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reseñas_pantalla = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/

include '../Vistas/todas_las_reseñas.html.php';