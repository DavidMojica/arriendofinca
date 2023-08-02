/*-------------------------------------------------------------------
#Global variables
--------------------------------------------------------------------*/
const btn_register        = document.getElementById("reg_btn_registrarme");
const reg_nombre          = document.getElementById("reg_nombre");
const reg_documento       = document.getElementById("reg_documento");
const reg_tipo_documento  = document.getElementById("reg_tipo_documento");
const reg_fecha           = document.getElementById("reg_fecha");
const reg_email           = document.getElementById("reg_email");
const reg_contraseña      = document.getElementById("reg_contraseña");
const reg_conf_contraseña = document.getElementById("reg_conf_contraseña");
const reg_indicativo      = document.getElementById("reg_indicativo");
const reg_celular         = document.getElementById("reg_celular");
const check_whatsapp      = document.getElementById("check_whatsapp");
const reg_pais            = document.getElementById("reg_pais");
const reg_estado_departamento = document.getElementById("reg_estado_departamento");
const reg_ciudad          = document.getElementById("reg_ciudad");
var paises_value   = [];
var deptos_value   = [];
var ciudades_value = [];
var pais_activo          = null;
//pasos
const pasos              = document.getElementsByClassName('paso');
var cont_pasos           = 0;
const atras              = document.getElementById('atras');
const siguiente          = document.getElementById('siguiente');
//misc
let colorAprobado = "#90EE90";
let colorDesaprobado = "#FF6961";

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

atras.addEventListener('click', function(){
    if(cont_pasos > 0 && cont_pasos <= 3){
        mostrar_pasos(-1);
    }
});



function changeCard() {
    var card1 = document.getElementById("div_login");
    var card2 = document.getElementById("div_registro");
    
    div_login.style.animation = "flipCard 1s";
    div_registro.style.animation = "flipCard 1s";
    
    setTimeout(function() {
      div_login.style.animation = "";
      div_registro.style.animation = "";
      
      div_login.style.display = "none";
      div_registro.style.display = "block";
    }, 1000);
  }


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
        let ban = true;
        let msg = "";
        //pais
        if(reg_pais.value.trim() == "default" || reg_estado_departamento.value.trim() == "default" || reg_ciudad.value.trim() == "default"){
            msg += "Indique su lugar de residencia. <br>";
            ban = false;
            reg_pais.style.backgroundColor = colorDesaprobado;
        }
        //Verificar que el valor del país proveniente de html exista en nuestro archivo json.
        else if(!paises_value.includes(reg_pais.value.trim())){
            ban = false;
            msg += "Error en el valor del país. <br>";
            reg_pais.style.backgroundColor = colorDesaprobado;
        }
        else if(!deptos_value.includes(reg_estado_departamento.value.trim())){
            //Verificar que el valor del departamento proveniente de html exista en nuestro archivo json.
            ban = false;
            msg += "Error en el valor del departamento <br>";
            reg_estado_departamento.style.backgroundColor = colorDesaprobado;
        }
        else if (!ciudades_value.includes(reg_ciudad.value.trim())){
            //Verificar que el valor de proveniente de html exista en nuestro archivo json.
            ban = false;
            msg += "Error en el valor de la ciudad <br>";
            reg_ciudad.style.backgroundColor = colorDesaprobado;
        }
        else{
            reg_pais.style.backgroundColor = colorAprobado;
            reg_estado_departamento.style.backgroundColor = colorAprobado;
            reg_ciudad.style.backgroundColor = colorAprobado;
        }
        
        setTimeout(function() {
            naturalizeColors([reg_pais, reg_estado_departamento, reg_ciudad]);
        }, 6000);
        
        if(ban)
            mandar_servidor();
        else{
            createToastNotify(1, "Error al momento de registrarse", msg + "error");
        }
    }
});

function clear_register_items(){
    reg_nombre.value          = "";
    reg_tipo_documento.value  = "1";
    reg_documento.value       = "";
    reg_fecha.value           = "dd/mm/aaaa";
    reg_contraseña.value      = "";
    reg_conf_contraseña.value = "";
    reg_email.value           = "";
    reg_indicativo.value      = "default";
    reg_celular.value         = "Número celular";
    check_whatsapp.checked    = false;
    reg_pais.value            = "default";
    reg_estado_departamento.value = "default";
    reg_estado_departamento.disabled = true;
    reg_ciudad.value          = "default";
    reg_ciudad.disabled       = true;
    cont_pasos                = 0;
}




/*-------------------------------------------------------------------
#De archivos JSON a listas y DOM.
--------------------------------------------------------------------*/
// Paises
fetch('../json/paises.json').then(Response => Response.json()).then(data=>{
    data.forEach(pais => {
        // Crear las opciones de los países
        const indicativo = pais.indicativo;
        const nombre    = pais.nombre;

        // Creamos una nueva opcion para indicativo.
        const nueva_opcion = document.createElement('option');
        nueva_opcion.value = `${nombre}`;
        nueva_opcion.textContent = `${nombre} (+${indicativo})`;
        reg_indicativo.appendChild(nueva_opcion);
        // Creamos otra opcion para el pais.
        const  nueva_opcion2 = document.createElement('option');
        nueva_opcion2.value = `${nombre}`;
        nueva_opcion2.textContent = `${nombre}`;
        reg_pais.appendChild(nueva_opcion2);

        paises_value.push(nombre);
      });
}).catch(error =>{
    console.log(error)
});

/*-------------------------------------------------------------------
#DOM.
--------------------------------------------------------------------*/

//Combobox cambian
reg_pais.addEventListener('change', function() {
    pais_activo = reg_pais.value;
  
    if(pais_activo != "default"){
        // Realizar la solicitud fetch al archivo JSON correspondiente al país seleccionado
        fetch(`../json/${pais_activo}.json`)
        .then(response => response.json())
        .then(data => {
            // Limpiar el ComboBox "departamento" antes de cargar los nuevos valores
            reg_estado_departamento.innerHTML = "";
            var option = document.createElement("option");
            option.value = "default";
            option.text = "Seleccione su departamento...";
            reg_estado_departamento.appendChild(option);
            // Recorrer los departamentos y agregarlos al ComboBox "departamento"
            data.forEach(departamento => {
            option = document.createElement("option");
            option.value = departamento.departamento;
            option.text = departamento.departamento;
            reg_estado_departamento.appendChild(option);
            deptos_value.push(departamento.departamento);
            });
        })
        .catch(error => {
            console.log("Error al cargar los departamentos: " + error);
        });
        reg_estado_departamento.disabled = false;
    }
    else{
        reg_estado_departamento.disabled = true;
        reg_ciudad.disabled = true;
    }
});

  // Evento de cambio del ComboBox "departamento"
reg_estado_departamento.addEventListener('change', function() {
    var departamento_activo = reg_estado_departamento.value;
    var pais_activo = reg_pais.value;
    if(departamento_activo != "default"){
        // Realizar la solicitud fetch al archivo JSON correspondiente al departamento seleccionado
        fetch(`../json/${pais_activo}.json`)
        .then(response => response.json())
        .then(data => {
        // Limpiar el ComboBox "ciudades" antes de cargar las nuevas ciudades
        reg_ciudad.innerHTML = "";

        var option = document.createElement("option");
        option.value = "default";
        option.text = "Seleccione su ciudad...";
        reg_estado_departamento.appendChild(option);
        // Recorrer las ciudades y agregarlas al ComboBox "ciudades"
        data.forEach(departamento => {
            if(departamento.departamento == departamento_activo){      
                for(let ciudad of departamento.ciudades){
                    option = document.createElement("option");
                    option.value = ciudad;
                    option.text = ciudad;
                    reg_ciudad.appendChild(option);
                    ciudades_value.push(ciudad);
                }    
                reg_ciudad.disabled = false;                  
            }

        });
        })
        .catch(error => {
        console.log("Error al cargar las ciudades: " + error);
        });
    }
    else{
        reg_ciudad.disabled = true;
    }
});

check_whatsapp.addEventListener('change', function(){
    if(check_whatsapp.checked){
        reg_indicativo.disabled = false;
        reg_celular.disabled    = false;
    }
    else if (!check_whatsapp.checked){
        reg_indicativo.disabled = true;
        reg_celular.disabled    = true;
    }
});


/*-------------------------------------------------------------------
#Functions
--------------------------------------------------------------------*/
function validar_paso_1(ban, msg){
    var regex = /^[0-9]+$/; //Expresión regular para validar que la cadena solo contenga números - documento y numero de celular

    //Nombre
    if(reg_nombre.value.trim() === ""){
        msg += "El campo nombre está vacío. <br>";
        ban = false;
        reg_nombre.style.backgroundColor = colorDesaprobado;
    }
    else if(reg_nombre.value.trim().length < 10){
        msg += "Nombre demasiado corto. <br>";
        ban = false;
        reg_nombre.style.backgroundColor = colorDesaprobado;
    }
    else reg_nombre.style.backgroundColor = colorAprobado;

    //Documento
    if(reg_documento.value.trim() === ""){
        msg += "El campo cédula está vacío. <br>";
        ban = false;
        reg_documento.style.backgroundColor= colorDesaprobado;
    }
    else if(reg_documento.value.trim().length < 5){
        msg += "Documento demasiado corto. <br>";
        ban = false;
        reg_documento.style.backgroundColor = colorDesaprobado;
    }
    else if(!regex.test(reg_documento.value.trim())){
        msg += "Documento inválido. Solo debe de contener numeros. <br>";
        ban = false;
        reg_documento.style.backgroundColor = colorDesaprobado;
    }
    else reg_documento.style.backgroundColor = colorAprobado;

    //Tipo_documento
    if(reg_tipo_documento.value.trim() != 1 && reg_tipo_documento.value.trim() != 2){
        msg += "Tipo de documento desconocido. <br>";
        ban = false;
        reg_tipo_documento.style.backgroundColor = colorDesaprobado;
    }
    else reg_tipo_documento.style.backgroundColor = colorAprobado;

    //Fecha de nacimiento y que tenga mas de 18 años.
    let fechaInput      = reg_fecha.value;
    let fechaNacimiento = new Date(fechaInput);
    let hoy             = new Date();
     //Calcula la diferencia en milisegundos entre la fecha actual y la fecha de nacimiento
     var diferenciaMilisegundos = hoy.getTime() - fechaNacimiento.getTime();
     //Calcula la edad dividiendo la diferencia de milisegundos por el número de milisegundos en un año
     var edad = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24 * 365.25));

     if (edad >= 18) {
        reg_fecha.style.backgroundColor = colorAprobado;
     } 
     else {
        msg += "Debes tener más de 18 para poder publicar en arriendofinca.com <br>";
        ban = false;
        reg_fecha.style.backgroundColor = colorDesaprobado;
    }
    setTimeout(function() {
        naturalizeColors([reg_nombre, reg_tipo_documento, reg_documento, reg_fecha]);
      }, 6000);
    return { ban : ban,
             msg: msg };
}

function naturalizeColors(objects){
    for(item of objects) item.style.backgroundColor = "initial";
}

function validar_paso_2(ban, msg){
    //contraseña
    if(reg_contraseña.value.trim() === ""){
        msg += "La contraseña está vacía. <br>";
        ban = false;
        reg_contraseña.style.backgroundColor = colorDesaprobado;
    }
    else if (reg_contraseña.value.trim().length <= 7){
        msg += "La contraseña es demasiado corta. <br>";
        ban = false;
        reg_contraseña.style.backgroundColor = colorDesaprobado;
    }
    else reg_contraseña.style.backgroundColor = colorAprobado;

    //Confirmar contraseña
    if(reg_conf_contraseña.value.trim().length == 0){
        ban=false;
        reg_conf_contraseña.style.backgroundColor = colorDesaprobado;
    }
    else if(reg_contraseña.value.trim().length >= 8 && reg_contraseña.value.trim() !== reg_conf_contraseña.value.trim()){
        msg += "Las contraseñas no coinciden. <br>";
        ban = false;
        reg_conf_contraseña.style.backgroundColor = colorDesaprobado;
    }
    else reg_conf_contraseña.style.backgroundColor = colorAprobado;

    setTimeout(function() {
        naturalizeColors([reg_contraseña, reg_conf_contraseña]);
      }, 6000);

    return { ban : ban,
             msg: msg };
}

function validar_paso_3(ban, msg){

    var regex = /^[0-9]+$/; //Expresión regular para validar que la cadena solo contenga números - documento y numero de celular
    let expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; //expresion para verificar que el email tenga la estructura: xxxxxxxx@xxxx.xxx
    //email
    if(!expresionRegular.test(reg_email.value)){
        msg += "Ingrese un correo electrónico válido. <br>";
        ban = false;
        reg_email.style.backgroundColor = colorDesaprobado;
    }
    else reg_email.style.backgroundColor = colorAprobado;

    //Celular es tomado como varchar en la base de datos
    if(reg_celular.value.trim().length >= 1){
        if(reg_celular.value.trim().length >= 20){
            msg += "Estás ingresando un número demasiado largo. <br>";
            ban = false;
            reg_celular.style.backgroundColor = colorDesaprobado;
        }
        else if(isNaN(parseInt(reg_celular.value.trim())) && check_whatsapp.checked){
            msg += "Ingrese un número válido. <br>";
            ban = false;
            reg_celular.style.backgroundColor = colorDesaprobado;
        }    
        else if(!paises_value.includes(reg_indicativo.value.trim())){
            msg += "Escoja el país de su número. <br>";
            ban = false;
            reg_indicativo.style.backgroundColor = colorDesaprobado;
        }  
        else if(!regex.test(reg_celular.value.trim())){
            msg += "Ingrese un número válido. Se coló un caracter no numérico. <br>";
            ban = false;
            reg_celular.style.backgroundColor = colorDesaprobado;
        }
        else{
            reg_indicativo.style.backgroundColor = colorAprobado;
            reg_celular.style.backgroundColor = colorAprobado;
        }
    }

    setTimeout(function() {
        naturalizeColors([reg_email, reg_celular,reg_indicativo]);
      }, 6000);

    return { ban : ban,
             msg: msg };
}

/*-------------------------------------------------------------------
#Validaciones al servidor
--------------------------------------------------------------------*/
function mandar_servidor(){
    let check_wsp = 0;
    check_whatsapp.checked == true ? check_wsp = 1 : check_wsp = 0;
    $.ajax({
        url: '../php/register.php',
        type: 'POST',
        data: {
            nombre          : reg_nombre.value,
            documento       : reg_documento.value,
            tipo_documento  : reg_tipo_documento.value,
            fecha_nacimiento: reg_fecha.value,
            email           : reg_email.value,
            contraseña      : reg_contraseña.value,
            celular         : reg_celular.value,
            auth_whatsapp   : check_wsp,
            pais            : reg_pais.value,
            departamento    : reg_estado_departamento.value,
            ciudad          : reg_ciudad.value
        },
        success: function(response){
            let jsonString = JSON.stringify(response);
            let data       = JSON.parse(jsonString);
            if(data.success){
                mensaje_confimacion(data.mensaje);
                div_registro.style.display = "none";
                div_login.style.display    = "block";
                clear_register_items();
                cont_pasos                = 0;
            }else{
                if(data.state == 0){
                    let error_text = "";
                    for(let error of data.mensaje){
                        error_text += error;
                    }
                    createToastNotify(1,"Error desde el servidor", error_text);
                }
                else if(data.state == 1){
                    let msg_warning = "";
                    for(let warning of data.mensaje){
                        msg_warning += warning;
                    }
                    createToastNotify(3,"Cuidado",msg_warning)
                }
                else if(data.state == 2){
                    createToastNotify(1,"Error desde el servidor", data.mensaje);
                }
                else{
                    createToastNotify(1,"Error desde el cliente", "Error desconocido");
                }
            }

        },
        error: function(jqXHR, textStatus, errorThrown){
            // Error en la solicitud AJAX
            console.log('Error en la solicitud');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function mensaje_confimacion(mensaje){
    createToastNotify(0,"Completado con exito", mensaje);
}
