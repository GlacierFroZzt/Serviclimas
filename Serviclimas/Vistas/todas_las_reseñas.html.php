<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonios de la Plebada - Serviclimas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../raiz_publica/estilos_premium.css">
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased min-h-screen relative overflow-x-hidden">

    <div class="glow-bg" style="position: fixed; top: -10%; left: -10%; width: 50%; height: 50%; background: radial-gradient(circle, rgba(220,38,38,0.05) 0%, transparent 70%); z-index: 1; pointer-events: none;"></div>

    <nav class="bg-[#16161a]/80 backdrop-blur-md border-b border-[#27272a] sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="index.php" class="text-xl font-bold tracking-wider text-white hover:opacity-90 transition">
                <i class="fa-solid fa-chevron-left text-xs mr-2 text-zinc-500"></i>SERVI<span class="text-[#dc2626]">CLIMAS</span>
            </a>
            <span class="text-xs font-black uppercase text-zinc-400 tracking-widest bg-zinc-900/60 border border-zinc-800 px-4 py-1.5 rounded-full">
                Panel de Testimonios
            </span>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-12 relative z-10">
        
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-white">
                LO QUE DICE <span class="text-[#dc2626]">LA PLEBADA</span>
            </h1>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Aquí no hay shamuco, viejón. Estas son las opiniones 100% reales de los clientes que ya mandaron a la shingada el calor gracias a nuestros servicios.
            </p>
            <div class="h-1 w-20 bg-[#dc2626] mx-auto rounded-full mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($reseñas_pantalla)): ?>
                <div class="col-span-full text-center py-12 bg-[#16161a] border border-[#27272a] rounded-xl">
                    <i class="fa-regular fa-comment-dots text-zinc-600 text-3xl mb-3 block"></i>
                    <p class="text-zinc-400 text-sm font-semibold">Aún no hay reseñas registradas, sea el primero viejo.</p>
                </div>
            <?php else: ?>
                <?php foreach ($reseñas_pantalla as $res): ?>
                    <?php 
                        // Generar iniciales dinámicas y repetir las estrellas en caliente
                        $iniciales = isset($res['iniciales']) ? $res['iniciales'] : strtoupper(substr($res['nombre'], 0, 2));
                        $estrellas = str_repeat('⭐', $res['estrellas']);
                    ?>
                    <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-6 space-y-4 shadow-xl hover:border-zinc-700 transition duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-red-950/60 text-[#dc2626] font-black text-xs flex items-center justify-center border border-red-900/40">
                                        <?= htmlspecialchars($iniciales) ?>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white"><?= htmlspecialchars($res['nombre']) ?></h4>
                                        <span class="text-[10px] text-zinc-500"><?= htmlspecialchars($res['fecha']) ?></span>
                                    </div>
                                </div>
                                <div class="text-[10px] tracking-tighter bg-zinc-900 px-2 py-1 rounded border border-zinc-800">
                                    <?= $estrellas ?>
                                </div>
                            </div>
                            <p class="text-zinc-300 text-xs leading-relaxed italic">
                                "<?= htmlspecialchars($res['comentario']) ?>"
                            </p>
                        </div>
                        <div class="pt-2 border-t border-zinc-800/60 text-right">
                            <span class="text-[9px] font-bold text-[#dc2626] uppercase tracking-wider"><i class="fa-solid fa-circle-check text-[8px] mr-1"></i> Cliente Verificado</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($total_paginas > 1): ?>
            <div class="flex justify-center items-center space-x-2 mt-12">
                <a href="?p=<?= $pagina_actual - 1 ?>" class="px-3 py-2 bg-zinc-900 border border-zinc-800 rounded-lg text-zinc-400 hover:text-white hover:border-zinc-600 text-xs transition <?= ($pagina_actual <= 1) ? 'pointer-events-none opacity-40' : '' ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="?p=<?= $i ?>" class="px-4 py-2 text-xs font-bold rounded-lg border transition-all duration-200 <?= ($i === $pagina_actual) ? 'bg-[#dc2626] text-white border-[#dc2626] shadow-lg shadow-red-950/40' : 'bg-zinc-900 border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-600' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <a href="?p=<?= $pagina_actual + 1 ?>" class="px-3 py-2 bg-zinc-900 border border-zinc-800 rounded-lg text-zinc-400 hover:text-white hover:border-zinc-600 text-xs transition <?= ($pagina_actual >= $total_paginas) ? 'pointer-events-none opacity-40' : '' ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        <?php endif; ?>

        <div class="text-center mt-16">
            <a href="index.php" class="inline-flex items-center justify-center bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-wider px-6 py-3.5 rounded-xl border border-zinc-800 hover:border-zinc-700 shadow-md transition-all active:scale-95">
                <i class="fa-solid fa-house mr-2 text-zinc-400"></i> Volver a la página principal
            </a>
        </div>

    </main>

    <footer class="border-t border-[#27272a] bg-[#111113] py-6 text-center mt-20 relative z-10">
        <p class="text-[11px] text-zinc-500 font-medium tracking-wide">© 2026 SERVICLIMAS - Culiacán, Sinaloa. Al puro tiro con el aire acondicionado.</p>
    </footer>

</body>
</html>