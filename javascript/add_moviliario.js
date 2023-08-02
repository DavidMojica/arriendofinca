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
const btn_register        = document.getElementById('btn_regis_prop');

//pasos
const pasos               = document.getElementsByClassName('paso');
var cont_pasos            = 0;
const atras               = document.getElementById('atras');
const siguiente           = document.getElementById('siguiente');
//misc
let colorAprobado = "#90EE90";
let colorDesaprobado = "#FF6961";
let initialColor    = "powderblue";
var regex = /^[0-9]+$/; //Expresión regular para validar que la cadena solo contenga números - documento y numero de celular
let expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; //expresion para verificar que el email tenga la estructura: xxxxxxxx@xxxx.xxx


//----------PASOS------------//
onload = mostrar_pasos(0);

function mostrar_pasos(num){

    for(let paso of pasos)
    paso.style.display = "none";

    cont_pasos += num;  
    if(cont_pasos<=0) atras.style.display = "none";
    else atras.style.display = "inline-block";

    if(cont_pasos >= 3) siguiente.style.display = "none";
    else siguiente.style.display = "inline-block";
    
    if(cont_pasos >= 0 && cont_pasos <= 3){
        pasos[cont_pasos].style.display = "inline-block";

    if(cont_pasos==3) btn_register.style.display = "inline-block";
    else btn_register.style.display              = "none";
    }
    console.log(pasos[cont_pasos])
}

siguiente.addEventListener('click', function(){
    if(cont_pasos ==0)
        validar_pasos(cont_pasos);
    else if(cont_pasos == 1)
        validar_pasos(cont_pasos);
    else if(cont_pasos == 2)
        validar_pasos(cont_pasos);
    else if(cont_pasos == 3)
        validar_pasos(cont_pasos);
});


atras.addEventListener('click', function(){
    if(cont_pasos > 0 && cont_pasos <= 3){
        mostrar_pasos(-1);
    }
});

function validar_pasos(paso){
    //Mensaje que usaremos en caso de que algún campo sea inválido.
    let msg = "";
    let ban = true;

    let func_list = [validar_paso_1, validar_paso_2, validar_paso_3];
    let result = func_list[paso](ban,msg);

    ban = result.ban;
    msg = result.msg;
    if(ban && (cont_pasos < 3 && cont_pasos >= 0))
        mostrar_pasos(1);
    else if(!ban && (cont_pasos < 3 && cont_pasos >= 0)){
        createToastNotify(1,`No puede avanzar al paso ${paso+2}.`,msg);
    }
    else createToastNotify(1, "Error al momento de registrarse", msg + "error");
}

btn_register.addEventListener('click', function(){
    if(cont_pasos === 3){
        createToastNotify(3,"Confirmar creación", "Está a punto de crear un nuevo inmueble. Desea proceder?");
    }
});

/*-------------------------------------------------------------------
#Functions
--------------------------------------------------------------------*/
function validar_paso_1(ban, msg){

    //Para
    if(sel_arriendo_venta.value == 1 || sel_arriendo_venta.value == 2){
        sel_arriendo_venta.style.backgroundColor = colorAprobado;
    }
    else{
        msg += "El selector arriendo o venta no contiene valores validos <br>";
        ban = false;
        sel_arriendo_venta.style.backgroundColor = colorDesaprobado;
    }

    //tipo
    if(!regex.test(add_tipo.value.trim()) || (add_tipo.value.trim()<=0 || add_tipo.value.trim()>=9)){
        msg += "El selector tipo propiedad no contiene valores validos <br>";
        ban = false;
        add_tipo.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_tipo.style.backgroundColor = colorAprobado;
    }
    
    //precio
    if(!regex.test(precio.value.trim())){
        msg += "El precio contiene caracteres no numéricos. <br>";
        ban = false;
        precio.style.backgroundColor = colorDesaprobado;
    }
    else{
        precio.style.backgroundColor = colorAprobado;
    }
    
    //direccion
    if(direccion.value.trim()==""){
        msg += "La dirección está vacía. <br>";
        ban = false;
        direccion.style.backgroundColor = colorDesaprobado;
    }
    else{
        direccion.style.backgroundColor = colorAprobado;
    }
    setTimeout(function() {
        naturalizeColors([sel_arriendo_venta, add_tipo, precio, direccion]);
      }, 6000);
    return { ban : ban,
             msg: msg };
}

function naturalizeColors(objects){
    for(item of objects) item.style.backgroundColor = initialColor;
}

function validar_paso_2(ban, msg){
    //pais
    if(!regex.test(add_pais.value)){
        msg += "Selección de país inválida. <br>";
        ban = false;
        add_pais.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_pais.style.backgroundColor = colorAprobado;
    }

    //estado
    if(!regex.test(add_departamento.value)){
        msg += "Seleccion de estado/departamento inválida. <br>";
        ban = false;
        add_departamento.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_departamento.style.backgroundColor = colorAprobado;
    }

    //ciudad
    if(!regex.test(add_ciudad.value)){
        msg += "Seleccion de ciudad inválida. <br>";
        ban = false;
        add_ciudad.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_ciudad.style.backgroundColor = colorAprobado;
    }

    setTimeout(function() {
        naturalizeColors([add_pais, add_departamento, add_ciudad]);
      }, 6000);

    return { ban : ban,
             msg: msg };
}

function validar_paso_3(ban, msg){

    //area del inmueble
    if(add_area.value == ""){
        ban = false;
        msg += "Area del inmueble está vacía. <br>";
        add_area.style.backgroundColor = colorDesaprobado;
    }
    else if(!regex.test(add_area.value)){
        ban = false;
        msg += "Carácter no numérico en el área";
        add_area.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_area.style.backgroundColor = colorAprobado;
    }

    //Numero de habitaciones
    if(add_habitaciones.value.trim() == ""){
        ban = false;
        msg += "Campo habitaciones está vacíoadd_habitaciones. <br>";
        add_habitaciones.style.backgroundColor = colorDesaprobado;
    }
    else if(!regex.test(add_habitaciones.value.trim())){
        ban = false;
        msg += "Caracter no numérico en campo habitaciones.";
        add_habitaciones.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_habitaciones.style.backgroundColor = colorAprobado;
    }

    //Numero de baños

    if(add_banos.value.trim() == ""){
        ban = false;
        msg += "Campo habitaciones está vacíoadd_banos. <br>";
        add_banos.style.backgroundColor = colorDesaprobado;
    }
    else if(!regex.test(add_banos.value.trim())){
        ban = false;
        msg += "Caracter no numérico en campo habitaciones.";
        add_banos.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_banos.style.backgroundColor = colorAprobado;
    }

    //area construida
    if(add_area_construida.value.trim() == ""){
        ban = false;
        msg += "Campo habitaciones está vacíoadd_habitaciones. <br>";
        add_area_construida.style.backgroundColor = colorDesaprobado;
    }
    else if(!regex.test(add_area_construida.value.trim())){
        ban = false;
        msg += "Caracter no numérico en campo habitaciones.";
        add_area_construida.style.backgroundColor = colorDesaprobado;
    }
    else{
        add_area_construida.style.backgroundColor = colorAprobado;
    }

    //descripcion

    if(prop_desc.value.trim == ""){
        ban = false;
        msg += "La descripción está vacía";
        prop_desc.style.backgroundColor = colorDesaprobado;
    }
    else{
        prop_desc.style.backgroundColor = colorAprobado;
    }
    setTimeout(function() {
        naturalizeColors([add_area, add_habitaciones, add_banos, add_area_construida, prop_desc]);
      }, 6000);

    return { ban : ban,
             msg: msg };
}








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
