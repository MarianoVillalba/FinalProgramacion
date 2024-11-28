document.addEventListener("DOMContentLoaded", function() {

    // Función para mostrar un mensaje de confirmación al agregar, eliminar o modificar un paquete
    function mostrarMensaje(message) {
        const mensajeElemento = document.createElement('div');
        mensajeElemento.classList.add('mensaje');
        mensajeElemento.textContent = message;
        document.body.appendChild(mensajeElemento);

        setTimeout(function() {
            mensajeElemento.remove();
        }, 3000); // El mensaje se elimina después de 3 segundos
    }

    // Evento de confirmación en el formulario de "Eliminar Paquete"
    const eliminarFormulario = document.querySelector("form[action='index.php'][method='POST']");
    if (eliminarFormulario) {
        eliminarFormulario.addEventListener("submit", function(event) {
            const confirmacion = confirm("¿Estás seguro de que deseas eliminar este paquete?");
            if (!confirmacion) {
                event.preventDefault(); // Previene el envío del formulario si el usuario cancela
            }
        });
    }

    // Agregar un evento para el formulario de "Agregar Paquete"
    const agregarFormulario = document.querySelector("form[action='index.php'][method='POST']");
    if (agregarFormulario) {
        agregarFormulario.addEventListener("submit", function(event) {
            const viaje = document.getElementById("viaje").value;
            const hotel = document.getElementById("hotel").value;
            const comida = document.getElementById("comida").value;
            const transporte = document.getElementById("transporte").value;

            if (!viaje || !hotel || !comida || !transporte) {
                event.preventDefault();
                mostrarMensaje("Por favor, selecciona todos los campos antes de agregar el paquete.");
            }
        });
    }

    // Agregar un evento para el formulario de "Modificar Paquete"
    const modificarFormulario = document.querySelector("form[action='index.php'][method='POST']");
    if (modificarFormulario) {
        modificarFormulario.addEventListener("submit", function(event) {
            const nuevaComida = document.getElementById("nueva_comida").value;

            if (!nuevaComida) {
                event.preventDefault();
                mostrarMensaje("Por favor, ingresa un nuevo tipo de comida.");
            }
        });
    }

});
