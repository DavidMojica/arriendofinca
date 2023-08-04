const rollback   = document.getElementById('rollback');
var btn_expandir = document.querySelectorAll('.btn_expandir');
const text_certificado = document.getElementsByClassName('text_certificado');
const div_certificado  = document.getElementsByClassName('div_certificado');
const help_certificado = "Garantizamos la certificación de arriendofinca.com para este inmueble, asegurando que es totalmente seguro y cumple con todo lo que promete.";
var inmoviliarios = document.getElementsByClassName('inmoviliario');

for(let inmoviliario of inmoviliarios){
  set_bgcolors(inmoviliario);
}


for(let div of div_certificado){
  div.addEventListener('mouseover', function(){
      let text = div.querySelector(".text_certificado");

      text.textContent = help_certificado;
  });

  div.addEventListener('mouseout',function(){
    let text = div.querySelector(".text_certificado");

    text.textContent = "INMUEBLE CERTIFICADO";
  });
}

btn_expandir.forEach(function(btn){
  btn.addEventListener('click', function(){
    let itemContenedor = this.parentElement;
    let detalles = itemContenedor.querySelector(".info_hidden");

    detalles.classList.toggle("mostrar");
    this.textContent = detalles.classList.contains("mostrar") ? "Ocultar detalles" : "Mostrar detalles";
  })
});

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
