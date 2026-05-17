<!-- admin_dashboard.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consola de Control | Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#09090b] text-[#f4f4f5] antialiased">

    <!-- CABECERA DEL DASHBOARD CORPORATIVO -->
    <header class="bg-[#121215] border-b border-[#27272a] sticky top-0 z-40 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-2.5 bg-red-600 rounded-full animate-ping"></div>
                <h1 class="text-xs font-black uppercase tracking-widest text-white">CONSOLA CENTRAL DE CLIMAS</h1>
            </div>
            <!-- Enlace directo al logout seguro de administración -->
            <a href="admin_logout.php" class="bg-red-950/40 border border-red-900 text-red-400 text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-lg hover:bg-red-900 hover:text-white transition">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Cerrar Consola
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 space-y-8">
        
        <!-- SECCIÓN DE MÉTRICAS RÁPIDAS (Calculadas dinámicamente) -->
        <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <div class="bg-[#121215] border border-[#27272a] p-5 rounded-xl">
                <span class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider">Servicios Solicitados Hoy</span>
                <span class="text-2xl font-black text-white block mt-1"><?= intval($metricas['totales_hoy']); ?></span>
            </div>

            <div class="bg-[#121215] border border-[#27272a] p-5 rounded-xl border-l-amber-600">
                <span class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider">Órdenes en Estatus Pendiente</span>
                <span class="text-2xl font-black text-amber-500 block mt-1"><?= intval($metricas['pendientes']); ?></span>
            </div>

            <div class="bg-[#121215] border border-[#27272a] p-5 rounded-xl border-l-blue-600">
                <span class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider">Cuadrillas Activas en Calle</span>
                <span class="text-2xl font-black text-blue-500 block mt-1"><?= intval($metricas['en_camino']); ?></span>
            </div>

        </section>

        <!-- LISTADO MAESTRO DE OPERACIONES -->
        <section class="space-y-4">
            <div class="border-b border-[#27272a] pb-2">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Cola de Atención Inmediata</h2>
            </div>

            <?php if (count($ordenes_control) > 0): ?>
                <div class="grid grid-cols-1 gap-3">
                    <?php foreach ($ordenes_control as $o): ?>
                        <div class="bg-[#121215] border border-[#27272a] rounded-xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-zinc-700 transition">
                            
                            <!-- Información de la solicitud -->
                            <div class="space-y-2 flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- ID e Identidad Sanitizados exhaustivamente contra ataques XSS -->
                                    <span class="text-xs font-bold text-white font-mono bg-zinc-800 px-2 py-0.5 rounded">
                                        #<?= intval($o['id_orden']); ?> — <?= htmlspecialchars($o['cliente'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>

                                    <!-- Marcadores de estatus dinámicos -->
                                    <?php if ($o['estatus'] === 'Pendiente'): ?>
                                        <span class="text-[9px] font-black px-2 py-0.5 bg-zinc-900 text-zinc-400 border border-zinc-700 rounded uppercase">Pendiente</span>
                                    <?php elseif ($o['estatus'] === 'Cotizado'): ?>
                                        <span class="text-[9px] font-black px-2 py-0.5 bg-blue-950 text-blue-400 border border-blue-900 rounded uppercase">Cotizado</span>
                                    <?php else: ?>
                                        <span class="text-[9px] font-black px-2 py-0.5 bg-amber-950 text-amber-400 border border-amber-900 rounded uppercase">🚚 En Camino</span>
                                    <?php endif; ?>
                                </div>

                                <p class="text-xs text-gray-400 truncate max-w-2xl">
                                    <i class="fa-solid fa-location-dot text-zinc-600 mr-1.5"></i><?= htmlspecialchars($o['direccion_entrega'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>

                            <!-- Valores Monetarios y Botón de redirección -->
                            <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 border-[#27272a] pt-3 md:pt-0">
                                <div class="text-left md:text-right">
                                    <span class="text-[9px] text-gray-500 uppercase block">Costo Caja</span>
                                    <span class="text-xs font-extrabold text-white">
                                        $<?= number_format(floatval($o['subtotal_productos'] + $o['costo_servicio_final']), 2); ?>
                                    </span>
                                </div>

                                <!-- Enlace de gestión directa mandando el ID estrictamente casteado a entero -->
                                <a href="admin_editar.php?id=<?= intval($o['id_orden']); ?>" class="bg-zinc-800 hover:bg-red-600 text-white text-[11px] font-bold px-3.5 py-2 rounded-lg transition flex items-center gap-1">
                                    <i class="fa-solid fa-sliders-h text-[10px]"></i> Gestionar
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 border border-dashed border-[#27272a] rounded-xl text-xs text-gray-500 bg-[#121215]">
                    <i class="fa-solid fa-square-check text-xl text-zinc-700 mb-2 block"></i>
                    No hay solicitudes pendientes en este momento, viejo. Todo el trabajo está al corriente.
                </div>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>