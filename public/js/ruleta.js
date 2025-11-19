let ruleta;
function crearRuleta() {
    const canvas = document.getElementById('ruleta');
    if (!canvas) {
        console.error("No se encontró el canvas con id 'ruleta'");
        return;
    }

    // Esperar un tick para que el DOM calcule el tamaño real del canvas
    setTimeout(() => {
        const tamano = canvas.offsetWidth > 0 ? (canvas.offsetWidth / 2 - 10) : 150;

        if (!window.categorias || window.categorias.length === 0) {
            console.error("No hay categorías disponibles para crear la ruleta");
            return;
        }

        const segmentos = window.categorias.map(cat => ({
            fillStyle: cat.color,
            text: cat.nombre,
            textFillStyle: '#1C1917',
            textFontSize: 22
        }));

        ruleta = new Winwheel({
            canvasId: 'ruleta',
            numSegments: segmentos.length,
            outerRadius: tamano,
            segments: segmentos,
            animation: {
                type: 'spinToStop',
                duration: 5,
                spins: 8,
                callbackFinished: alertResultado
            }
        });
    }, 100);
}

// función callback
function alertResultado(segmento) {
    const categoria = segmento.text;
    const categoriaElemento = document.getElementById("categoriaSeleccionada");
    categoriaElemento.textContent = categoria;

    const modal = new bootstrap.Modal(document.getElementById("categoriaModal"));
    console.log("Categoría seleccionada:", categoria);
    modal.show();

    document.getElementById("continuarBtn").onclick = function () {
        window.location.href = "/partida/jugarPartida?categoria=" + encodeURIComponent(categoria);
    };
}


// botón girar
document.getElementById('girar').addEventListener('click', () => {
    if (ruleta) ruleta.startAnimation();
});

// crear ruleta al cargar
window.addEventListener('load', crearRuleta);

// volver a crear ruleta si cambia el tamaño
window.addEventListener('resize', () => {
    crearRuleta();
});