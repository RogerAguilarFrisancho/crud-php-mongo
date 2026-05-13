<?php
require 'vendor/autoload.php';
// Cargar variables de entorno solo si el archivo .env existe (entorno local)
// En producción (Render), las leeremos directamente del sistema.
if (file_exists(__DIR__ . '/.env')) {
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
}
// Obtener la URI de forma segura
$uri = $_ENV['MONGO_URI'] ?? getenv('MONGO_URI');
try {
// Conectar a Atlas
$cliente = new MongoDB\Client($uri);
// Seleccionar la Base de Datos "app_crud" y la colección "tareas"
$coleccion = $cliente->app_crud->tareas;
} catch (Exception $e) {
die("Error de conexión a MongoDB: " . $e->getMessage());
}
?>