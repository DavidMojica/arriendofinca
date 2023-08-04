const sel_pais           = document.getElementById('sel_pais');
const sel_estado         = document.getElementById('sel_estado');
const sel_ciudad         = document.getElementById('sel_ciudad');
const sel_arriendo_venta = document.getElementById('sel_arriendo_venta');
const sel_categoria      = document.getElementById('sel_categoria');
const points             = document.getElementsByClassName('points');

var pais_activo               = 0;
var depto_activo              = 0;
const tiempo_modificar_bordes = 6000;

//--------------------------------------#
//Dynamic points - border Change
//--------------------------------------#
const min    = 5;
const max    = 100;
const medida = "px";
setInterval(function() {
    modifyBorders(points, min, max, medida);
  }, tiempo_modificar_bordes);



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


function checkValues(){
    if (sel_arriendo_venta.value != 1 && sel_arriendo_venta.value != 2){
        createToastNotify(1,"Alterado", "El selector debe de ser arrendar o comprar.");
        return false;
    }

    if(sel_categoria.value < 1 || sel_categoria.value > 8){
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
