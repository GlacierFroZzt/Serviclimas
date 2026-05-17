<!-- cotizador.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizador Express | Climas Sinaloa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Hojas de estilo de Leaflet para los mapas -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased">

    <!-- NAV BAR -->
    <nav class="bg-[#16161a] border-b border-[#27272a] sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="perfil.php" class="text-xs font-bold text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-chevron-left mr-1"></i> Volver a mi Perfil
            </a>
            <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest hidden sm:inline">Paso 2: Datos de Entorno</span>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-xl font-extrabold text-white uppercase tracking-tight">Cotizador de Instalación</h1>
            <p class="text-xs text-gray-500">Configure los detalles técnicos de su domicilio para calcular costos adicionales.</p>
        </div>

        <?php if ($mensaje_cotizacion === "error"): ?>
            <div class="bg-rose-950 border border-rose-500 text-rose-200 px-4 py-3 rounded-lg text-xs font-medium mb-6">
                Hubo un detalle técnico interno en el sistema. Revise los datos e intente de nuevo, viejo.
            </div>
        <?php elseif ($mensaje_cotizacion === "incompleto"): ?>
            <div class="bg-amber-950 border border-amber-500 text-amber-200 px-4 py-3 rounded-lg text-xs font-medium mb-6">
                Falta ingresar la dirección manual de entrega para poder procesar la orden.
            </div>
        <?php endif; ?>

        <!-- FORMULARIO GRID COMPACTO -->
        <form action="cotizador.php" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- CANDADO DE SEGURIDAD INTEGRADO -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['cotizador_csrf_token']; ?>">

            <!-- COLUMNA IZQUIERDA: MENÚS DE OPCIONES (5 Columnas) -->
            <div class="lg:col-span-5 bg-[#16161a] border border-[#27272a] rounded-xl p-6 space-y-5 h-fit">
                
                <div>
                    <label class="label-formulario">¿En qué piso se instalará el equipo?</label>
                    <select name="piso_instalacion" class="input-oscuro w-full text-sm mt-1">
                        <option value="Planta Baja">🏢 Planta Baja</option>
                        <option value="Segundo Piso">🏢 Segundo Piso (+ Costo de maniobra base)</option>
                        <option value="Tercer Piso / Azotea">🏢 Tercer Piso / Azotea (Ocupa andamio)</option>
                    </select>
                </div>

                <div>
                    <label class="label-formulario">¿Cómo está su preparación eléctrica?</label>
                    <select name="preparacion_electrica" class="input-oscuro w-full text-sm mt-1">
                        <option value="Ya cuenta con 220v">⚡ Ya cuenta con pastilla y base a 220v</option>
                        <option value="Requiere cableado desde centro de carga">⚡ Solo tengo 220v en medidor (Ocupa cablear)</option>
                        <option value="Solo cuenta con 110v">⚡ Mi casa es de 110v (Requiere bajada completa)</option>
                    </select>
                </div>

                <div>
                    <label class="label-formulario">¿Dónde irá montado el motor exterior?</label>
                    <select name="ubicacion_motor" class="input-oscuro w-full text-sm mt-1">
                        <option value="Piso / Patio">⚙️ En el piso de patio / Pasillo libre</option>
                        <option value="Colgado en pared exterior">⚙️ Colgado con juego de ménsulas metálicas</option>
                        <option value="Techo / Azotea">⚙️ Arriba en la azotea del domicilio</option>
                    </select>
                </div>

                <div>
                    <label class="label-formulario">Dirección del Domicilio (Escrita de corrido)</label>
                    <input type="text" name="direccion_manual" required placeholder="Ej. Av. Sinaloa #124, Col. Centro" class="input-oscuro w-full text-sm mt-1">
                </div>

                <div>
                    <label class="label-formulario">Notas adicionales para la cuadrilla</label>
                    <textarea name="detalles_adicionales" rows="3" maxlength="250" placeholder="Ej. Hay reja alta, llevar escalera de extensión..." class="input-oscuro w-full text-sm mt-1 placeholder-zinc-700"></textarea>
                </div>

                <!-- Input oculto seguro donde el script de JS va a estampar las coordenadas GPS reales -->
                <input type="hidden" name="coordenadas_gps" id="coordenadas_gps" value="24.8053,-107.3944">

                <button type="submit" name="procesar_cotizacion" class="btn-rojo-premium w-full font-bold uppercase tracking-wider text-xs py-4 rounded-lg shadow-lg shadow-red-600/10 transition pt-3">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Confirmar y Calcular Costo
                </button>
            </div>

            <!-- COLUMNA DERECHA: SELECCIÓN VISUAL DEL MAPA (7 Columnas) -->
            <div class="lg:col-span-7 space-y-3">
                <span class="text-[11px] font-bold text-gray-500 uppercase block">
                    <i class="fa-solid fa-location-crosshairs text-red-500 mr-1"></i> Arrastre el marcador rojo sobre su casa
                </span>
                
                <!-- Caja de renderizado del mapa Leaflet -->
                <div id="contenedorMapaCotizador" class="w-full h-[350px] lg:h-[520px] rounded-xl border border-[#27272a] bg-[#16161a] overflow-hidden z-10"></div>
            </div>

        </form>
    </main>

    <!-- JS SEGURO E INTERACTIVO DEL MAPA DE OPENSTREETMAP -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputGPS = document.getElementById('coordenadas_gps');
            
            // Coordenadas iniciales por defecto centrado (Culiacán, Sinaloa de referencia)
            let latInicial = 24.8053;
            let lngInicial = -107.3944;

            // 1. Inicializar el objeto mapa apuntando al div
            const mapa = L.map('contenedorMapaCotizador', {
                zoomControl: true
            }).setView([latInicial, lngInicial], 13);

            // 2. Cargar la capa de texturas gratuitas de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapa);

            // 3. Crear el marcador movible de color rojo
            const marcador = L.marker([latInicial, lngInicial], {
                draggable: true
            }).addTo(mapa);

            // Función interna para actualizar el input oculto filtrando los strings
            const actualizarInputCoordenadas = (lat, lng) => {
                if (inputGPS) {
                    // Fijamos a 6 decimales que da una precisión al puro tiro de centímetros
                    inputGPS.value = lat.toFixed(6) + ',' + lng.toFixed(6);
                }
            };

            // Estampamos las coordenadas base al arrancar
            actualizarInputCoordenadas(latInicial, lngInicial);

            // 4. EVENTO A: Cuando el cliente suelta el pin después de arrastrarlo
            marcador.on('dragend', function(e) {
                const posicion = marcador.getLatLng();
                actualizarInputCoordenadas(posicion.lat, posicion.lng);
            });

            // 5. EVENTO B: Si el cliente le pica a cualquier parte del mapa, brincamos el pin para allá
            mapa.on('click', function(e) {
                marcador.setLatLng(e.latlng);
                actualizarInputCoordenadas(e.latlng.lat, e.latlng.lng);
            });

            // 6. GEOLOCALIZACIÓN MÓVIL AUTOMÁTICA (Si el cliente autoriza el GPS del celular)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    let miLat = position.coords.latitude;
                    let miLng = position.coords.longitude;
                    
                    mapa.setView([miLat, miLng], 16);
                    marcador.setLatLng([miLat, miLng]);
                    actualizarInputCoordenadas(miLat, miLng);
                }, () => {
                    // Si deniega el permiso, se queda el mapa en el centro por defecto sin colapsar el script
                    console.log('Permiso de GPS denegado por el usuario.');
                });
            }
        });
    </script>
</body>
</html>