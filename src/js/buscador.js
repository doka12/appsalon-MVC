document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
        buscarPorFecha();
}


function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input',function(e) {
        const fechaSeleccionada = e.target.value;           // Leo fecha seleccionada
        window.location = `?fecha=${fechaSeleccionada}`;    // Redireccionamos por query string a fecha seleccionada
    });
}