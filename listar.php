<?php

// ==========================================
// CONEXION CON LA BASE DE DATOS
// ==========================================

require_once "conexion.php";

// ==========================================
// RECIBIR EL TERMINO DE BUSQUEDA
// ==========================================

$busqueda = isset($_GET["q"]) && is_string($_GET["q"])
    ? trim($_GET["q"])
    : "";

// Limitar la longitud en el servidor (igual que el maxlength del formulario)
$busqueda = mb_substr($busqueda, 0, 100, "UTF-8");

$productos = [];
$error = "";

// ==========================================
// CONSULTAR LOS PRODUCTOS
// ==========================================

try {

    if ($busqueda !== "") {

        // Escapar comodines de LIKE
        $termino = "%" . addcslashes($busqueda, "%_\\") . "%";

        // Consulta con parámetros únicos
        $sql = "SELECT id, nombre, descripcion, categoria,
                       precio, stock, fecha_creacion
                FROM productos
                WHERE nombre LIKE :nombre
                   OR categoria LIKE :categoria
                ORDER BY id DESC";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":nombre" => $termino,
            ":categoria" => $termino
        ]);

    } else {

        // Consulta general del inventario
        $sql = "SELECT id, nombre, descripcion, categoria,
                       precio, stock, fecha_creacion
                FROM productos
                ORDER BY id DESC";

        $consulta = $conexion->query($sql);
    }

    $productos = $consulta->fetchAll();

} catch (PDOException $e) {

    error_log("Error al consultar productos: " . $e->getMessage());

    $error = "No fue posible consultar el inventario. Intente nuevamente.";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Listado y búsqueda de productos del inventario.">

    <title>Inventario de productos</title>

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <main class="contenedor contenedor--ancho">

        <header class="encabezado">

            <h1>Sistema de Gestión de Inventario</h1>

            <p>Registro y administración de productos</p>

            <nav class="menu">
                <a href="index.php">Registrar producto</a>
                <a href="listar.php">Ver inventario</a>
            </nav>

        </header>

        <section class="formulario-seccion">

            <h2>Inventario de productos</h2>

            <!-- FORMULARIO DE BUSQUEDA -->

            <form action="listar.php" method="GET" class="buscador">

                <input
                    type="text"
                    name="q"
                    placeholder="Buscar por nombre o categoría"
                    maxlength="100"
                    value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>"
                >

                <button type="submit" class="boton boton--principal">
                    Buscar
                </button>

                <?php if ($busqueda !== ""): ?>

                    <a href="listar.php" class="boton boton--secundario">
                        Limpiar
                    </a>

                <?php endif; ?>

            </form>

            <!-- RESULTADOS DE LA CONSULTA -->

            <?php if ($error !== ""): ?>

                <div class="alerta error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php elseif (empty($productos)): ?>

                <p class="sin-resultados">
                    <?= $busqueda !== ""
                        ? "No se encontraron productos para su búsqueda."
                        : "Aún no hay productos registrados." ?>
                </p>

            <?php else: ?>

                <p class="resumen">
                    Productos encontrados:
                    <strong><?= count($productos) ?></strong>
                </p>

                <div class="tabla-contenedor">

                    <table class="tabla">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th class="numero">Precio</th>
                                <th class="numero">Stock</th>
                                <th>Fecha de registro</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($productos as $p): ?>

                                <?php
                                $fecha = strtotime($p["fecha_creacion"]);
                                ?>

                                <tr>

                                    <td><?= (int) $p["id"] ?></td>

                                    <td>
                                        <?= htmlspecialchars($p["nombre"], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <?= $p["descripcion"] !== null
                                            ? htmlspecialchars(
                                                mb_strimwidth(
                                                    $p["descripcion"],
                                                    0,
                                                    60,
                                                    "...",
                                                    "UTF-8"
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            )
                                            : "-" ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($p["categoria"], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td class="numero">
                                        $<?= number_format((float) $p["precio"], 2) ?>
                                    </td>

                                    <td class="numero <?= (int) $p["stock"] <= 5 ? 'stock-bajo' : '' ?>">
                                        <?= (int) $p["stock"] ?>
                                    </td>

                                    <td>
                                        <?= $fecha !== false
                                            ? date("d/m/Y H:i", $fecha)
                                            : "-" ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>