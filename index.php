<?php
session_start();

// Mensajes que deja guardar.php (se muestran una sola vez)
$errores = $_SESSION['errores'] ?? [];
$exito   = $_SESSION['exito'] ?? false;
unset($_SESSION['errores'], $_SESSION['exito']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema web para gestionar el inventario de productos.">

    <title>Sistema de Gestión de Inventario</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <main class="contenedor">

        <header class="encabezado">
            <h1>Sistema de Gestión de Inventario</h1>
            <p>Registro y administración de productos</p>
            <nav class="menu">
                <a href="index.php">Registrar producto</a>
                <a href="listar.php">Ver inventario</a>
            </nav>
        </header>

        <section class="formulario-seccion">

            <h2>Registrar nuevo producto</h2>

            <?php if ($exito): ?>
                <div class="alerta exito">Producto registrado correctamente.</div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="alerta error">
                    <?php foreach ($errores as $error): ?>
                        <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="guardar.php" method="POST" class="formulario">

                <div class="campo">
                    <label for="nombre">Nombre del producto</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                    ></textarea>
                </div>

                <div class="campo">
                    <label for="categoria">Categoría</label>
                    <input
                        type="text"
                        id="categoria"
                        name="categoria"
                        maxlength="60"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="precio">Precio</label>
                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        min="0.01"
                        step="0.01"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="stock">Stock</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        min="0"
                        step="1"
                        required
                    >
                </div>

                <button type="submit" class="boton boton--principal">
                    Registrar producto
                </button>

            </form>

        </section>

    </main>

</body>

</html>