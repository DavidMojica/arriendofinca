var id_moviliario_var = 0;
function delete_mov(id_moviliario){
    id_moviliario_var = id_moviliario;
    createToastNotify(3,"Borrar un inmoviliario","Está a punto de borrar un inmoviliario ¿Está seguro?");
}


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
                console.log(response)
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
    AJAX_PAIS_CHANGE(sel_estado,sel_ciudad,pais_activo, '../php/dynamic_cboxes.php');
});

sel_estado.addEventListener('change', function(){
    depto_activo = sel_estado.value;
    AJAX_ESTADO_CHANGE(sel_ciudad, depto_activo, '../php/dynamic_cboxes.php');
});


var swiper = new Swiper(".mySwiper", {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });