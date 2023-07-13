const chk_certificar = document.getElementById('chk_certificar');
const btn_certificar = document.getElementById('btn_certificar');
const id_mov         = document.getElementById('id_mov');

btn_certificar.addEventListener('click', function(){
    console.log(id_mov.value);
    if(chk_certificar.checked){
        $.ajax({
            url: 'add_certificado.php',
            type: 'post',
            data: {
                id_mov: id_mov.value
            },
            success: function(response){
                console.log(response);
                if(response.success){
                    window.location.href = "../php/userarea.php";
                }
                else{
                    createToastNotify(1,"Error al asignar el certificado", response.mensaje);
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
        createToastNotify(1,"No se certificó","Usted no ha aceptado términos y condiciones.");
    }
});