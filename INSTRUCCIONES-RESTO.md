# Jukebox PHP — continuación de la práctica

Este paquete contiene los archivos para completar los bloques 7–12. Extrae el ZIP
en la raíz `C:\server\asociacion-jukebox-php` y combina la carpeta `docker`.
Conserva los archivos `docker/.env`, `docker/compose.yaml`, `docker/Dockerfile`
y `docker/apache/jukebox.conf` que ya has creado.

## Arranque y comprobación

Desde PowerShell:

```powershell
cd C:\server\asociacion-jukebox-php\docker
docker compose config --quiet
docker compose up -d --build
docker compose ps
docker compose exec web php -l /var/www/html/11-conexiones.php
docker compose exec web php -l /var/www/html/12-catalogo-json.php
docker compose exec web php -m
docker compose exec web apache2ctl -t
```

Deben aparecer `pdo_pgsql` y `pdo_mysql`. PostgreSQL y MariaDB deben terminar
en estado `healthy`.

## Orden de pruebas

1. `http://localhost:8091/11-conexiones.php`: HTTP 200 y dos estados `ok`.
2. `http://localhost:8091/register.php`: crea una cuenta ficticia.
3. `http://localhost:8091/login.php`: inicia sesión.
4. `http://localhost:8091/home.php`: muestra el alias.
5. `http://localhost:8091/canciones.php`: consulta el catálogo.
6. Prueba añadir, editar y eliminar canciones.
7. `http://localhost:8091/api/canciones.php`: con sesión devuelve una lista JSON;
   sin sesión devuelve HTTP 401.

## Seguridad y datos

- Los JSON están en `docker/data/`, montados en `/var/www/data`, fuera de
  `/var/www/html`.
- El registro almacena `password_hash`, nunca la contraseña original.
- Las operaciones de escritura del catálogo requieren POST y token CSRF.
- No compartas `docker/.env`, cookies, tokens ni datos privados.
- Añade estas líneas a tu `.gitignore` de la raíz sin borrar otras reglas:

```gitignore
docker/.env
docker/data/
```

- No ejecutes `docker compose down -v`: elimina los volúmenes de las bases de datos.

## Integración del reproductor (bloque 11)

La práctica pide reutilizar el reproductor que ya hiciste en la asignatura de cliente.
Este paquete crea `api/canciones.php` y deja un espacio en `home.php`, pero no puede
inventar los IDs, clases, CSS, JavaScript ni los audios concretos de tu reproductor.

Copia los archivos reales a:
- `docker/src/assets/` — CSS e imágenes.
- `docker/src/scripts/` — JavaScript y Howler.
- `docker/src/audio/` — audios autorizados.

En el JavaScript del reproductor cambia la carga de `songsData.json` por una petición
a `./api/canciones.php`. Comprueba `response.ok`, usa `response.json()` y trabaja con
los campos `title`, `artist` y `src`. Mantén una sola colección de canciones. Para
terminar esta integración hace falta adaptar el HTML y JavaScript reales de tu reproductor.
