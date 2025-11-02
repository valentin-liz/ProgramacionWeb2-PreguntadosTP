let ruleta;
function crearRuleta() {
    const canvas = document.getElementById('ruleta');
    const tamano = canvas.offsetWidth / 2 - 10;

    ruleta = new Winwheel({
        'canvasId': 'ruleta',
        'numSegments': 4,
        'outerRadius': tamano,
        'segments': [
            { 'fillStyle': '#31C950', 'text': 'Ciencia', 'textFillStyle': '#1C1917', 'textFontSize': 22 },
            { 'fillStyle': '#FF692A', 'text': 'Deportes', 'textFillStyle': '#1C1917', 'textFontSize': 22 },
            { 'fillStyle': '#FDC745', 'text': 'Historia', 'textFillStyle': '#1C1917', 'textFontSize': 22 },
            { 'fillStyle': '#51A2FF', 'text': 'Geografía', 'textFillStyle': '#1C1917', 'textFontSize': 22 }
        ],
        'animation': {
            'type': 'spinToStop',
            'duration': 5,
            'spins': 8,
            'callbackFinished': alertResultado
        }
    });
}

// función callback
function alertResultado(segmento) {
    const categoria = segmento.text;
    const categoriaElemento = document.getElementById("categoriaSeleccionada");
    categoriaElemento.textContent = categoria;

    const modal = new bootstrap.Modal(document.getElementById("categoriaModal"));
    console.log("Categoría: " + categoria);
    modal.show();

}

// botón girar
document.getElementById('girar').addEventListener('click', () => {
    ruleta.startAnimation();
});

// crear ruleta al cargar
window.addEventListener('load', crearRuleta);

// volver a crear ruleta si cambia el tamaño
window.addEventListener('resize', () => {
    crearRuleta();
});