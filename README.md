
# 🎵 Asociación Jukebox PHP

## Descripción

Aplicación web desarrollada como práctica acumulativa de PHP
del módulo Desenvolupament Web en Entorn Servidor (RA1).

El objetivo es crear un Jukebox musical en el que los usuarios
puedan registrarse, iniciar sesión, consultar un catálogo
de canciones y administrarlo mediante formularios PHP.

La aplicación también dispone de un reproductor musical
desarrollado con JavaScript y Howler.js.

## Funcionalidades

- Registro de usuarios.
- Inicio y cierre de sesión.
- Validación de formularios.
- Contraseñas protegidas mediante hash.
- Gestión de sesiones PHP.
- Protección CSRF en operaciones de modificación.
- Catálogo musical almacenado en JSON.
- Creación de canciones.
- Edición de canciones.
- Eliminación de canciones.
- API PHP para obtener el catálogo.
- Reproductor con Play, Pausa, Anterior y Siguiente.
- Interfaz web con diseño oscuro.

## Tecnologías utilizadas

| Tecnología | Utilización |
|---|---|
| PHP 8.5 | Lógica del servidor |
| Apache | Servidor web |
| Docker | Contenedores |
| Docker Compose | Gestión de servicios |
| PostgreSQL | Prácticas de conexión PDO |
| MariaDB | Prácticas de conexión PDO |
| JSON | Almacenamiento de canciones y usuarios |
| JavaScript | Interactividad |
| Howler.js | Reproducción musical |
| HTML5 | Estructura |
| CSS3 | Diseño |

## Estructura del proyecto

    asociacion-jukebox-php/
    ├── README.md
    ├── .gitignore
    ├── compose.yaml
    ├── src/
    ├── docker/
    │   ├── compose.yaml
    │   ├── Dockerfile
    │   ├── data/
    │   └── src/
    │       ├── home.php
    │       ├── login.php
    │       ├── canciones.php
    │       ├── api/
    │       ├── lib/
    │       ├── scripts/
    │       ├── assets/
    │       └── audio/
    └── data-ejemplo/

## Requisitos

Para ejecutar el proyecto es necesario instalar:

- Docker Desktop
- Git
- Un navegador web
- Visual Studio Code (recomendado)

## Instalación

### 1. Descargar el proyecto

Clonar el repositorio:

    git clone https://github.com/TU_USUARIO/asociacion-jukebox-php.git

Entrar en la carpeta descargada:

    cd asociacion-jukebox-php

### 2. Preparar la configuración

Entrar en la carpeta Docker:

    cd docker

Si existe un archivo .env.example, copiarlo a .env
y completar las variables necesarias.

Nunca subir el archivo .env a GitHub.

Crear la carpeta data si no existe:

    mkdir data

En una instalación nueva, preparar canciones.json
y usuarios.json utilizando datos ficticios de ejemplo.

No sobrescribir los datos de una instalación existente.

### 3. Ejecutar Docker

Desde la carpeta docker:

    docker compose up -d --build

Comprobar que los contenedores funcionan:

    docker compose ps

### 4. Abrir la aplicación

Inicio de sesión:

http://localhost:8091/login.php

Página principal:

http://localhost:8091/home.php

Gestión de canciones:

http://localhost:8091/canciones.php

Para detener los contenedores:

    docker compose down

## Funcionamiento del catálogo

Las canciones se almacenan en un archivo JSON privado.

Cada canción tiene información como:

- Identificador
- Título
- Artista
- Categoría
- Duración
- Ruta del audio

PHP realiza las operaciones de creación, lectura,
actualización y eliminación de canciones.

El reproductor JavaScript obtiene las canciones
mediante la API PHP.

## Reproductor musical

El reproductor utiliza JavaScript y Howler.js.

Su funcionamiento es:

1. El usuario inicia sesión.
2. Accede a la página principal.
3. JavaScript solicita las canciones a la API PHP.
4. La API consulta el catálogo JSON.
5. El navegador muestra las canciones disponibles.
6. Howler.js permite reproducir y controlar el audio.

## Seguridad

El proyecto trabaja con:

- Sesiones PHP.
- Validación de entradas.
- Escape de contenido HTML.
- Hash de contraseñas.
- Protección CSRF.
- Restricción de acceso a páginas privadas.
- Datos JSON fuera del directorio público.

Es un proyecto educativo diseñado para ejecutarse
en un entorno local de desarrollo.

## Archivos musicales

Los archivos MP3 comerciales utilizados durante
las pruebas locales no se distribuyen en GitHub.

Para reproducir canciones en una instalación nueva,
es necesario aportar archivos propios o autorizados
y configurar las rutas correspondientes.

Los audios deben colocarse en:

    docker/src/audio/

## Pruebas

Las pruebas de aceptación comprueban:

- Registro e inicio de sesión.
- Creación de canciones.
- Edición de canciones.
- Eliminación de canciones.
- Respuestas de la API.
- Funcionamiento del reproductor.
- Persistencia de archivos JSON.
- Protección de rutas y formularios.

Los resultados deben documentarse con evidencias reales.

## Autor y contexto académico

Proyecto de DAW (Desenvolupament d'Aplicacions Web).

Centro: Monlau La Sagrera.

Módulo: Desenvolupament Web en Entorn Servidor.

Práctica: PHP RA1 - Jukebox musical.

## Estado del proyecto

Aplicación desarrollada para entorno local.
La verificación final y sus evidencias forman parte
de la entrega académica.

## Derechos de autor

Proyecto realizado con fines educativos.

Las canciones de terceros pertenecen a sus
respectivos titulares y no se distribuyen
sin autorización.
