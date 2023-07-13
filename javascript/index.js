const sel_pais = document.getElementById('sel_pais');
const sel_estado = document.getElementById('sel_estado');
const sel_ciudad = document.getElementById('sel_ciudad');


var pais_activo = 0;
var depto_activo = 0;

//--------------------------------------#
//Dynamic comboboxs - Change
//--------------------------------------#
//--Pais

sel_pais.addEventListener('change', function(){
    pais_activo = sel_pais.value;
    AJAX_PAIS_CHANGE(sel_estado,sel_ciudad,pais_activo, 'php/dynamic_cboxes.php');
});

sel_estado.addEventListener('change', function(){
    depto_activo = sel_estado.value;
    AJAX_ESTADO_CHANGE(sel_ciudad, depto_activo, 'php/dynamic_cboxes.php');
});

function onMenuClick() {
    var navbar = document.getElementById("navigation-bar");
    var responsive_class_name = "responsive";

    navbar.classList.toggle(responsive_class_name);
}