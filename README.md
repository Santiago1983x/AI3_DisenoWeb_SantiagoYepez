# Sistema de Gestión de Inventario

Actividad Integradora 3 – Universidad ECOTEC  
Módulo web dinámico en PHP con base de datos MySQL/MariaDB.


## Descripción

Aplicación web que permite registrar productos de un inventario (nombre,
descripción, categoría, precio y stock), consultarlos en una tabla y buscarlos
por nombre o categoría.

## Tecnologías utilizadas

- PHP 8.4
- MySQL / MariaDB
- HTML5
- CSS3
- PDO (PHP Data Objects)
- XAMPP

## Estructura del proyecto

```text
DisenoWeb_Santiago_Yepez/
|-- index.php
|-- conexion.php
|-- guardar.php
|-- listar.php
|-- .gitignore
|-- README.md
|-- css/
│   |-- styles.css
|-- database/
    |-- db_sistema_web.sql
```

## Requisitos para ejecutar el proyecto

- XAMPP o un entorno compatible.
- Apache.
- MySQL o MariaDB.
- PHP con extensión PDO MySQL habilitada.
- Un navegador web.

## Instalación y ejecución

1. Copiar la carpeta del proyecto dentro del directorio `htdocs` de XAMPP.
2. Iniciar los servicios Apache y MySQL.
3. Abrir phpMyAdmin.
4. Importar el archivo `database/db_sistema_web.sql`.
5. Verificar los parámetros de conexión en `conexion.php`.
6. Abrir el navegador y acceder a:

```text
http://localhost/DisenoWeb_Santiago_Yepez/
```

## Base de datos

Nombre: `db_sistema_web`

Tabla principal: `productos`.

La tabla almacena información como identificador, nombre, descripción, categoría, precio, stock y fecha de creación.

## Uso

- **Registrar producto:** completa el formulario en `index.php`.
- **Ver inventario:** entra a `listar.php`. Allí puedes buscar por nombre o
  categoría; el stock de 5 unidades o menos se resalta en rojo.

## Seguridad

El proyecto utiliza PDO con sentencias preparadas para proteger las consultas SQL. 
También aplica `htmlspecialchars()` al mostrar datos para prevenir la ejecución de contenido HTML o JavaScript no confiable.

## Autor

Santiago Yepez

Proyecto académico desarrollado