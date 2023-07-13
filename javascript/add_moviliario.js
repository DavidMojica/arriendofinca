// General y Fotos
const add_fotos           = document.getElementById('add_fotos');
const add_tipo            = document.getElementById('add_tipo'); //Tipo de inmueble
const btn_regis_prop      = document.getElementById('btn_regis_prop');
const sel_arriendo_venta  = document.getElementById('sel_arriendo_venta');
const form_prop           = document.getElementsByClassName('form_prop');
const precio              = document.getElementById('precio');
const direccion           = document.getElementById('direccion');
const prop_desc           = document.getElementById('prop_description'); 
const add_pais            = document.getElementById('add_pais');
const add_departamento    = document.getElementById('add_departamento');
const add_ciudad          = document.getElementById('add_ciudad');
const add_area            = document.getElementById('area');
const add_habitaciones    = document.getElementById('add_habitaciones');
const add_banos           = document.getElementById('add_banos');
const add_area_construida = document.getElementById('add_area_construida');
const area_tp2            = document.getElementById('area_tp2');
//pasos
const pasos               = document.getElementsByClassName('add_pasos');
var cont_pasos            = 0;
const atras               = document.getElementById('atras');
const siguiente           = document.getElementById('siguiente');

//----------PASOS------------//
onload = mostrar_pasos(0);

function mostrar_pasos(num){
    for(let paso of pasos)
    paso.style.display = "none";

    cont_pasos += num;  
    if(cont_pasos<=0) atras.style.display = "none";
    else atras.style.display = "block";

    if(cont_pasos >= 3) siguiente.style.display = "none";
    else siguiente.style.display = "block";
    
    if(cont_pasos >= 0 && cont_pasos <= 3){
        pasos[cont_pasos].style.display = "block";
    }
    console.log(pasos[cont_pasos])
}

atras.addEventListener('click', function(){
    if(cont_pasos > 0 && cont_pasos <= 3){
        mostrar_pasos(-1);
    }
});

siguiente.addEventListener('click', function(){
    if(cont_pasos < 3 && cont_pasos >= 0){
        mostrar_pasos(1);
    }
});






// Variables JS
var pais_activo  = 0;
var depto_activo = 0;
//--------------------------------------#
//Dynamic comboboxs - Change
//--------------------------------------#
//--Pais
add_pais.addEventListener('change', function(){
    pais_activo = add_pais.value;
    AJAX_PAIS_CHANGE(add_departamento, add_ciudad, pais_activo,'../php/dynamic_cboxes.php')
});

//--Estado
add_departamento.addEventListener('change', function(){
    depto_activo = add_departamento.value;
    AJAX_ESTADO_CHANGE(add_ciudad, depto_activo, '../php/dynamic_cboxes.php');
});


for(let form of form_prop)
        form.style.display = "none";
        
add_tipo.addEventListener('change', function(){
    for(let form of form_prop)
        form.style.display = "none";

    if(add_tipo.value == 1 || add_tipo.value == 3){
        area_tp2.style.display = "block";
    }else{
        area_tp2.style.display = "none";
    }

    try{
        form_prop[add_tipo.value-1].style.display = "block";
    }
    finally{}
});
//----------------------------------------#
//Click
//--------------------------------------#

btn_regis_prop.addEventListener('click', function(){
    let result = validaciones_general(add_tipo, precio, direccion, prop_desc, add_area, add_banos, add_habitaciones, add_area_construida);

    if(!result.auth){
        createToastNotify(1,"Error en el registro de la propiedad", result.mensaje);
    }
    else{
        createToastNotify(3,"Confirmar creación", "Está a punto de crear un nuevo inmueble. Desea proceder?");
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
    //Nuevos//
    formData.append('area', add_area.value);
    formData.append('banos', add_banos.value);
    formData.append('habitaciones', add_habitaciones.value);
    formData.append('area_construida', add_area_construida.value);

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
