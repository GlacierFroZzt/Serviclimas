<?php
// db.php

// 1. ARCHIVO DE CONFIGURACIÓN DINÁMICO
// Si está en Render, lee las variables de producción; si no, agarra lo local
$host    = getenv('DB_HOST') ?: 'b4m7cskh0hp3etblz5rx-mysql.services.clever-cloud.com';
$db      = getenv('DB_NAME') ?: 'b4m7cskh0hp3etblz5rx';
$user    = getenv('DB_USER') ?: 'u0i2twmpxggurnf0';
$pass    = getenv('DB_PASSWORD') ?: 'K1Oo8XmxTBuuNuiaSGvh'; 
$charset = 'utf8mb4'; 

// 2. CONSTRUCCIÓN DE LA CADENA DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. ATRIBUTOS DE SEGURIDAD
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log("Fallo de conexión en db.php: " . $e->getMessage());
    exit('El sistema se encuentra en mantenimiento técnico interno, viejo. Vuelva en unos minutos.');
}
?>