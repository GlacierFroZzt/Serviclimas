<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Orden #<?= intval($orden['id_orden']); ?> | Consola Administrativa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#09090b] text-[#f4f4f5] antialiased">

    <nav class="bg-[#121215] border-b border-[#27272a] sticky top-0 z-50">
        <div class="max-w-2xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="admin_dashboard.php" class="text-xs font-bold text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-chevron-left mr-1"></i> Regresar a la Consola
            </a>
            <span class="text-xs font-mono font-bold text-zinc-500 uppercase tracking-widest">Modulación de Folio</span>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-8 space-y-6">

        <?php if ($mensaje_cambio === "success"): ?>
            <div class="bg-emerald-950 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg text-xs font-medium">
                ¡Al puro centavo, viejo! La orden fue actualizada en el servidor de forma segura.
            </div>
        <?php elseif ($mensaje_cambio === "error"): ?>
            <div class="bg-rose-950 border border-rose-500 text-rose-200 px-4 py-3 rounded-lg text-xs font-medium">
                Hubo un conflicto interno en la consulta SQL. No se guardaron las modificaciones.
            </div>
        <?php endif; ?>

        <div class="bg-[#121215] border border-[#27272a] rounded-xl p-5 space-y-4">
            <div class="border-b border-[#27272a] pb-3">
                <span class="text-[9px] font-black uppercase text-red-500 tracking-wider block">Ficha del Propietario</span>
                <h2 class="text-base font-bold text-white mt-0.5"><?= htmlspecialchars($orden['cliente'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                    <i class="fa-solid fa-location-dot text-zinc-600 mr-1"></i><?= htmlspecialchars($orden['direccion_entrega'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>

            <div class="flex flex-wrap gap-3 pt-1">
                <a href="tel:<?= filter_var($orden['telefono'], FILTER_SANITIZE_NUMBER_INT); ?>" class="bg-zinc-800 hover:bg-zinc-700 text-white text-[11px] font-bold px-3 py-2 rounded-lg flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-phone text-[10px]"></i> Llamar al Cliente
                </a>
                
                <?php if (!empty($orden['coordenadas_gps'])): ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($orden['coordenadas_gps']); ?>" target="_blank" class="bg-blue-950/40 border border-blue-900 text-blue-400 text-[11px] font-bold px-3 py-2 rounded-lg flex items-center gap-1.5 transition hover:bg-blue-900/50">
                        <i class="fa-solid fa-diamond-turn-right text-[10px]"></i> Desplegar GPS en Ruta
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <form action="admin_editar.php?id=<?= intval($orden['id_orden']); ?>" method="POST" class="bg-[#121215] border border-[#27272a] rounded-xl p-6 space-y-5">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['admin_csrf_token']; ?>">

            <div>
                <label class="label-formulario">Control de Estatus</label>
                <select name="estatus" class="input-oscuro w-full text-sm mt-1 bg-[#09090b]">
                    <option value="Pendiente" <?= $orden['estatus'] === 'Pendiente' ? 'selected' : ''; ?>>⏳ Pendiente de revisión</option>
                    <option value="Cotizado" <?= $orden['estatus'] === 'Cotizado' ? 'selected' : ''; ?>>💵 Costo Cotizado / Autorizado</option>
                    <option value="En Camino" <?= $orden['estatus'] === 'En Camino' ? 'selected' : ''; ?>>🚚 Cuadrilla en Camino</option>
                    <option value="Completado" <?= $orden['estatus'] === 'Completado' ? 'selected' : ''; ?>>✅ Trabajo Finalizado / Cobrado</option>
                </select>
            </div>

            <div>
                <label class="label-formulario">Programar Fecha de Instalación (Obra)</label>
                <input type="date" name="fecha_programada" value="<?= !empty($orden['fecha_programada']) ? htmlspecialchars($orden['fecha_programada'], ENT_QUOTES, 'UTF-8') : ''; ?>" class="input-oscuro w-full text-sm mt-1 bg-[#09090b]">
            </div>

            <div>
                <label class="label-formulario">Costo Final del Servicio Técnico ($ MXN)</label>
                <input type="number" step="0.01" min="0" name="costo_servicio_final" value="<?= floatval($orden['costo_servicio_final']); ?>" required class="input-oscuro w-full text-sm mt-1 bg-[#09090b] font-mono font-bold text-red-500">
                <span class="text-[10px] text-zinc-500 block mt-1">*Este monto se suma en automático al subtotal de refacciones e insumos.</span>
            </div>

            <div class="pt-2 border-t border-[#27272a]">
                <button type="submit" name="actualizar_orden" class="btn-rojo-premium w-full font-bold uppercase tracking-wider text-xs py-4 rounded-lg shadow-md shadow-red-900/20 transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Confirmar y Aplicar Cambios
                </button>
            </div>

        </form>
    </main>

</body>
</html>