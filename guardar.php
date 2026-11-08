<?php

session_start();

// ==========================================
// VERIFICAR EL METODO DE ENVIO
// ==========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// ==========================================
// CONEXION CON LA BASE DE DATOS
// ==========================================

require_once "conexion.php";

// ==========================================
// RECIBIR LOS DATOS DEL FORMULARIO
// ==========================================

$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$precio = trim($_POST["precio"] ?? "");
$stock = trim($_POST["stock"] ?? "");

$errores = [];

// ==========================================
// VALIDAR LOS DATOS RECIBIDOS
// ==========================================

// Validar nombre
if ($nombre === "") {
    $errores[] = "El nombre del producto es obligatorio.";
} elseif (mb_strlen($nombre, "UTF-8") > 100) {
    $errores[] = "El nombre no puede superar los 100 caracteres.";
}

// Validar descripción
if (mb_strlen($descripcion, "UTF-8") > 65535) {
    $errores[] = "La descripción es demasiado extensa.";
}

// Validar categoría
if ($categoria === "") {
    $errores[] = "La categoría es obligatoria.";
} elseif (mb_strlen($categoria, "UTF-8") > 60) {
    $errores[] = "La categoría no puede superar los 60 caracteres.";
}

// Validar precio
if (
    !preg_match('/^\d+(\.\d{1,2})?$/', $precio) ||
    (float) $precio <= 0 ||
    (float) $precio > 99999999.99
) {
    $errores[] = "Ingrese un precio válido, mayor que cero y con máximo dos decimales.";
}

// Validar stock (entero entre 0 y el máximo que admite la columna INT)
$stock_validado = filter_var($stock, FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 0, "max_range" => 2147483647]
]);

if ($stock_validado === false) {
    $errores[] = "El stock debe ser un número entero igual o mayor que cero.";
}

// ==========================================
// SI EXISTEN ERRORES, VOLVER AL FORMULARIO
// ==========================================

if (!empty($errores)) {

    $_SESSION["errores"] = $errores;

    header("Location: index.php");
    exit;
}

// ==========================================
// INSERTAR EL PRODUCTO EN LA BASE DE DATOS
// ==========================================

try {

    $sql = "INSERT INTO productos
            (nombre, descripcion, categoria, precio, stock)
            VALUES
            (:nombre, :descripcion, :categoria, :precio, :stock)";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ":nombre" => $nombre,
        ":descripcion" => $descripcion !== "" ? $descripcion : null,
        ":categoria" => $categoria,
        ":precio" => $precio,
        ":stock" => $stock_validado
    ]);

    // Guardar mensaje de éxito
    $_SESSION["exito"] = true;

} catch (PDOException $error) {

    // Registrar el error internamente
    error_log("Error al guardar producto: " . $error->getMessage());

    // Mostrar mensaje general al usuario
    $_SESSION["errores"] = [
        "No fue posible registrar el producto. Intente nuevamente."
    ];

}

// ==========================================
// REGRESAR AL FORMULARIO
// ==========================================

header("Location: index.php");
exit;

?>