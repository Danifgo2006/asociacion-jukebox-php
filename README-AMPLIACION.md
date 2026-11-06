# Asociación Jukebox PHP — proyecto integrado

Este ZIP conserva el proyecto original y añade la continuación de los bloques 7–12.

## Arranque

Etapa inicial (puerto 8090), desde la raíz:
```powershell
docker compose up -d
```

Etapa ampliada (puerto 8091), desde `docker`:
```powershell
cd docker
docker compose config --quiet
docker compose up -d --build
docker compose ps
```

## URLs de la etapa ampliada

- `http://localhost:8091/` redirige al login.
- `http://localhost:8091/register.php` crea una cuenta ficticia.
- `http://localhost:8091/login.php` inicia sesión.
- `http://localhost:8091/11-conexiones.php` comprueba PostgreSQL y MariaDB.
- `http://localhost:8091/canciones.php` administra el catálogo tras iniciar sesión.

## Datos y seguridad

Los catálogos y usuarios están en `docker/data/`, montados fuera de la carpeta pública.
No compartas `docker/.env`, cookies, tokens ni datos privados. No ejecutes
`docker compose down -v`, porque elimina los volúmenes persistentes de las bases de datos.

Consulta `INSTRUCCIONES-RESTO.md` para el orden de pruebas y la integración del reproductor.
