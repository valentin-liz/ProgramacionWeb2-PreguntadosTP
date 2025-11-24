// Escuchar clics en cada fila del jugador
document.querySelectorAll('.fila-jugador').forEach(fila => {

    fila.addEventListener('click', () => {

        const nombre = fila.dataset.nombre;
        const puntos = fila.dataset.puntos;
        const partidas = fila.dataset.partidas;
        const nivel = fila.dataset.nivel;

        const foto = fila.dataset.foto
            ? '/' + fila.dataset.foto
            : '/public/imagenes/perfilesImgs/default-profile.png';

        // Mostrar datos en el modal
        document.getElementById('nombreJugador').textContent = nombre;
        document.getElementById('puntosJugador').textContent = puntos;
        document.getElementById('partidasJugador').textContent = partidas;
        document.getElementById('nivelJugador').textContent = nivel;
        document.getElementById('fotoJugador').src = foto;

        // QR del jugador (basado en el nombre)
        const qrURL = `https://quickchart.io/qr?size=200&text=${encodeURIComponent(nombre)}`;
        document.getElementById('qrJugador').src = qrURL;

        // Abrir modal
        const modal = new bootstrap.Modal(document.getElementById('modalJugador'));
        modal.show();
    });
});