<!-- detalle_orden.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Orden #<?= intval($orden['id_orden']); ?> | Climas Sinaloa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Librería de Mapas Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased">

    <!-- NAV BAR -->
    <nav class="bg-[#16161a] border-b border-[#27272a] sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="perfil.php" class="text-xs font-bold text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver a solicitudes
            </a>
            <span class="text-xs font-mono font-bold text-[#dc2626] uppercase bg-red-950/40 border border-red-900 px-2.5 py-0.5 rounded">Ficha Oficial</span>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-8 space-y-6">
        
        <!-- HEADER DE LA SOLICITUD -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#27272a] pb-4">
            <div>
                <h1 class="text-xl font-black text-white uppercase tracking-tight">Resumen de Orden #<?= intval($orden['id_orden']); ?></h1>
                <p class="text-[11px] text-gray-500 mt-0.5">Generado el: <?= date('d/m/Y H:i', strtotime($orden['fecha_creacion'])); ?></p>
            </div>
            
            <!-- Badge dinámico de estatus sanitizado -->
            <div>
                <?php if ($orden['estatus'] === 'Pendiente'): ?>
                    <span class="text-[10px] font-extrabold px-3 py-1 bg-zinc-900 text-zinc-400 border border-zinc-700 rounded-full uppercase">⏳ Pendiente de revisión</span>
                <?php elseif ($orden['estatus'] === 'Cotizado'): ?>
                    <span class="text-[10px] font-extrabold px-3 py-1 bg-blue-950 text-blue-400 border border-blue-900 rounded-full uppercase">💵 Costo autorizado</span>
                <?php elseif ($orden['estatus'] === 'En Camino'): ?>
                    <span class="text-[10px] font-extrabold px-3 py-1 bg-amber-950 text-amber-400 border border-amber-900 rounded-full uppercase animate-pulse">🚚 Cuadrilla en camino</span>
                <?php else: ?>
                    <span class="text-[10px] font-extrabold px-3 py-1 bg-emerald-950 text-emerald-400 border border-emerald-900 rounded-full uppercase">✅ Servicio Completado</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- DETALLES TÉCNICOS ESPECÍFICOS DEL ENTORNO -->
        <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-6 space-y-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-[#27272a] pb-2">Especificaciones de Instalación</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-[#0c0c0e] border border-[#27272a] p-3 rounded-lg">
                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Piso Inmueble</span>
                    <span class="text-white font-semibold text-xs mt-1 block"><?= htmlspecialchars($entorno['piso'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div class="bg-[#0c0c0e] border border-[#27272a] p-3 rounded-lg">
                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Preparación Eléctrica</span>
                    <span class="text-white font-semibold text-xs mt-1 block"><?= htmlspecialchars($entorno['preparacion_220v'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div class="bg-[#0c0c0e] border border-[#27272a] p-3 rounded-lg">
                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Montaje de Motor</span>
                    <span class="text-white font-semibold text-xs mt-1 block"><?= htmlspecialchars($entorno['ubicacion_condensador'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>

            <div class="bg-[#0c0c0e] border border-[#27272a] p-4 rounded-lg">
                <span class="text-[10px] font-bold text-gray-500 uppercase block">Notas especiales añadidas</span>
                <p class="text-xs text-gray-300 italic mt-1 leading-relaxed">"<?= htmlspecialchars($entorno['detalles_adicionales'], ENT_QUOTES, 'UTF-8'); ?>"</p>
            </div>
        </div>

        <!-- DIRECCIÓN Y MINI MAPA LOGÍSTICO -->
        <div class="grid grid-cols-1 md:grid-cols-12 bg-[#16161a] border border-[#27272a] rounded-xl overflow-hidden">
            <div class="p-6 md:col-span-5 flex flex-col justify-between space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Dirección de Entrega</h3>
                    <p class="text-xs sm:text-sm text-white font-medium leading-relaxed">
                        <?= htmlspecialchars($orden['direccion_entrega'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                <div>
                    <span class="text-[10px] text-zinc-500 block">Coordenadas del Pin:</span>
                    <span class="text-xs font-mono text-zinc-400 block mt-0.5"><?= filter_var($orden['coordenadas_gps'], FILTER_SANITIZE_SPECIAL_CHARS); ?></span>
                </div>
            </div>
            
            <!-- Div para renderizar el Mapa Estático -->
            <div class="md:col-span-7 h-[220px] md:h-auto border-t md:border-t-0 md:border-l border-[#27272a] relative z-10 bg-[#0c0c0e]">
                <div id="mapaDetalleEstatico" class="w-full h-full"></div>
            </div>
        </div>

        <!-- COMPONENTES FINANCIEROS Y ENLACE DE ACCIÓN DE WHATSAPP -->
        <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="text-center sm:text-left">
                <span class="text-[11px] font-bold text-gray-500 uppercase block tracking-wider">Costo Estimado de Servicio Base</span>
                <div class="flex items-baseline justify-center sm:justify-start gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-white">$<?= number_format(floatval($orden['subtotal_productos'] + $orden['costo_servicio_final']), 2); ?></span>
                    <span class="text-[10px] text-zinc-500 font-bold font-mono">MXN</span>
                </div>
                <span class="text-[9px] text-zinc-500 block mt-0.5">*Sujeto a cambios menores según materiales extras usados en obra.</span>
            </div>

            <!-- BOTÓN DE ENLACE DIRECTO A WHATSAPP -->
            <a href="<?= $url_whatsapp; ?>" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center bg-[#25d366] hover:bg-[#20ba56] text-zinc-950 text-xs font-black uppercase tracking-wider px-6 py-4 rounded-lg transition shadow-lg shadow-[#25d366]/10 text-center">
                <i class="fa-brands fa-whatsapp text-lg mr-2"></i> Contactar por WhatsApp
            </a>
        </div>

        <!-- Input oculto e inofensivo para inyectar el valor GPS al JS -->
        <input type="hidden" id="coordenadas_registro" value="<?= filter_var($orden['coordenadas_gps'], FILTER_SANITIZE_SPECIAL_CHARS); ?>">
    </main>

    <!-- JS SEGURO QUE VALIDA EL FORMATO ANTES DE CARGAR LEAFLET -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputCoords = document.getElementById('coordenadas_registro');
            
            if (inputCoords && inputCoords.value) {
                // Expresión regular para verificar que el string contenga únicamente lat,lng decimal real
                const regexGPS = /^-?[0-9]{1,2}\.[0-9]+,-?[0-9]{1,3}\.[0-9]+$/;
                
                if (regexGPS.test(inputCoords.value.trim())) {
                    const coords = inputCoords.value.split(',');
                    const lat = parseFloat(coords[0]);
                    const lng = parseFloat(coords[1]);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        // Desactivamos arrastres y zooms para simular un mapa estático corporativo
                        const mapaDetalle = L.map('mapaDetalleEstatico', {
                            zoomControl: false,
                            boxZoom: false,
                            doubleClickZoom: false,
                            dragging: false,
                            scrollWheelZoom: false
                        }).setView([lat, lng], 15);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapaDetalle);
                        L.marker([lat, lng]).addTo(mapaDetalle);
                    }
                } else {
                    // Si el string trae mañas o viene vacío, pintamos un contenedor de aviso elegante en lugar de romper el script
                    document.getElementById('mapaDetalleEstatico').innerHTML = 
                        `<div class="h-full flex flex-col items-center justify-center text-zinc-600 text-[11px] p-4 text-center bg-[#111114]">
                            <i class="fa-solid fa-map-marked-alt text-lg mb-1 block text-zinc-700"></i> Ubicación resguardada por privacidad
                         </div>`;
                }
            }
        });
    </script>
</body>
</html>