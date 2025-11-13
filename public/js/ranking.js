// Escuchar clics en cada fila del jugador
document.querySelectorAll('.fila-jugador').forEach(fila => {
    fila.addEventListener('click', () => {
        const nombre = fila.dataset.nombre;
        const puntos = fila.dataset.puntos;
        const partidas = fila.dataset.partidas;
        const foto = fila.dataset.foto
            ? '/' + fila.dataset.foto
            : '/public/imagenes/perfilesImgs/default-profile.png';

        // Asignar valores al modal
        document.getElementById('nombreJugador').textContent = nombre;
        document.getElementById('puntosJugador').textContent = puntos;
        document.getElementById('partidasJugador').textContent = partidas;
        document.getElementById('fotoJugador').src = foto;

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalJugador'));
        modal.show();
    });
});