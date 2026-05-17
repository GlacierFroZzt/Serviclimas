<?php
// db.php

// 1. ARCHIVO DE CONFIGURACIÓN CENTRALIZADO DE LA BASE DE DATOS
// Guardamos las credenciales en variables limpias
$host    = 'localhost';
$db      = 'Serviclimas';
$user    = 'root';
$pass    = 'Admin123'; // Aquí mete la contraseña real de su servidor MySQL
$charset = 'utf8mb4'; // Forzamos soporte completo para emojis y caracteres especiales

// 2. CONSTRUCCIÓN DE LA CADENA DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. ATRIBUTOS DE SEGURIDAD EXTREMA PARA PDO
$options = [
    // Activa el modo de excepciones para atrapar fallos con bloques try-catch
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    
    // Configura el mapeo para que los datos devueltos sean arreglos asociativos limpios
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    
    // DESACTIVAR EMULACIÓN DE CONSULTAS PREPARADAS (Blindaje maestro anti-inyecciones SQL)
    // Obliga al motor de MySQL a pre-compilar la estructura antes de meterle datos
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 4. INSTANCIACIÓN DE LA CONEXIÓN MAESTRA
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (\PDOException $e) {
    // SEGURIDAD CRÍTICA: Registramos el error real en las entrañas del servidor (error.log)
    error_log("Fallo de conexión en db.php: " . $e->getMessage());
    
    // Jamás le enseñamos el '$e->getMessage()' al navegador, porque expondría rutas o contraseñas
    exit('El sistema se encuentra en mantenimiento técnico interno, viejo. Vuelva en unos minutos.');
}
?>