/*-------------------------------------------------------------------
#create Toast Notify
--------------------------------------------------------------------*/
/**
 * Mostrar una notificación que emite al usuario un determinado mensaje.
 * Hay 4 tipos de notificaciones:
 * @param {number} opc - Opción para seleccionar el tipo de notificación:
 *                       0: success
 *                       1: error
 *                       2: info
 *                       3: warning
 * @param {string} head - Encabezado de la notificación.
 * @param {string} msg - Mensaje de la notificación.
 */

function createToastNotify(opc,head,msg){
    switch(opc){
        case 0:{
            // Mostrar notificación de éxito con el encabezado y mensaje proporcionados.
            new ToastNotify('success', {
                head: head,
                msg: msg,
                timer: 6000 // Tiempo en milisegundos para que la notificación desaparezca automáticamente.
            });
            break;
        };
        case 1:{
            // Mostrar notificación de error con el encabezado y mensaje proporcionados.
            new ToastNotify('error', {
                head: head,
                msg: msg,
                timer: 6000
            });
            break;
        };
        case 2:{
            // Mostrar notificación de información con el encabezado y mensaje proporcionados.
            new ToastNotify('info', {
                head: head,
                msg: msg,
                timer: 7000
            });
            break;
        };
        case 3:{
             // Mostrar notificación de advertencia con el encabezado y mensaje proporcionados, incluyendo un botón de aceptar.
            new ToastNotify('warning', {
                head: head,
                msg: `${msg}
                <div class="row">
                    <div class="col d-flex justify-content-end">
                      <button class="btn btn-light border btn-sm" onclick="mensaje_confimacion()">acepto</button>
                    </div>
                </div>`
            });
            break;
        };
    };
}