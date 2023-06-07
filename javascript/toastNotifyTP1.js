/*-------------------------------------------------------------------
#create Toast Notify
--------------------------------------------------------------------*/
/**
 * Mostrar una notificacion que emite al usuario un determinado mensaje.
 * Hay 4 tipos de notificacion:
 * @param {number} opc 
 *  0: sucess
 *  1: error 
 *  2: info
 *  3: warning
 * @param {string} head 
 * @param {string} msg

 */

function createToastNotify(opc,head,msg){
    switch(opc){
        case 0:{
            new ToastNotify('success', {
                head: head,
                msg: msg,
                timer: 6000
            });
            break;
        };
        case 1:{
            new ToastNotify('error', {
                head: head,
                msg: msg,
                timer: 6000
            });
            break;
        };
        case 2:{
            new ToastNotify('info', {
                head: head,
                msg: msg,
                timer: 7000
            });
            break;
        };
        case 3:{
            new ToastNotify('warning', {
                head: head,
                msg: `${msg}
                <div class="row">
                    <div class="col d-flex justify-content-end">
                      <button class="btn btn-light border btn-sm" onclick="mensaje_confimacion()">acepto</button>
                    </div>
                </div>          
                `
            });
            break;
        };
    };
}