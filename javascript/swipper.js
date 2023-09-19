try{
    const swiper = new Swiper(".mySwiper", {
    navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
    },
});

if(document.getElementsByClassName('buttonmodal')){
    const modals  = document.getElementsByClassName('modalcontent');
    const buttons = document.getElementsByClassName('buttonmodal');
    const close   = document.getElementsByClassName('close');
    const body    = document.getElementsByTagName("body");


    for(let button of buttons){
        button.addEventListener('click', function(){
            const modal = document.querySelector('.modalcontent')
            modal.style.display = "block";
            body.style.position = "static";
            body.style.height   = "100%";
            body.style.overflow = "hidden";
        });
    }

    for(let span of close){
        span.addEventListener('click', function(){
            const modal = document.querySelector('.modalcontent')
            modal.style.display = "none";
            body.style.position = "inherit";
            body.style.height   = "auto";
            body.style.overflow = "visible";
        })
    }
}


}catch(e){
}