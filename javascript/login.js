/*-------------------------------------------------------------------
#Global variables
--------------------------------------------------------------------*/
const log_user = document.getElementById('log_user');
const log_pass = document.getElementById('log_pass');
const log_btn  = document.getElementById('log_btn');
const btn_reg_open = document.getElementById('btn_reg_open');
const div_login = document.getElementById('div_login');
const div_registro = document.getElementById('div_registro');
const btn_login_open = document.getElementById('btn_login_open');
const body = document.getElementById('html');
/*-------------------------------------------------------------------
#Dom
--------------------------------------------------------------------*/
log_btn.addEventListener('click', function(){
    validar_login();
});

btn_reg_open.addEventListener('click', function(){
    div_registro.style.display = "block";
    div_login.style.display = "none";
    clear_register_items();
});

btn_login_open.addEventListener('click', function(){
    div_registro.style.display = "none";
    div_login.style.display = "block";
    clear_register_items();
});

/*-------------------------------------------------------------------
#Functions
--------------------------------------------------------------------*/
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
    if(user === "" || pass === ""){
        msg += "Algún campo está vacío<br>";
        ban = false;
    }
    else if(expresionRegular.test(user)){
        //Si el user es un correo
        user_type = 1;
        console.log("CASO 1"+user + pass);
    }
    else if(regex.test(user)){
        //si el user es un documento
        user_type = 2;
        console.log("CASO 2"+user + pass);
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
                window.location.href = "../php/userarea.php";
            }
            else{
                createToastNotify(1,"Revise sus datos", data.mensaje);
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

