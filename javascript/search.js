
//DOM
const rollback         = document.getElementById('rollback');
var btn_expandir       = document.querySelectorAll('.btn_expandir');
const text_certificado = document.getElementsByClassName('text_certificado');
const div_certificado  = document.getElementsByClassName('div_certificado');
var inmoviliarios      = document.getElementsByClassName('inmoviliario');
//Variables globales
const help_certificado = "Garantizamos la certificación de arriendofinca.com para este inmueble, asegurando que es totalmente seguro y cumple con todo lo que promete.";

for(let inmoviliario of inmoviliarios){
  set_bgcolors(inmoviliario);
}

// Iterar sobre los elementos con clase 'div_certificado'
// Agregar eventos 'mouseover' y 'mouseout' para mostrar y ocultar el mensaje de ayuda 'help_certificado'
for(let div of div_certificado){
  div.addEventListener('mouseover', function(){
      let text = div.querySelector(".text_certificado");
      // Al pasar el cursor sobre el elemento, se cambia el texto con el contenido del mensaje de ayuda
      text.textContent = help_certificado;
  });

  div.addEventListener('mouseout',function(){
    let text = div.querySelector(".text_certificado");
    // Al quitar el cursor del elemento, se vuelve a mostrar el texto original "INMUEBLE CERTIFICADO"
    text.textContent = "INMUEBLE CERTIFICADO";
  });
}

// Agregar un evento 'click' a cada elemento con clase 'btn_expandir'
// Cuando se hace clic en un botón de expandir, se mostrarán u ocultarán ciertos detalles relacionados con el inmueble
btn_expandir.forEach(function(btn){
  btn.addEventListener('click', function(){
    let itemContenedor = this.parentElement;
    let detalles = itemContenedor.querySelector(".info_hidden");

    detalles.classList.toggle("mostrar");
    this.textContent = detalles.classList.contains("mostrar") ? "Ocultar detalles" : "Mostrar detalles";
  })
});

// Función para copiar la dirección de correo electrónico del dueño del inmueble al portapapeles.
function copyEmail(email) {
    // Crear un elemento temporal (textarea) para almacenar el correo electrónico
    var tempTextarea = document.createElement("textarea");
    tempTextarea.value = email;
    document.body.appendChild(tempTextarea);
    
    tempTextarea.select();
    
    document.execCommand("copy");
    
    document.body.removeChild(tempTextarea);
    
    createToastNotify(0, "Copiado!", "Correo copiado en el portapapeles. Puedes escribirle un correo al dueño del inmueble :).");
}


var swiper = new Swiper(".mySwiper", {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
