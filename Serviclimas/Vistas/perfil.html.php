<!-- perfil.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Climas Sinaloa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased">

    <!-- NAV BAR -->
    <nav class="bg-[#16161a] border-b border-[#27272a] sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="index.php" class="text-xs font-bold text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-house mr-1"></i> Inicio
            </a>
            <a href="logout.php" class="text-xs font-bold text-red-500 hover:text-red-400 transition">
                <i class="fa-solid fa-power-off mr-1"></i> Salir
            </a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-8 space-y-8">
        
        <!-- SECCIÓN 1: DATOS PERSONALES DEL PERFIL (SANITIZADOS) -->
        <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-red-950/40 border border-red-800 flex items-center justify-center text-red-500">
                    <i class="fa-solid fa-user text-lg"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest block">Cliente Verificado</span>
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">
                        <?= htmlspecialchars($perfil['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-[#27272a] pt-4">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase">Correo Electrónico</span>
                    <p class="text-xs sm:text-sm text-gray-300 break-all mt-0.5"><?= htmlspecialchars($perfil['correo'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase">Teléfono de Contacto</span>
                    <p class="text-xs sm:text-sm text-gray-300 mt-0.5"><?= htmlspecialchars($perfil['telefono'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: HISTORIAL DE COTIZACIONES / SERVICIOS -->
        <div class="space-y-4">
            <div class="border-b border-[#27272a] pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Mis Solicitudes</h3>
                    <p class="text-[11px] text-gray-500">Historial completo de instalaciones y diagnósticos.</p>
                </div>
                <a href="cotizador.php" class="bg-[#dc2626] hover:bg-red-700 text-white text-[11px] font-bold px-3 py-2 rounded transition">
                    <i class="fa-solid fa-plus mr-1"></i> Nueva
                </a>
            </div>

            <?php if (count($historial_ordenes) > 0): ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($historial_ordenes as $o): ?>
                        <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-5 flex items-center justify-between hover:border-zinc-700 transition gap-4">
                            
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-white">#<?= intval($o['id_orden']); ?></span>
                                    
                                    <!-- Insignias de Estatus con Control Estético -->
                                    <?php if ($o['estatus'] === 'Pendiente'): ?>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 bg-zinc-900 text-zinc-400 border border-zinc-700 rounded uppercase">Pendiente</span>
                                    <?php elseif ($o['estatus'] === 'Cotizado'): ?>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 bg-blue-950 text-blue-400 border border-blue-900 rounded uppercase">Cotizado</span>
                                    <?php elseif ($o['estatus'] === 'En Camino'): ?>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 bg-amber-950 text-amber-400 border border-amber-900 rounded uppercase animate-pulse">🚚 En Camino</span>
                                    <?php else: ?>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 bg-emerald-950 text-emerald-400 border border-emerald-900 rounded uppercase">Completado</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Dirección Sanitizada -->
                                <p class="text-xs text-gray-400 truncate">
                                    <i class="fa-solid fa-location-dot mr-1 text-zinc-600"></i><?= htmlspecialchars($o['direccion_entrega'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                                
                                <span class="block text-[10px] text-gray-500">
                                    Registrado: <?= date('d/m/Y H:i', strtotime($o['fecha_creacion'])); ?>
                                </span>
                            </div>

                            <!-- Bloque de Costo y Acción -->
                            <div class="flex items-center space-x-4 shrink-0">
                                <div class="text-right">
                                    <span class="text-[9px] text-gray-500 block uppercase">Total</span>
                                    <span class="text-xs sm:text-sm font-black text-white">$<?= number_format(floatval($o['subtotal_productos'] + $o['costo_servicio_final']), 2); ?></span>
                                </div>
                                
                                <!-- Botón Responsivo con ID casteado a entero -->
                                <button data-id="<?= intval($o['id_orden']); ?>" class="btn-ver-detalle bg-zinc-800 hover:bg-zinc-700 text-white w-8 h-8 rounded-lg flex items-center justify-center transition">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 border border-dashed border-[#27272a] rounded-xl text-xs text-gray-500">
                    <i class="fa-solid fa-folder-open text-xl text-zinc-700 mb-2 block"></i>
                    Aún no ha realizado ninguna cotización de aire acondicionado, viejo.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- SCRIPT SEGURO PARA REDIRECCIÓN -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const botones = document.querySelectorAll('.btn-ver-detalle');
            botones.forEach(btn => {
                btn.addEventListener('click', function() {
                    const idOrden = this.getAttribute('data-id');
                    if (idOrden && !isNaN(idOrden)) {
                        // Viaja directo a la pantalla 4 de forma limpia
                        window.location.href = 'detalle_orden.php?id=' + idOrden;
                    }
                });
            });
        });
    </script>
</body>
</html>