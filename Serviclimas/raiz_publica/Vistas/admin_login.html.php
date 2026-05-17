<!-- vistas/admin_login.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consola Operativa | Login de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#09090b] text-[#f4f4f5] antialiased flex items-center justify-center min-h-screen px-4">
    <div class="bg-[#121215] border-2 border-red-950 rounded-xl p-6 w-full max-w-sm space-y-6 shadow-xl shadow-red-950/10">
        
        <div class="text-center space-y-1">
            <div class="inline-flex w-8 h-8 rounded-lg bg-red-950/50 border border-red-800 items-center justify-center text-red-500 font-mono text-xs font-black">
                HQ
            </div>
            <h2 class="text-xs font-black uppercase tracking-widest text-white block pt-2">Consola de Control Central</h2>
            <p class="text-[10px] text-zinc-500 uppercase">Área restringida para gerencia y mantenimiento técnico.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-950/60 border border-red-900 text-red-200 px-3 py-2 rounded text-[11px] font-medium text-center">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="admin_login.php" method="POST" class="space-y-4">
            <div>
                <label class="label-formulario">Llave de Usuario (Correo)</label>
                <input type="email" name="correo" required placeholder="admin@climas.com" class="input-oscuro w-full text-sm mt-1 border-zinc-800 focus:border-red-800">
            </div>
            <div>
                <label class="label-formulario">Token / Contraseña Crítica</label>
                <input type="password" name="password" required placeholder="••••••••" class="input-oscuro w-full text-sm mt-1 border-zinc-800 focus:border-red-800">
            </div>
            <button type="submit" class="w-full bg-red-700 hover:bg-red-600 text-white font-black uppercase tracking-wider text-xs py-4 rounded-lg transition shadow-md shadow-red-900/10 pt-3.5">
                Autenticar Credenciales
            </button>
        </form>

        <div class="text-center">
            <a href="login.php" class="text-[10px] text-zinc-500 hover:text-zinc-300 font-mono transition uppercase">
                <i class="fa-solid fa-arrow-left"></i> Volver al portal público
            </a>
        </div>
    </div>
</body>
</html>