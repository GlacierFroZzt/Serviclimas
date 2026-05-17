<!-- vistas/registro.html.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cuenta | Climas Sinaloa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="estilos_premium.css">
</head>
<body class="bg-[#0c0c0e] text-[#f4f4f5] antialiased flex items-center justify-center min-h-screen px-4 py-8">
    <div class="bg-[#16161a] border border-[#27272a] rounded-xl p-6 w-full max-w-sm space-y-5">
        <div class="text-center">
            <h2 class="text-lg font-black uppercase tracking-tight text-white">Alta de Cliente</h2>
            <p class="text-[11px] text-gray-500 mt-1">Regístrese para guardar la ubicación GPS de su domicilio.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-rose-950/50 border border-rose-800 text-rose-300 px-3 py-2 rounded text-xs font-medium text-center">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php $_SESSION['registro_csrf_token'] = bin2hex(random_bytes(32)); endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-emerald-950/50 border border-emerald-800 text-emerald-300 px-3 py-2 rounded text-xs font-medium text-center">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="registro.php" method="POST" class="space-y-4">
            <!-- Candado Anti-CSRF inyectado -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['registro_csrf_token'] ?? ''; ?>">

            <div>
                <label class="label-formulario">Nombre Completo</label>
                <input type="text" name="nombre" required placeholder="Ej. Juan Pérez" class="input-oscuro w-full text-sm mt-1">
            </div>
            <div>
                <label class="label-formulario">Correo Electrónico</label>
                <input type="email" name="correo" required placeholder="juan@correo.com" class="input-oscuro w-full text-sm mt-1">
            </div>
            <div>
                <label class="label-formulario">Teléfono Celular (10 dígitos)</label>
                <input type="tel" name="telefono" required placeholder="6671234567" class="input-oscuro w-full text-sm mt-1">
            </div>
            <div>
                <label class="label-formulario">Contraseña de Acceso</label>
                <input type="password" name="password" required minlength="8" placeholder="Mínimo 8 caracteres" class="input-oscuro w-full text-sm mt-1">
            </div>
            <button type="submit" class="btn-rojo-premium w-full font-bold uppercase tracking-wider text-xs py-3.5 rounded-lg transition pt-2.5">
                Crear mi Perfil Seguro
            </button>
        </form>

        <div class="text-center border-t border-[#27272a] pt-4">
            <p class="text-[11px] text-gray-500">¿Ya está dado de alta? <a href="login.php" class="text-red-500 hover:underline font-bold">Inicie sesión aquí</a></p>
        </div>
    </div>
</body>
</html>