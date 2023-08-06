// Variables globales
var id_moviliario_var = 0;
var pais_activo = 0;
var depto_activo = 0;

// Obtener elementos con clase 'inmoviliario' y aplicar la función 'set_bgcolors'
var inmoviliarios = document.getElementsByClassName('inmoviliario');
for(let inmoviliario of inmoviliarios){
    set_bgcolors(inmoviliario);
}

// Ejecutar el código una vez que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los elementos con la clase 'btn_expandir'
    var btn_expandir = document.querySelectorAll('.btn_expandir');

    // Asignar eventos de clic a cada botón 'btn_expandir'
    btn_expandir.forEach(function(btn){
        btn.addEventListener('click', function(){
            var itemContenedor = this.parentElement;
            var detalles = itemContenedor.querySelector(".info_hidden");
            detalles.classList.toggle("mostrar");
            this.textContent = detalles.classList.contains("mostrar") ? "Ocultar detalles" : "Mostrar detalles";
        })
    });
});

// Función para eliminar un inmoviliario
function delete_mov(id_moviliario){
    id_moviliario_var = id_moviliario;
    createToastNotify(3,"Borrar un inmoviliario","Está a punto de borrar un inmoviliario ¿Está seguro?");
}

// Función para confirmar el mensaje de eliminación
function mensaje_confimacion(){
    $.ajax({
        url : "../php/opts_mov.php",
        type: "POST",
        data: {
            id_moviliario: id_moviliario_var,
            type         : 0
        },
        success: function(response){
            console.log(response)
            if(!response.success){
                createToastNotify(1,"Error",response.mensaje);
            }
            else{
                window.location.reload();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Manejar errores aquí
        }
    });
}

// Obtener elementos del DOM para los combobox
const sel_pais = document.getElementById('sel_pais');
const sel_estado = document.getElementById('sel_estado');
const sel_ciudad = document.getElementById('sel_ciudad');

// Manejadores de eventos para los combobox dinámicos
sel_pais.addEventListener('change', function(){
    pais_activo = sel_pais.value;
    AJAX_PAIS_CHANGE(sel_estado,sel_ciudad,pais_activo, '../php/dynamic_cboxes.php');
});

sel_estado.addEventListener('change', function(){
    depto_activo = sel_estado.value;
    AJAX_ESTADO_CHANGE(sel_ciudad, depto_activo, '../php/dynamic_cboxes.php');
});

// Configuración de la biblioteca Swiper para el slider
var swiper = new Swiper(".mySwiper", {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
});
