
/* JUKEBOX - BLOQUE 11 */

// Lista compartida de canciones.
let songs = [];

// Posición de la canción seleccionada.
let i = 0;

// Aquí guardaremos el sonido de Howler.
let sonido = null;

// Elementos de nuestra página HTML.
const estado = document.getElementById('estado-canciones');
const lista = document.getElementById('lista-canciones');
const cancionActual = document.getElementById('cancion-actual');

const botonAnterior = document.getElementById('btn-anterior');
const botonSiguiente = document.getElementById('btn-siguiente');
const botonReproducir = document.getElementById('btn-reproducir');
const botonPausar = document.getElementById('btn-pausar');

// 1. ACTIVAR O DESACTIVAR BOTONES

function actualizarBotones(reproduciendo = false) {
    const hayCanciones = songs.length > 0;

    botonAnterior.disabled = !hayCanciones;
    botonSiguiente.disabled = !hayCanciones;
    botonReproducir.disabled = !hayCanciones || reproduciendo;
    botonPausar.disabled = !reproduciendo;
}

// 2. MOSTRAR LA CANCIÓN SELECCIONADA

function mostrarCancionActual() {
    if (songs.length === 0) {
        cancionActual.textContent = 'No hay canciones';
        return;
    }

    const cancion = songs[i];

    cancionActual.textContent =
        cancion.title + ' — ' + cancion.artist;
}

// 3. CARGAR LAS CANCIONES DESDE PHP

async function cargarCanciones() {
    try {
        const response = await fetch('./api/canciones.php');

        if (!response.ok) {
            throw new Error('No se pudo consultar la API');
        }

        const datos = await response.json();

        // Revisamos que el JSON sea una lista válida.
        if (
            !Array.isArray(datos) ||
            !datos.every(cancion =>
                cancion !== null &&
                typeof cancion === 'object' &&
                typeof cancion.title === 'string' &&
                typeof cancion.artist === 'string' &&
                typeof cancion.src === 'string'
            )
        ) {
            throw new Error('El catálogo tiene datos incorrectos');
        }

        songs = datos;
        i = 0;

        lista.replaceChildren();

        // Si no hay canciones, no podemos reproducir.
        if (songs.length === 0) {
            estado.textContent = 'Todavía no hay canciones.';
            mostrarCancionActual();
            actualizarBotones();
            return;
        }

        estado.textContent =
            'Canciones disponibles: ' + songs.length;

        // Mostrar todas las canciones.
        for (const cancion of songs) {
            const elemento = document.createElement('li');

            elemento.textContent =
                cancion.title + ' — ' + cancion.artist;

            lista.appendChild(elemento);
        }

        mostrarCancionActual();
        actualizarBotones();

    } catch (error) {
        estado.textContent = 'Error: ' + error.message;
        console.error(error);
        actualizarBotones();
    }
}

// 4. REPRODUCIR MÚSICA

function reproducirCancion() {
    if (songs.length === 0) return;

    if (typeof Howl === 'undefined') {
        estado.textContent = 'Error: Howler no se ha cargado.';
        return;
    }

    const cancion = songs[i];

    // Solo creamos un sonido si no existe todavía.
    if (sonido === null) {
        sonido = new Howl({
            src: [cancion.src],
            html5: true,

            onplay: function () {
                estado.textContent =
                    'Reproduciendo: ' + cancion.title;

                actualizarBotones(true);
            },

            onpause: function () {
                estado.textContent = 'Canción en pausa';
                actualizarBotones(false);
            },

            onend: function () {
                estado.textContent = 'Canción terminada';
                actualizarBotones(false);
            },

            onloaderror: function (id, error) {
                estado.textContent =
                    'No se pudo cargar el archivo MP3.';

                console.error('Error de audio:', error);
                actualizarBotones(false);
            },

            onplayerror: function (id, error) {
                estado.textContent =
                    'El navegador no pudo reproducir el audio.';

                console.error('Error de reproducción:', error);
                actualizarBotones(false);
            }
        });
    }

    sonido.play();
}

// 5. PAUSAR MÚSICA

function pausarCancion() {
    if (sonido !== null && sonido.playing()) {
        sonido.pause();
    }
}

// 6. CAMBIAR DE CANCIÓN

function cambiarCancion(paso) {
    if (songs.length === 0) return;

    // ¿Estaba sonando música?
    const estabaSonando =
        sonido !== null && sonido.playing();

    // Detenemos y liberamos la pista anterior.
    if (sonido !== null) {
        sonido.stop();
        sonido.unload();
        sonido = null;
    }

    // Calculamos la nueva posición.
    i = (i + paso + songs.length) % songs.length;

    mostrarCancionActual();
    actualizarBotones(false);

    // Si sonaba música, seguimos con la nueva pista.
    if (estabaSonando) {
        reproducirCancion();
    }
}

// 7. CONECTAR LOS BOTONES

botonReproducir.addEventListener('click', reproducirCancion);

botonPausar.addEventListener('click', pausarCancion);

botonAnterior.addEventListener('click', function () {
    cambiarCancion(-1);
});

botonSiguiente.addEventListener('click', function () {
    cambiarCancion(1);
});

// 8. COMENZAR CARGANDO LAS CANCIONES

cargarCanciones();
