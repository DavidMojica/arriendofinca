/**
 * Función que valida las propidades generales de cada inmueble del lado del cliente.
 * 
 * @param {number} add_tipo 
 * @param {number} precio 
 * @param {string} direccion 
 * @param {string} prop_desc 
 * @returns 
 */
function validaciones_general(add_tipo, precio, direccion, prop_desc, add_area, add_banos, add_habitaciones, add_area_construida){
    const total_tipo_propiedades = 8;
    const regex = /^[0-9]+$/; //Expresión regular para validar que la cadena solo contenga números - documento y numero de celular
    let ban = true;
    let msg = "";

    //Validar que no hayan modificado el tipo de propiedad en HTML
    if((add_tipo.value <= 0 || add_tipo.value > total_tipo_propiedades)){
        ban = false;
        msg += "El tipo de propiedad no coincide con ningun tipo. <br>";
    } 
    else if(add_tipo.value == "default"){
        ban = false;
        msg += "Por favor especifique el tipo de propiedad. <br>";
    }
    //Validar que precio sea un numero.
    if(precio.value.trim() === ""){
        msg += "El campo precio es obligatorio. <br>";
        ban = false;
    }
    else if(!regex.test(precio.value.trim())){
        msg += "Se coló un carácter no numérico en el precio. <br>";
        ban = false;
    }

    //Validar dirección del inmueble
    if(direccion.value.trim() === ""){
        msg += "El campo dirección es obligatorio. <br>";
        ban = false;
    }
    else if(direccion.value.trim().length <= 5){
        msg += "La direccion es demasiado corta. <br>";
        ban = false;
    }

    //Que la descripción de la propiedad no sea demasiado corta.
    if(prop_desc.value.trim().length === ""){
        msg += "Ingrese una descripción de su inmueble. <br>";
        ban = false;
    }
    else if(prop_desc.value.trim().length < 10){
        msg += "Proporcione una información más larga por favor. <br>";
        ban = false;
    }
    //Validar el area
    if(add_area.value.trim() === ""){
        msg += "El campo area es obligatorio. <br>";
        ban = false;
    }
    else if(!regex.test(add_area.value.trim())){
        msg += "Se coló un carácter no numérico en el campo area. <br>";
        ban = false;
    }
    //validar baños
    if(add_banos.value.trim() === ""){
        msg += "El campo baños es obligatorio. <br>";
        ban = false;
    }
    else if(!regex.test(add_banos.value.trim())){
        msg += "Se coló un carácter no numérico en el campo baños. <br>";
        ban = false;
    }

    //validar habitaciones
    if(add_habitaciones.value.trim() === ""){
        msg += "El campo habitaciones es obligatorio. <br>";
        ban = false;
    }
    else if(!regex.test(add_habitaciones.value.trim())){
        msg += "Se coló un carácter no numérico en el campo habitaciones. <br>";
        ban = false;
    }
    //Area construida solamente en tipo 1 o 3
    if(add_tipo == 1 || add_tipo == 3){
        if(add_area_construida.value.trim() === ""){
            msg += "El campo area construida es obligatorio. <br>";
            ban = false;
        }
        else if(!regex.test(add_area_construida.value.trim())){
            msg += "Se colón un carácter no numérico en el campo area construida. <br>";
            ban = false;
        }
    }

    return {auth: ban, mensaje: msg};
}

/**
 * Esta funcion se encarga de obtener y dibujar los departamentos en el combobox departamentos
 * dependiendo del país seleccionado.
 * @param {Object combobox} add_departamento 
 * @param {Object combobox} add_ciudad 
 * @param {*} pais_activo 
 */
function AJAX_PAIS_CHANGE(add_departamento,add_ciudad, pais_activo, url){
    $.ajax({
        url: url,
        type: 'POST',
        data: {
          pais_activo: pais_activo,
          estado_activo: null,
          type: 0
        },
        success: function(response) {
            console.log(response);
          if (response.success) {
            add_departamento.innerHTML = "";
            var option = document.createElement('option');
            option.value = "default";
            option.text = "Seleccione un departamento...";
            add_departamento.appendChild(option);

            for (let estado of response.mensaje) {
                const id_estado = estado.id_estado;
                const nombre    = estado.nombre_estado;

                //creamos una nueva opcion
                const nueva_opcion = document.createElement('option');
                nueva_opcion.value =`${id_estado}`;
                nueva_opcion.textContent = `${nombre}`;
                add_departamento.appendChild(nueva_opcion);
            }
            add_departamento.disabled = false;
        }
        else{
            add_departamento.disabled = true;
            add_ciudad.disabled = true;
        }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Manejar errores aquí
        }
    });
}

/**
 * Esta función se encarga de obtener y dibujar los municipios en el combobox municipios
 * dependiendo del estado seleccionado.
 * @param {Object combobox} add_ciudad 
 * @param {Object combobox} depto_activo 
 */
function AJAX_ESTADO_CHANGE(add_ciudad, depto_activo, url){
    $.ajax({
        url: url,
        type: 'POST',
        data: {
          pais_activo: pais_activo,
          estado_activo: depto_activo,
          type: 1
        },
        success: function(response) {
            console.log(response);
            add_ciudad.innerHTML = "";
            var option = document.createElement('option');
            option.value = "default";
            option.text = "Seleccione una ciudad...";
            add_ciudad.appendChild(option);
          if (response.success) {
            for (let municipio of response.mensaje) {
                const id_municipio = municipio.id_municipio;
                const nombre    = municipio.nombre_municipio;

                //creamos una nueva opcion
                const nueva_opcion = document.createElement('option');
                nueva_opcion.value =`${id_municipio}`;
                nueva_opcion.textContent = `${nombre}`;
                add_ciudad.appendChild(nueva_opcion);
            }
            add_ciudad.disabled = false;
        }
        else{
            add_ciudad.disabled = true;
        }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Manejar errores aquí
        }
    });
}