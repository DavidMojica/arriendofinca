// General y Fotos
const add_fotos          = document.getElementById('add_fotos');
const add_tipo           = document.getElementById('add_tipo'); //Tipo de inmueble
const btn_regis_prop     = document.getElementById('btn_regis_prop');
const sel_arriendo_venta = document.getElementById('sel_arriendo_venta');
const form_prop          = document.getElementsByClassName('form_prop');
const precio             = document.getElementById('precio');
const direccion          = document.getElementById('direccion');
const prop_desc          = document.getElementById('prop_description'); 
const add_pais           = document.getElementById('add_pais');
const add_departamento   = document.getElementById('add_departamento');
const add_ciudad         = document.getElementById('add_ciudad');

// Variables JS
var pais_activo  = 0;
var depto_activo = 0;
//--------------------------------------#
//Dynamic comboboxs - Change
//--------------------------------------#
//--Pais
add_pais.addEventListener('change', function(){
    pais_activo = add_pais.value;
    AJAX_PAIS_CHANGE(add_departamento, add_ciudad, pais_activo)
});

//--Estado
add_departamento.addEventListener('change', function(){
    depto_activo = add_departamento.value;
    AJAX_ESTADO_CHANGE(add_ciudad, depto_activo);
});

add_tipo.addEventListener('change', function(){
    for(let form of form_prop)
        form.style.display = "none";

    try{
        form_prop[add_tipo.value-1].style.display = "block";
    }
    finally{}
});
//----------------------------------------#
//Click
//--------------------------------------#

btn_regis_prop.addEventListener('click', function(){
    let result = validaciones_general(add_tipo, precio, direccion, prop_desc);
    if(!result.auth){
        createToastNotify(1,"Error en el registro de la propiedad", result.mensaje);
    }
    else{
        let ban_prop = true;
        switch(add_tipo.value){
            case 0: ban_prop = validaciones_finca();
        }
        if(ban_prop){
            createToastNotify(3,"Confirmar creación", "Está a punto de crear un nuevo inmueble. Desea proceder?");
        }
    }
});

//--------------------------------------#
// DOM -- Verificar que ningún archivo pese más de 5mb.
//--------------------------------------#
add_fotos.addEventListener('change', function() {
    var files = add_fotos.files;

    var maxSize = 5 * 1024 * 1024; // 5 MB en bytes
    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        if (file.size > maxSize) {
            alert(`El archivo ${file.name} excede el límite de tamaño (5Mb).`);
            add_fotos.value = '';
            return;
        }
    }
});

//--------------------------------------#
//Funcion al mensaje de confirmacion
//--------------------------------------#
function mensaje_confimacion() {
    console.log("Pulsó en confirmar. Creando moviliario...");
    
    var formData = new FormData();
    formData.append('id_tipo_inmueble', add_tipo.value);
    formData.append('arriendo_o_venta', sel_arriendo_venta.value);
    formData.append('precio', precio.value);
    formData.append('id_municipio', add_ciudad.value);
    formData.append('direccion', direccion.value);
    formData.append('descripcion', prop_desc.value);
    formData.append('pais', add_pais.value);
    formData.append('estado', add_departamento.value);
    formData.append('ciudad',add_ciudad);

    var files = add_fotos.files;
    for (var i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }

    $.ajax({
        url: "../php/uploadMov.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response)
            if (response.success) {
                window.location.href = "../php/userarea.php";
            } else {
                if (response.state == 0) {
                    let error_text = response.mensaje.join(" ");
                    createToastNotify(1, "Error desde el servidor", error_text);
                    
                } else if (response.state == 1) {
                    let msg_warning = response.mensaje.join(" ");
                    createToastNotify(3, "Cuidado", msg_warning);
                } else if (response.state == 2) {
                    createToastNotify(1, "Error desde el servidor", response.mensaje);
                } else {
                    createToastNotify(1, "Error desde el cliente", "Error desconocido");
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Error en la solicitud AJAX
            console.log('Error en la solicitud');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

//--------------------------------------#
//Validaciones a cada tipo de propiedad
//--------------------------------------#
//Finca
function validaciones_finca(){
    const tipo_finca     = document.getElementById('tipo_finca');

    let ban = true;
    let msg = "";

    if(tipo_finca.value != 1 && tipo_finca.value != 2){
        ban = false;
        msg += "El tipo de finca no corresponde a recreo o a produciión";
    }

    if(!ban){
        createToastNotify(1,"Error en la informacion de la finca", msg);
        return false;
    }
    else return true;
}