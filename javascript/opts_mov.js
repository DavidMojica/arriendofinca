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