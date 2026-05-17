<?php
// db.php

// 1. ARCHIVO DE CONFIGURACIÓN DINÁMICO
// Forzamos la lectura buscando primero en $_ENV y luego en getenv
$host     = $_ENV['DB_HOST']     ?? getenv('DB_HOST')     ?? 'b4m7cskh0hp3etblz5rx-mysql.services.clever-cloud.com';
$db       = $_ENV['DB_NAME']     ?? getenv('DB_NAME')     ?? 'b4m7cskh0hp3etblz5rx';
$user     = $_ENV['DB_USER']     ?? getenv('DB_USER')     ?? 'u0i2twmpxggurnf0';
$pass     = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? 'K1Oo8XmxTBuuNuiaSGvh'; 
$charset  = 'utf8mb4'; 

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
    // Esto va a pintar el error real en la bitácora de Render para saber qué falló
    error_log("Fallo de conexión en db.php: " . $e->getMessage());
    
    // Mostramos el mensaje limpio al cliente
    exit('El sistema se encuentra en mantenimiento técnico interno, viejo. Vuelva en unos minutos.');
}
?>
