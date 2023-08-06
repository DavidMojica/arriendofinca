// Selección de elementos del DOM
const sel_pais           = document.getElementById('sel_pais');
const sel_estado         = document.getElementById('sel_estado');
const sel_ciudad         = document.getElementById('sel_ciudad');
const sel_arriendo_venta = document.getElementById('sel_arriendo_venta');
const sel_categoria      = document.getElementById('sel_categoria');
const points             = document.getElementsByClassName('points');

// Variables globales
var pais_activo               = 0;
var depto_activo              = 0;
const tiempo_modificar_bordes = 6000; // Intervalo para cambiar los bordes de los elementos

//--------------------------------------#
//Dynamic points - Cambiar bordes dinámicamente
//--------------------------------------#
const min    = 5;
const max    = 100;
const medida = "px";

// Intervalo para modificar los bordes de los elementos "points"
setInterval(function() {
    modifyBorders(points, min, max, medida);
  }, tiempo_modificar_bordes);



//--------------------------------------#
//Dynamic comboboxs - Cambiar contenido dinámicamente
//--------------------------------------#

// Evento change para el selector de país (sel_pais)
sel_pais.addEventListener('change', function(){
    pais_activo = sel_pais.value;
    AJAX_PAIS_CHANGE(sel_estado,sel_ciudad,pais_activo, 'php/dynamic_cboxes.php');
});

// Evento change para el selector de estado (sel_estado)
sel_estado.addEventListener('change', function(){
    depto_activo = sel_estado.value;
    AJAX_ESTADO_CHANGE(sel_ciudad, depto_activo, 'php/dynamic_cboxes.php');
});

// Función para validar los valores seleccionados en el formulario
function checkValues(){
    if (sel_arriendo_venta.value != 1 && sel_arriendo_venta.value != 2){
        createToastNotify(1,"Alterado", "El selector debe de ser arrendar o comprar.");
        return false;
    }

    if(sel_categoria.value < 1 || sel_categoria.value > 11){
        createToastNotify(1,"Alterado","El selector de categoria debe de contener las categorias preestablecidas");
        return false;
    }

    if(isNaN(sel_pais.value)){
        createToastNotify(1, "Seleccione Ubicacion", "Debe de seleccionar un país, un estado y una ciudad");
        return false;
    }

    if(isNaN(sel_estado.value)){
        createToastNotify(1, "Seleccione Ubicacion", "Debe seleccionar un estado y una ciudad.");
        return false;
    }

    if(isNaN(sel_ciudad.value)){
        createToastNotify(1, "Seleccione Ubicacion", "Debe de seleccionar una ciudad.");
        return false;
    }

    return true;
}