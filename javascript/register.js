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
var pais_activo = null;

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


btn_register.addEventListener('click', function(){
    validar();
});

/*-------------------------------------------------------------------
#Functions
--------------------------------------------------------------------*/

function validar(){
    //Mensaje que usaremos en caso de que algún campo sea inválido.
    let msg = "";
    let ban = true;
    let expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; //expresion para verificar que el email tenga la estructura: xxxxxxxx@xxxx.xxx
    var regex = /^[0-9]+$/; //Expresión regular para validar que la cadena solo contenga números - documento y numero de celular
    //Validaciones
    //nombre
    if(reg_nombre.value.trim() === ""){
        msg += "El campo nombre está vacío. <br>";
        ban = false;
    }
    else if(reg_nombre.value.trim().length < 10){
        msg += "Nombre demasiado corto. <br>";
        ban = false;
    }

    //cedula
    if(reg_documento.value.trim() === ""){
        msg += "El campo cédula está vacío. <br>";
        ban = false;
    }
    else if(reg_documento.value.trim().length < 5){
        msg += "Documento demasiado corto. <br>";
        ban = false;
    }
    else if(!regex.test(reg_documento.value.trim())){
        msg += "Documento inválido. Solo debe de contener numeros. <br>";
        ban = false;
    }

    //Tipo_documento
    if(reg_tipo_documento.value.trim() != 1 && reg_tipo_documento.value.trim() != 2){
        msg += "Tipo de documento desconocido. <br>";
        ban = false;
    }

    //Fecha de nacimiento y que tenga mas de 18 años.
    let fechaInput      = reg_fecha.value;
    let fechaNacimiento = new Date(fechaInput);
    let hoy             = new Date();
     //Calcula la diferencia en milisegundos entre la fecha actual y la fecha de nacimiento
     var diferenciaMilisegundos = hoy.getTime() - fechaNacimiento.getTime();
     //Calcula la edad dividiendo la diferencia de milisegundos por el número de milisegundos en un año
     var edad = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24 * 365.25));

     if (edad >= 18) {} 
     else {
        msg += "Debes tener más de 18 para poder publicar en arriendofinca.com <br>";
        ban = false;
    }

    //email
    if(!expresionRegular.test(reg_email.value)){
        msg += "Ingrese un correo electrónico válido. <br>";
        ban = false;
    }

    //contraseña
    if(reg_contraseña.value.trim() === ""){
        msg += "La contraseña está vacía. <br>";
        ban = false;
    }
    else if (reg_contraseña.value.trim().length <= 7){
        msg += "La contraseña es demasiado corta. <br>";
        ban = false;
    }

    //Confirmar contraseña
    if(reg_contraseña.value.trim().length >= 8 && reg_contraseña.value.trim() !== reg_conf_contraseña.value.trim()){
        msg += "Las contraseñas no coinciden. <br>";
        ban = false;
    }

    //Celular es tomado como varchar en la base de datos
    if(reg_celular.value.trim().length >= 1){
        if(reg_celular.value.trim().length >= 20){
            msg += "Estás ingresando un número demasiado largo. <br>";
            ban = false;
        }
        else if(isNaN(parseInt(reg_celular.value.trim()))){
            msg += "Ingrese un número válido. <br>";
            ban = false;
        }    
        else if(!paises_value.includes(reg_indicativo.value.trim())){
            msg += "Escoja el país de su número. <br>";
            ban = false;
        }  
        else if(!regex.test(reg_celular.value.trim())){
            msg += "Ingrese un número válido. Se coló un caracter no numérico. <br>";
            ban = false;
        }
    }

    //check_whatsapp Hecho en php. Y funcionando.
    // if(check_whatsapp.checked && reg_celular.value.trim().length == 0){
    //     msg_warning += "Marcaste la casilla de contactar al WhatsApp sin haber proporcionado un número. <br>Pulsa X para regresar e ingresar un número. <br>Pulsa continuar para ignorar este mensaje.";
    //     ban_warning = false;
    // }
    //pais
    if(reg_pais.value.trim() == "default" || reg_estado_departamento.value.trim() == "default" || reg_ciudad.value.trim() == "default"){
        msg += "Indique su lugar de residencia. <br>";
        ban = false;
    }
    //Verificar que el valor del país proveniente de html exista en nuestro archivo json.
    else if(!paises_value.includes(reg_pais.value.trim())){
        ban = false;
        msg += "Error en el valor del país. <br>";
    }
    else if(!deptos_value.includes(reg_estado_departamento.value.trim())){
        //Verificar que el valor del departamento proveniente de html exista en nuestro archivo json.
        ban = false;
        msg += "Error en el valor del departamento <br>";
    }
    else if (!ciudades_value.includes(reg_ciudad.value.trim())){
        //Verificar que el valor de proveniente de html exista en nuestro archivo json.
        ban = false;
        msg += "Error en el valor de la ciudad <br>";
    }

    if(!ban){
        createToastNotify(1,"Revise el formulario",msg)
    }
    // if(ban && !ban_warning){
        
    // }
    if(ban){
        mandar_servidor();
    }
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
