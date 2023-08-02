// DOM
const edit_save            = document.getElementById('edit_save');
const edit_tipo_inmueble   = document.getElementById('edit_tipo_inmueble');
const edit_arriendo_venta  = document.getElementById('edit_arriendo_venta');
const edit_precio          = document.getElementById('edit_precio');
const edit_pais             = document.getElementById('edit_pais');
const edit_estado          = document.getElementById('edit_estado');
const edit_municipio       = document.getElementById('edit_municipio');
const edit_direccion       = document.getElementById('edit_direccion');
const edit_descripcion     = document.getElementById('edit_descripcion');
const btn_edit_municipio   = document.getElementById('btn_edit_municipio');
const div_edit_location    = document.getElementById('div_edit_location');
const confirm_new_location = document.getElementById('confirm_new_location');
const id_inmoviliario      = document.getElementById('id_inmoviliario');
const edit_area            = document.getElementById('edit_area');
const edit_habitaciones    = document.getElementById('edit_habitaciones');
const edit_area_construida = document.getElementById('edit_area_construida');
const edit_banos           = document.getElementById('edit_banos');

//--------------------------------------#
//Dynamic combobox - change
//--------------------------------------#
edit_pais.addEventListener('change', function(){
    pais_activo = edit_pai.value;
    AJAX_PAIS_CHANGE(edit_estado, edit_municipio, pais_activo, '../php/dynamic_cboxes.php');
});

edit_estado.addEventListener('change', function(){
    estado_activo = edit_estado.value;
    AJAX_ESTADO_CHANGE(edit_municipio, estado_activo, '../php/dynamic_cboxes.php');
});



//--------------------------------------#
//Click event
//--------------------------------------#

btn_edit_municipio.addEventListener('click', function(){
    if(div_edit_location.style.display == 'block'){
        edit_pais.disabled = true;
        div_edit_location.style.display = 'none';
    }
    else if(div_edit_location.style.display == 'none'){
        div_edit_location.style.display = 'block';
        edit_pais.disabled = false;
    }
    else{
        edit_pais.disabled = true;
        div_edit_location.style.display = 'none';
    }
});


var swiper = new Swiper(".mySwiper", {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
});


function borrar_imagen(id_imagen){
    $.ajax({
        url: '../php/delete_mov.php',
        type: 'POST',
        data:{
            id_imagen : id_imagen
        },
        success: function(response){
            console.log(response);
            if(response.success){
                createToastNotify(0, "Exito", "Foto borrada correctamente");
            }
            else{
                createToastNotify(1, "Error", response.mensaje)
            }
        },
        error: function(jqXHR, errorLog, NTXH){
            console.log(jqXHR)
            console.log(errorLog)
            console.log(NTXH)
        }
    })
}

edit_save.addEventListener('click', function(){
    let result = validaciones_general(edit_tipo_inmueble, edit_precio, edit_direccion, edit_descripcion,edit_area, edit_banos, edit_habitaciones, edit_area_construida);
    let id_edit_municipio = 0;
    console.log(id_inmoviliario.innerHTML);

    // Verifica que el usuario confirme el cambio de la ubicacion del inmueble.
    if(confirm_new_location.checked){
        id_edit_municipio = edit_municipio.value;
    }
        if(result.auth){
        $.ajax({
            url: '../php/save.php',
            type: 'POST',
            data: {
                edit_tipo_inmueble : edit_tipo_inmueble.value,
                edit_arriendo_venta: edit_arriendo_venta.value,
                edit_precio        : edit_precio.value,
                edit_direccion     : edit_direccion.value,
                id_edit_municipio  : id_edit_municipio,
                edit_descripcion   : edit_descripcion.value,
                id_inmoviliario    : id_inmoviliario.innerHTML,
                edit_area          : edit_area.value,
                edit_habitaciones  : edit_habitaciones.value,
                edit_area_construida : edit_area_construida.value,
                edit_banos         : edit_banos.value
            },
            success: function(response){
                if(response.success){
                    window.location.href = "../php/userarea.php";
                }
                else{
                    createToastNotify(1,"Error al ejecutar la actualizacion", response.mensaje);
                }
            },
            error: function(jqXHR, errorLog, NTXH){
                console.log(jqXHR)
                console.log(errorLog)
                console.log(NTXH)
            }
        });
    }
    else{
        createToastNotify(1,"Error en la edicion de la propiedad", result.mensaje);
    }
});
