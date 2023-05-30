/*-------------------------------------------------------------------
#Global variables
--------------------------------------------------------------------*/
const log_user = document.getElementById('log_user');
const log_pass = document.getElementById('log_pass');
const log_btn  = document.getElementById('log_btn');

function mandar_servidor_log(user, pass, user_type){
    $.ajax({
        url: '../php/login.php',
        type: 'post',
        data: {
            user      : user,
            pass      : pass,
            user_type : user_type
        },
        success: function(response){
            let jsonString = JSON.stringify(response);
            let data       = JSON.parse(jsonString);
            console.log(data);
            if(data.success){
                createToastNotify(0,"Bienvenido", data.mensaje);
            }
            else{
                createToastNotify(1,"Revise sus datos", data.mensaje);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            // Error en la solicitud AJAX
            console.log('Error en la solicitud');
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

/*-------------------------------------------------------------------
#Dom
--------------------------------------------------------------------*/
log_btn.addEventListener('click', function(){
    validar_login();
});

function validar_login(){
    let msg = "";
    let ban = true;
    let user_type = 0;
    let expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; //expresion para verificar que el email tenga la estructura: xxxxxxxx@xxxx.xxx
    let regex = /^[0-9]+$/; //Expresión regular para validar que la cadena solo contenga números - documento y numero de celular
    let user = log_user.value.trim();
    let pass = log_pass.value.trim();
    //Validaciones
    //user
    if(user === ""){
        msg += "Algún campo está vacío<br>";
        ban = false;
    }
    else if(expresionRegular.test(user)){
        //Si el user es un correo
        user_type = 1;
    }
    else if(regex.test(user)){
        //si el user es un documento
        user_type = 2;
    }
    else{
        msg += "El usuario no es un email o un documento <br>";
        ban = false;
    }

    if(!ban){
        createToastNotify(1,"Revise sus datos",msg);
    }
    if(ban && (user_type === 1 || user_type === 2)){
        mandar_servidor_log(user, pass, user_type);
    }
}

