<?php

// ==========================================
// CONFIGURACION DE LA BASE DE DATOS
// ==========================================

$servidor = "localhost";
$base_datos = "db_sistema_web";
$usuario = "root";
$contrasena = "";

// ==========================================
// CONEXION MEDIANTE PDO
// ==========================================

try {

    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$base_datos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    // Configurar el manejo de errores
    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Configurar resultados como arreglos asociativos
    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    // Usar sentencias preparadas reales (no emuladas)
    $conexion->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $error) {

    // No mostrar detalles internos al usuario
    error_log("Error de conexion: " . $error->getMessage());

    die("No fue posible conectar con la base de datos.");

}

?>