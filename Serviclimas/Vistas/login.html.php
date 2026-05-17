<!-- vistas/login.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Climas Sinaloa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased flex items-center justify-center min-h-screen px-4">
    <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-6 w-full max-w-sm space-y-6">
        <div class="text-center">
            <h2 class="text-lg font-black uppercase tracking-tight text-white">Ingreso de Clientes</h2>
            <p class="text-[11px] text-gray-500 mt-1">Consulte sus cotizaciones e instalaciones al momento.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-rose-950/50 border border-rose-800 text-rose-300 px-3 py-2 rounded text-xs font-medium text-center">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="label-formulario">Correo Electrónico</label>
                <input type="email" name="correo" required placeholder="ejemplo@correo.com" class="input-oscuro w-full text-sm mt-1">
            </div>
            <div>
                <label class="label-formulario">Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••" class="input-oscuro w-full text-sm mt-1">
            </div>
            <button type="submit" class="btn-rojo-premium w-full font-bold uppercase tracking-wider text-xs py-3.5 rounded-lg transition pt-2.5">
                Entrar a mi Cuenta
            </button>
        </form>

        <div class="text-center border-t border-[#27272a] pt-4">
            <p class="text-[11px] text-gray-500">¿No tiene cuenta aún? <a href="registro.php" class="text-red-500 hover:underline font-bold">Regístrese aquí</a></p>
        </div>
    </div>
</body>
</html>