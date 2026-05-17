<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviclimas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../raiz_publica/estilos_premium.css">

   <style>
    /* ==========================================================================
       CONSOLA DEL CARRUSEL 3D - CENTRADO ABSOLUTO Y RESPONSIVE
       ========================================================================== */

    /* Contenedor general del carrusel */
    .carousel-contenedor {
        position: relative !important;
        width: 100% !important;
        max-width: 1200px !important;
        height: 540px !important; /* Altura ideal fija para que no tape las reseñas abajo */
        margin: 40px auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: border-box !important;
    }

    /* Caja contenedora rígida de las tarjetas */
    .cards-wrapper {
        position: relative !important;
        width: 100% !important;
        max-width: 520px !important; 
        height: 440px !important;   
        margin: 0 auto !important;
        display: block !important;
    }

    /* Obligamos a todas las tarjetas a encimarse en un solo punto absoluto */
    .tarjeta-3d {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important; /* Centrado horizontal forzado */
        width: 100% !important;
        height: 100% !important;
        margin: 0 auto !important; /* El margen automático amarra el centro matemático */
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        
        /* Limpiamos cualquier margen vertical raro de Tailwind (space-y) */
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        
        /* Transición fina para el movimiento 3D */
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                    opacity 0.5s ease, 
                    z-index 0.5s ease,
                    box-shadow 0.3s ease !important;
    }

    /* ==========================================================================
       CONFIGURACIÓN INTERNA PARA QUE LAS FOTOS NO SE CORTEN EN CELULARES
       ========================================================================== */
    .contenedor-foto-clima {
        width: 100% !important;
        height: 160px !important;
        position: relative !important;
        overflow: hidden !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: #0c0c0e !important; /* Fondo oscuro de la app */
        border-bottom: 1px solid #27272a !important;
    }

    .foto-clima-3d {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important; /* En PC llena bien la tarjeta de forma premium */
        opacity: 0.8 !important;
        transition: opacity 0.3s ease !important;
    }

    /* ==========================================================================
       CONFIGURACIÓN PARA COMPUTADORAS (PANTALLAS GRANDES - EFECTO AMPLIO)
       ========================================================================== */
    @media (min-width: 768px) {
        .carousel-contenedor {
            overflow: visible !important; /* Dejamos que vuelen las laterales en PC */
        }
        /* Tarjeta Activa (Al centro y al frente) */
        .tarjeta-3d.activa {
            opacity: 1 !important;
            transform: translateX(0) scale(1) !important;
            z-index: 30 !important;
            pointer-events: auto !important;
            border-color: #dc2626 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 25px rgba(220, 38, 38, 0.2) !important;
        }
        /* Tarjeta Anterior (Se desplaza a la IZQUIERDA) */
        .tarjeta-3d.anterior {
            opacity: 0.35 !important;
            transform: translateX(-65%) scale(0.85) !important; /* Desplazamiento ancho en PC */
            z-index: 20 !important;
            pointer-events: auto !important;
        }
        /* Tarjeta Siguiente (Se desplaza a la DERECHA) */
        .tarjeta-3d.siguiente {
            opacity: 0.35 !important;
            transform: translateX(65%) scale(0.85) !important; /* Desplazamiento ancho en PC */
            z-index: 20 !important;
            pointer-events: auto !important;
        }
    }

    /* ==========================================================================
       CONFIGURACIÓN PARA CELULARES (MÓVIL - EFECTO CERRADO ANTI-DESCUADRE)
       ========================================================================== */
    @media (max-width: 767px) {
        .carousel-contenedor {
            overflow: hidden !important; /* Capa de seguridad para que el cel no haga scroll horizontal */
            height: 480px !important;
        }
        .cards-wrapper {
            max-width: 88% !important; /* Margen de respiro para las orillas del teléfono */
            height: 410px !important;
        }
        /* Tarjeta Activa en Cel */
        .tarjeta-3d.activa {
            opacity: 1 !important;
            transform: translateX(0) scale(1) !important;
            z-index: 30 !important;
            pointer-events: auto !important;
            border-color: #dc2626 !important;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.8) !important;
        }
        /* Tarjeta Anterior en Cel: La cerramos tantito para que no se salga de la pantalla */
        .tarjeta-3d.anterior {
            opacity: 0.15 !important;
            transform: translateX(-28%) scale(0.88) !important; /* Desplazamiento corto inteligente */
            z-index: 20 !important;
            pointer-events: none !important;
        }
        /* Tarjeta Siguiente en Cel */
        .tarjeta-3d.siguiente {
            opacity: 0.15 !important;
            transform: translateX(25%) scale(0.88) !important; /* Desplazamiento corto inteligente */
            z-index: 20 !important;
            pointer-events: none !important;
        }
        
        /* Ajuste de compresión para el marco en móviles */
        .contenedor-foto-clima {
            height: 130px !important; /* Ajuste fino de altura en cel */
            padding: 8px !important; /* Margen interno para que no asfixie la foto */
        }
        
        /* ¡La magia, pariente! Cambiamos a contain para que se comprima y quepa entero */
        .foto-clima-3d {
            object-fit: contain !important;
            opacity: 0.9 !important;
        }
    }

    /* Tarjetas en espera (Ocultas al centro) */
    .tarjeta-3d.oculta {
        opacity: 0 !important;
        transform: translateX(0) scale(0.7) !important;
        z-index: 10 !important;
        pointer-events: none !important;
    }
</style>
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased min-h-screen relative overflow-x-hidden">

    <div class="glow-bg"></div>

    <nav class="bg-[#16161a]/80 backdrop-blur-md border-b border-[#27272a] sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="index.php" class="text-xl font-bold tracking-wider text-white hover:opacity-90 transition">SERVI<span class="text-[#dc2626]">CLIMAS</span></a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['cliente_id'])): ?>
                    <a href="perfil.php" class="bg-[#27272a] hover:bg-zinc-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md">Mi Perfil</a>
                <?php else: ?>
                    <a href="login.php" class="text-zinc-300 hover:text-white text-xs font-semibold transition-colors duration-200">Entrar</a>
                    <a href="registro.php" class="bg-[#dc2626] hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-900/20">Registrarme</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main id="contenido-principal" class="max-w-[1400px] mx-auto px-4 py-12 relative z-10 select-none space-y-16">
        
    <div class="carousel-contenedor">
        
        <button onclick="cambiarVista('anterior')" class="absolute left-4 md:left-10 w-10 h-10 rounded-full bg-zinc-900/80 border border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-600 flex items-center justify-center transition active:scale-95 z-40">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </button>

        <div class="cards-wrapper">
            
            <div id="card-boton" class="tarjeta-3d activa bg-[#16161a]/80 backdrop-blur-md border border-[#27272a] rounded-2xl p-6 sm:p-10 shadow-2xl text-center space-y-6">
                <span class="text-[9px] sm:text-xs font-bold uppercase text-[#dc2626] tracking-widest bg-red-950/40 border border-red-900/60 px-4 py-1.5 rounded-full inline-block backdrop-blur-sm">
                    Servicios Profesionales de Aire Acondicionado
                </span>
                <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-tight leading-none text-white">
                    Evite el calorón, cotice su instalación en caliente
                </h1>
                <p class="text-xs text-zinc-300 max-w-md mx-auto leading-relaxed">
                    Calculamos el costo base de su service y materiales extras al instante con nuestra herramienta digital de geolocalización.
                </p>
                <button class="inline-flex items-center justify-center bg-gradient-to-r from-[#dc2626] to-[#b91c1c] text-white text-xs sm:text-sm font-bold uppercase tracking-wider px-6 py-3 rounded-full border border-[#ef4444] shadow-lg shadow-red-950/50 hover:scale-105 transition-all duration-300 mx-auto">
                    <i class="fas fa-calculator mr-2 text-xs opacity-90"></i> 
                    <span>Iniciar Cotización Express</span>
                </button>
            </div>

            <div id="card-mirage" onclick="seleccionarTarjetaDirecta(1)" class="tarjeta-3d siguiente bg-[#16161a] border border-[#27272a] rounded-2xl overflow-hidden shadow-2xl cursor-pointer group">
                <div class="contenedor-foto-clima">
                    <span class="absolute top-3 left-3 bg-[#dc2626] text-white text-[9px] font-black uppercase px-2 py-0.5 rounded tracking-wider shadow-md z-10">¡Oferta!</span>
                    <img src="/img/mirage-xlife-1ton.png" alt="Mirage Xlife" class="foto-clima-3d group-hover:opacity-100">
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex justify-between items-baseline">
                            <h2 class="text-white font-black text-lg uppercase tracking-tight group-hover:text-red-500 transition">Mirage Xlife 1 Ton</h2>
                            <div class="text-right">
                                <span class="block text-[10px] text-zinc-500 line-through">$7,499</span>
                                <span class="text-sm font-black text-white">$6,199 MXN</span>
                            </div>
                        </div>
                        <p class="text-zinc-400 text-[11px] leading-relaxed mt-1">Solo Frío, 110V. Ideal para recámaras shicas. Eficiencia estándar calibrada especialmente para cuidar al máximo el recibo de la CFE.</p>
                    </div>
                    <button class="w-full bg-[#27272a] hover:bg-[#dc2626] text-white text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 border border-zinc-700/40 boton-cotizar-tarjeta">
                        <i class="fa-brands fa-whatsapp text-sm"></i> Cotizar este
                    </button>
                </div>
            </div>

            <div id="card-magnum" onclick="seleccionarTarjetaDirecta(2)" class="tarjeta-3d oculta bg-[#16161a] border border-[#27272a] rounded-2xl overflow-hidden shadow-2xl cursor-pointer group">
                <div class="contenedor-foto-clima">
                    <span class="absolute top-3 left-3 bg-red-600 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded tracking-wider shadow-md z-10">Ahorrador</span>
                    <img src="/img/magnum-inverter-1.5ton.jpg" alt="Magnum Inverter" class="foto-clima-3d group-hover:opacity-100">
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex justify-between items-baseline">
                            <h2 class="text-white font-black text-lg uppercase tracking-tight group-hover:text-red-500 transition">Magnum Inverter 1.5</h2>
                            <div class="text-right">
                                <span class="block text-[10px] text-zinc-500 line-through">$11,899</span>
                                <span class="text-sm font-black text-white">$9,950 MXN</span>
                            </div>
                        </div>
                        <p class="text-zinc-400 text-[11px] leading-relaxed mt-1">Frío/Calor, 220V. Perfecto para salas o áreas medianas. Tecnología inverter de última generación que ahorra hasta el 60% de luz.</p>
                    </div>
                    <button class="w-full bg-[#27272a] hover:bg-[#dc2626] text-white text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 border border-zinc-700/40 boton-cotizar-tarjeta">
                        <i class="fa-brands fa-whatsapp text-sm"></i> Cotizar este
                    </button>
                </div>
            </div>

            <div id="card-carrier" onclick="seleccionarTarjetaDirecta(3)" class="tarjeta-3d oculta bg-[#16161a] border border-[#27272a] rounded-2xl overflow-hidden shadow-2xl cursor-pointer group">
                <div class="contenedor-foto-clima">
                    <span class="absolute top-3 left-3 bg-zinc-800 border border-zinc-700 text-zinc-300 text-[9px] font-bold uppercase px-2 py-0.5 rounded tracking-wider shadow-md z-10">Uso Rudo</span>
                    <img src="/img/carrier-2ton.jpg" alt="Carrier 2 Ton" class="foto-clima-3d group-hover:opacity-100">
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex justify-between items-baseline">
                            <h2 class="text-white font-black text-lg uppercase tracking-tight group-hover:text-red-500 transition">Carrier 2 Ton</h2>
                            <div class="text-right">
                                <span class="block text-[10px] text-zinc-500 line-through">$15,400</span>
                                <span class="text-sm font-black text-white">$13,200 MXN</span>
                            </div>
                        </div>
                        <p class="text-zinc-400 text-[11px] leading-relaxed mt-1">Solo Frío, 220V. Alto flujo de aire con turbina optimizada, especial para negocios o espacios grandes abiertos que necesitan enfriar de volada.</p>
                    </div>
                    <button class="w-full bg-[#27272a] hover:bg-[#dc2626] text-white text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 border border-zinc-700/40 boton-cotizar-tarjeta">
                        <i class="fa-brands fa-whatsapp text-sm"></i> Cotizar este
                    </button>
                </div>
            </div>

            <div id="card-trane" onclick="seleccionarTarjetaDirecta(4)" class="tarjeta-3d anterior bg-[#16161a] border border-[#27272a] rounded-2xl overflow-hidden shadow-2xl cursor-pointer group">
                <div class="contenedor-foto-clima">
                    <span class="absolute top-3 left-3 bg-red-950/60 border border-red-900 text-red-400 text-[9px] font-bold uppercase px-2 py-0.5 rounded tracking-wider shadow-md z-10">Premium</span>
                    <img src="/img/trane-inverter.jpg" alt="Trane Inverter" class="foto-clima-3d group-hover:opacity-100">
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex justify-between items-baseline">
                            <h2 class="text-white font-black text-lg uppercase tracking-tight group-hover:text-red-500 transition">Trane Inverter 1 Ton</h2>
                            <div class="text-right">
                                <span class="block text-[10px] text-zinc-500 line-through">$10,999</span>
                                <span class="text-sm font-black text-white">$8,890 MXN</span>
                            </div>
                        </div>
                        <p class="text-zinc-400 text-[11px] leading-relaxed mt-1">Solo Frío, 220V. De las marcas más finas y aguantadoras del mercado mundial. Componentes de grado industrial y sistema ultra silencioso.</p>
                    </div>
                    <button class="w-full bg-[#27272a] hover:bg-[#dc2626] text-white text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 border border-zinc-700/40 boton-cotizar-tarjeta">
                        <i class="fa-brands fa-whatsapp text-sm"></i> Cotizar este
                    </button>
                </div>
            </div>

        </div>

        <button onclick="cambiarVista('siguiente')" class="absolute right-4 md:right-10 w-10 h-10 rounded-full bg-zinc-900/80 border border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-600 flex items-center justify-center transition active:scale-95 z-40">
            <i class="fa-solid fa-chevron-right text-sm"></i>
        </button>
    </div>

    <section class="mt-20">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-black uppercase tracking-tight text-white">LO QUE DICE LA PLEBADA</h2>
                <p class="text-zinc-400 text-xs mt-1">Deslice hacia los lados para ver las opiniones de nuestros clientes.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="todas_las_reseñas.php" class="text-zinc-400 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors duration-200 hidden sm:inline-block">
                    Ver todas <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                </a>
                <button id="btnAbrirModal" class="bg-zinc-800 border border-zinc-700 hover:bg-zinc-750 text-white text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2 transition active:scale-95 shadow-md">
                    <i class="fa-regular fa-pen-to-square"></i> Dejar mi Reseña
                </button>
            </div>
        </div>

        <div class="flex w-full overflow-x-auto space-x-4 md:space-x-6 px-4 pb-6 pt-2 scrollbar-none snap-x snap-mandatory [-webkit-overflow-scrolling:touch]" style="scrollbar-width: none; -ms-overflow-style: none;">
                        
            <div class="w-[80vw] sm:w-[340px] md:w-[380px] max-w-[380px] bg-[#16161a] border border-[#27272a] rounded-xl p-5 md:p-6 space-y-4 snap-start shadow-xl flex-shrink-0 transition-transform duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-red-950 text-[#dc2626] font-bold text-xs flex items-center justify-center border border-red-900/40">JC</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Juan Carlos M.</h4>
                            <span class="text-[10px] text-zinc-500">Hace 2 días</span>
                        </div>
                    </div>
                    <div class="text-[10px] text-amber-400">⭐⭐⭐⭐⭐</div>
                </div>
                <p class="text-zinc-400 text-xs leading-relaxed italic">"Tenia un calorón feo en la sala y los plebes vinieron en caliente. Instalaron el minisplit de 1.5 toneladas bien rápido y quedó jalando al puro tiro."</p>
            </div>

            <div class="w-[80vw] sm:w-[340px] md:w-[380px] max-w-[380px] bg-[#16161a] border border-[#27272a] rounded-xl p-5 md:p-6 space-y-4 snap-start shadow-xl flex-shrink-0 transition-transform duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-zinc-800 text-zinc-400 font-bold text-xs flex items-center justify-center border border-zinc-700">SR</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Sergio R.</h4>
                            <span class="text-[10px] text-zinc-500">Hace 1 semana</span>
                        </div>
                    </div>
                    <div class="text-[10px] text-amber-400">⭐⭐⭐⭐⭐</div>
                </div>
                <p class="text-zinc-400 text-xs leading-relaxed italic">"Muy profesionales los compas de los climas. El aparato no hacia ruido y la instalación limpia, sin dejar desmadre de polvo. El precio está excelente."</p>
            </div>

            <div class="w-[80vw] sm:w-[340px] md:w-[380px] max-w-[380px] bg-[#16161a] border border-[#27272a] rounded-xl p-5 md:p-6 space-y-4 snap-start shadow-xl flex-shrink-0 transition-transform duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-red-950 text-[#dc2626] font-bold text-xs flex items-center justify-center border border-red-900/40">LA</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Luis Alberto G.</h4>
                            <span class="text-[10px] text-zinc-500">Hace 3 semanas</span>
                        </div>
                    </div>
                    <div class="text-[10px] text-amber-400">⭐⭐⭐⭐⭐</div>
                </div>
                <p class="text-zinc-400 text-xs leading-relaxed italic">"El sistema de cotización express está bien cura, te calcula los metros al instante. Vinieron al siguiente día a instalar el Mirage. Ya duermo agusto."</p>
            </div>

            <a href="todas_las_reseñas.php" class="w-[60vw] sm:w-[220px] md:w-[240px] max-w-[240px] mr-4 bg-gradient-to-b from-[#1c1c22] to-[#111115] border border-dashed border-zinc-800 hover:border-[#dc2626] rounded-xl p-6 snap-start flex flex-col justify-center items-center text-center group transition-all duration-300 flex-shrink-0 shadow-lg">
                <div class="w-12 h-12 rounded-full bg-zinc-900 group-hover:bg-red-950/50 border border-zinc-800 group-hover:border-red-900 flex items-center justify-center mb-4 transition-colors duration-300">
                    <i class="fa-solid fa-comments text-zinc-500 group-hover:text-[#dc2626] text-lg transition-colors duration-300"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-wider text-white group-hover:text-[#dc2626] transition-colors duration-300">Ver más reseñas</span>
                <p class="text-[11px] text-zinc-500 mt-2 max-w-[180px] leading-snug">Vea lo que opinan los más de 200 plebes que atendemos al mes.</p>
                <i class="fa-solid fa-circle-arrow-right text-zinc-600 group-hover:text-[#dc2626] text-xl mt-4 transition-all duration-300 transform group-hover:translate-x-1"></i>
            </a>

        </div>
    </section>
</main>

    <div id="modalReseña" class="fixed inset-0 bg-black/85 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-[#16161a] border border-[#27272a] w-full max-w-md rounded-xl overflow-hidden shadow-2xl shadow-black/80 transition-all transform scale-95 opacity-0 duration-300" id="modalCuerpo">
            <div class="px-6 py-4 border-b border-[#27272a] flex items-center justify-between bg-[#1a1a20]">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white">Dejar Testimonio</h3>
                <button id="btnCerrarModal" class="text-zinc-300 hover:text-white transition-colors duration-200"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            
            <form action="index.php" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">

                <div>
                    <label class="label-formulario mb-1.5">Su Nombre o Apodo</label>
                    <input type="text" name="nombre_cliente" required maxlength="60" class="input-oscuro w-full text-xs" placeholder="Ej. Plebe de Culiacán">
                </div>

                <div>
                    <label class="label-formulario mb-1.5">Calificación del Servicio</label>
                    <select name="estrellas" required class="input-oscuro w-full text-xs">
                        <option value="5">⭐⭐⭐⭐⭐ Excelente servicio al puro tiro</option>
                        <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                        <option value="3">⭐⭐⭐ Regular</option>
                        <option value="2">⭐⭐ Malo</option>
                        <option value="1">⭐ Pésimo jale</option>
                    </select>
                </div>

                <div>
                    <label class="label-formulario mb-1.5">Su Comentario / Reseña</label>
                    <textarea name="comentario" required rows="4" maxlength="300" class="input-oscuro w-full text-xs placeholder-zinc-500" placeholder="¿Cómo le pareció el trabajo del instalador?"></textarea>
                </div>

                <button type="submit" name="enviar_reseña" class="btn-rojo-premium w-full font-bold uppercase tracking-wider text-xs py-3.5 rounded-lg transition-all duration-300 mt-2 shadow-lg shadow-red-950/50">
                    Enviar Reseña a Revisión
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnAbrir = document.getElementById('btnAbrirModal');
            const btnCerrar = document.getElementById('btnCerrarModal');
            const modal = document.getElementById('modalReseña');
            const cuerpo = document.getElementById('modalCuerpo');

            if (btnAbrir) {
                btnAbrir.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        cuerpo.classList.remove('scale-95', 'opacity-0');
                        cuerpo.classList.add('scale-100', 'opacity-100');
                    }, 10);
                });
            }

            if (btnCerrar) {
                btnCerrar.addEventListener('click', cerrarElModal);
            }
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) cerrarElModal();
            });

            function cerrarElModal() {
                cuerpo.classList.remove('scale-100', 'opacity-100');
                cuerpo.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        });

        let tarjetasIds = ['card-boton', 'card-mirage', 'card-magnum', 'card-carrier', 'card-trane'];
        let indiceActivo = 0;

        function renderizarCarrusel3D() {
            tarjetasIds.forEach((id, i) => {
                const elemento = document.getElementById(id);
                if (!elemento) return;

                elemento.classList.remove('activa', 'anterior', 'siguiente', 'oculta');

                if (i === indiceActivo) {
                    elemento.classList.add('activa');
                } else if (i === (indiceActivo - 1 + tarjetasIds.length) % tarjetasIds.length) {
                    elemento.classList.add('anterior');
                } else if (i === (indiceActivo + 1) % tarjetasIds.length) {
                    elemento.classList.add('siguiente');
                } else {
                    elemento.classList.add('oculta');
                }
            });
        }

        function cambiarVista(direccion) {
            if (direccion === 'siguiente') {
                indiceActivo = (indiceActivo + 1) % tarjetasIds.length;
            } else {
                indiceActivo = (indiceActivo - 1 + tarjetasIds.length) % tarjetasIds.length;
            }
            renderizarCarrusel3D();
        }

        function seleccionarTarjetaDirecta(indice) {
            indiceActivo = indice;
            renderizarCarrusel3D();
        }

        // Ejecutamos al inicio para acomodar las posiciones
        renderizarCarrusel3D();
    </script>
</body>
</html>
