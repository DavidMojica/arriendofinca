<?php

/**
 * Regresa la respuesta a Ajax.
 * Se creó para evitar reiteración de código.
 * @param bool $success Un booleano que representa el exito o no de la operación.
 * @param string|array $mensaje El mensaje de salida que se le mostrará al usuario.
 */
function return_Response($success, $mensaje){
    $response['success'] = $success;
    $response['mensaje'] = $mensaje;
    $jsonResponse = json_encode($response);
    header('Content-Type: application/json');
    exit($jsonResponse);
}


/**
 * Regresa la respuesta a Ajax.
 * Este se usa en caso de que se necesite identificar un tipo de suceso o error.
 * @param bool $success Un booleano que representa el exito o no de la operación.
 * @param string|array $mensaje El mensaje de salida que se le mostrará al usuario.
 * @param int $state   Un número que representa el tipo de suceso o error
 */

function return_Response_Bad($success, $mensaje, $state){
    $response['success'] =  $success;
    $response['mensaje'] = $mensaje;
    $response['state']   = $state;
    $jsonResponse = json_encode($response);
    header('Content-Type: application/json');
    exit($jsonResponse);
}


/**
 * Retorna todos los tipos de inmueble con su respectivo id y nombre.
 * @return array asociativo
 */
function get_tipos_inmueble(){
    #Libraries
    include('PDOconn.php');

    $query = "SELECT * FROM tbl_tipo_inmueble";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
/**
 * Retorna todos los paises que hayan en la base de datos.
 * @return array asociativo.
 */
function get_paises(){
    include('PDOconn.php');
    $query = "SELECT * FROM tbl_pais";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Retorna el nombre del tipo del inmueble a base de su id,
 * @param object La query preparada por PDO.
 * @return string
 */
function get_tipo_inmueble_PDOQUERY($stmt){
    if($stmt->execute()){
        $res_tp_inmueble = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($res_tp_inmueble) > 0){
            $row_tp_inmueble = $res_tp_inmueble[0];
            #Quitar la S al final del tipo de inmueble
            $tipo_inmueble   = $row_tp_inmueble['tipo_inmueble'];
            if(substr($tipo_inmueble, -1) === 's')
                $tipo_inmueble = rtrim($tipo_inmueble, 's');
        } else{
        $tipo_inmueble = "No se obtuvo el tipo inmueble";
        }
    } 
    return $tipo_inmueble;
}
/**
 * Obtiene el tipo de AOV dependiendo del id_aov.
 * @param object La query preparada por PDO.
 * @return string
 */

function get_tipo_aov_PDOQUERY($stmt){
    if($stmt->execute()){
        $res_tp_aov = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($res_tp_aov) > 0){
            $row_tp_aov = $res_tp_aov[0];
            $tipo_aov = $row_tp_aov['descripcion_aov'];
        }
        else{
            $tipo_aov = "No se pudo obtener el AOV";
        }
    }
    return $tipo_aov;
}
/**
 * Retorna los nombres del municipio, estado y país de la consulta respectiva.
 * Tambien retorna el id del estado y el id del pais.
 * 'nombre_municipio'
 * 'nombre_estado'    
 * 'nombre_pais'      
 * 'id_estado'        
 * 'id_pais'          
 * @param object La query preparada por PDO.
 * @return array
 */
function get_nombres_ubicacion_PDOQUERY($stmt) {
    include('PDOconn.php');

    $ban = false; // Definir $ban como false al principio

    if ($stmt->execute()) {
        $res_nom_municipio = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($res_nom_municipio) > 0) {
            $row_nom_municipio = $res_nom_municipio[0];
            $nombre_municipio  = $row_nom_municipio['nombre_municipio'];
            $id_estado         = $row_nom_municipio['id_estado'];

            $query = "SELECT nombre_estado, id_pais FROM tbl_estado WHERE id_estado = :id_estado";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $res_nom_estado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($res_nom_estado) > 0) {
                    $row_nom_estado = $res_nom_estado[0];
                    $nombre_estado  = $row_nom_estado['nombre_estado'];
                    $id_pais        = $row_nom_estado['id_pais'];

                    $query = "SELECT * FROM tbl_pais WHERE id_pais = :id_pais";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id_pais', $id_pais, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        $res_nom_pais = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($res_nom_pais) > 0) {
                            $row_nom_pais = $res_nom_pais[0];
                            $nombre_pais  = $row_nom_pais['nombre_pais'];
                            $ban = true; // Establecer $ban como true cuando se obtiene el nombre del país
                        }
                    }
                }
            }
        }
    }

    if ($ban) {
        $nombres = array(
            'nombre_municipio' => $nombre_municipio,
            'nombre_estado'    => $nombre_estado,
            'nombre_pais'      => $nombre_pais,
            'id_estado'        => $id_estado,
            'id_pais'          => $id_pais
        );
        return $nombres;
    } else {
        return array();
    }
}

/**
 * Retorna todos los detalles de la propiedad de la query, incluyendo su ubicacion.
 * @param string $La query a evaluar.
 * @param int    $El id del inmoviliario
 * @return array $El arreglo con los detalles en su interior.
 */
function get_detalles_propiedad_PDOQUERY($query, $id_inmoviliario){
    include('PDOconn.php');

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_moviliario', $id_inmoviliario, PDO::PARAM_INT);
    if($stmt->execute()){
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result) > 0){
            $row              = $result[0];
            $modo             = $row['arriendo_o_venta'] == 1 ? "Arriendo" : "Venta";
            $precio           = $row['precio'];
            $direccion        = $row['direccion'];
            $descripcion      = $row['descripcion'];
            $id_municipio     = $row['id_municipio_ubicacion'];
            $id_tipo_inmueble = $row['id_tipo_inmueble'];

            #---Obtener el nombre del tipo de inmueble---#
            $query = "SELECT * FROM tbl_tipo_inmueble WHERE id_tipo_inmueble = :id_tipo_inmueble";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_tipo_inmueble', $id_tipo_inmueble, PDO::PARAM_INT);

            if($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) > 0){
                    $row = $result[0];
                    $nombre_tipo_inmueble = $row['tipo_inmueble'];
                }
            }

            #---Obtener nombres de municipios y ubicacion exacta---#

            $query = "SELECT * FROM tbl_municipio WHERE id_municipio = :id_municipio";
            $stmt  = $pdo->prepare($query);
            $stmt->bindParam(':id_municipio', $id_municipio, PDO::PARAM_INT);

            return array(
                'modo'          => $modo,
                'precio'        => $precio,
                'direccion'     => $direccion,
                'descripcion'   => $descripcion,
                'tipo_inmueble' => $nombre_tipo_inmueble,
                'ubicacion'     => get_nombres_ubicacion_PDOQUERY($stmt)
            ); 
        }
        else return array(
            'modo'          => "No se pudo obtener",
            'precio'        => "No se pudo obtener",
            'direccion'     => "No se pudo obtener",
            'descripcion'   => "No se pudo obtener",
            'tipo_inmueble' => "No se pudo obtener",
            'ubicacion'     => get_nombres_ubicacion_PDOQUERY($stmt)
        ); 
    }
    else return array(
        'modo'          => "No se pudo obtener",
        'precio'        => "No se pudo obtener",
        'direccion'     => "No se pudo obtener",
        'descripcion'   => "No se pudo obtener",
        'tipo_inmueble' => "No se pudo obtener",
        'ubicacion'     => get_nombres_ubicacion_PDOQUERY($stmt)
    ); 
}

/**
 * Comprueba si la sesion está activa y devuelve algunos valores utiles.
 * @return array
 */
function comprobar_sesion_valida($ses){
    include('PDOconn.php');

    $ban     = false;
    $tp_user = 0;
    $documento_usuario = null; // Definir una valor predeterminado para $documento_usuario

    if (!isset($_SESSION['username'])) { // Si la sesión no está iniciada se redirige al usuario al index.
        header("Location: ../index.php");
        exit;
    } else {
        $user = $_SESSION['username'];
        if (ctype_digit($user)) {
            $tp = "documento";
            $tp_user = 1;
        } else {
            $tp = "email";
            $tp_user = 2;
        }

        $query = "SELECT documento FROM tbl_usuario where $tp = :user";
        $stmt = $pdo->prepare($query);

        if ($tp_user == 1) {
            $stmt->bindParam(':user', $user, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            $row = $result[0];
            $documento_usuario = $row['documento'];
            $ban = true;
        }
    }

    return array(
        'ban' => $ban,
        'documento_usuario' => $documento_usuario
    );
}

function create_inmoviliario($row, $sel_ciudad){
    include('PDOconn.php');
    echo "<div class='inmoviliario'>";
    #obtención de datos
    $id_inmueble          = $row['id_inmueble'];
    $id_tipo_inmueble     = $row['id_tipo_inmueble'];
    $precio               = $row['precio'];
    $direccion            = $row['direccion'];
    $descripcion          = $row['descripcion'];
    $arriendo_o_venta     = $row['arriendo_o_venta'];
    $prop_area            = $row['area'];
    $prop_habitaciones    = $row['habitaciones'];
    $prop_banos           = $row['banos'];
    $prop_area_construida = $row['area_construida'];

    $query = "SELECT tipo_inmueble FROM tbl_tipo_inmueble WHERE id_tipo_inmueble = :id_tipo_inmueble";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_tipo_inmueble', $row['id_tipo_inmueble'], PDO::PARAM_INT);

    $tipo_inmueble = get_tipo_inmueble_PDOQUERY($stmt);

    $query = "SELECT descripcion_aov FROM tbl_arriendo_o_venta WHERE id_aov = :id_aov";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_aov', $row['arriendo_o_venta'],PDO::PARAM_INT); 

    $tipo_aov = get_tipo_aov_PDOQUERY($stmt);

    $query = "SELECT nombre_municipio, id_estado, id_pais FROM tbl_municipio WHERE id_municipio = :id_municipio";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_municipio', $row['id_municipio_ubicacion'], PDO::PARAM_INT);
    
    $nombres = get_nombres_ubicacion_PDOQUERY($stmt);
    if(!empty($nombres)){
        $nombre_municipio = $nombres['nombre_municipio'];
        $nombre_estado    = $nombres['nombre_estado'];
        $nombre_pais      = $nombres['nombre_pais'];
        $id_estado        = $nombres['id_estado'];
    }else{
        $nombre_municipio = "Not obtained";
        $nombre_estado    = "Not obtained";
        $nombre_pais      = "Not obtained";
    }

    
    // Crear un div para cada fila
    echo '<div class="resultado">';
    echo '<h3>'. $tipo_inmueble .' en '.$tipo_aov .'</h3>';
    #imagenes
    echo "<div class='inmoviliario_imgs'>";
            
    $query = "SELECT imagen FROM tbl_imagenes WHERE id_inmueble = :id_inmueble"; 
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0) {
    echo '<div class="swiper mySwiper">
            <div class="swiper-wrapper">';

    foreach ($result as $row) {
        $image_blob = $row['imagen'];

        // Obtener información sobre la imagen
        $image_info = getimagesizefromstring($image_blob);
        $mime_type = $image_info['mime'];

        // Obtener la extensión basada en el tipo MIME
        $extension = image_type_to_extension($image_info[2]);

        // Establecer el encabezado Content-type según la extensión
        if ($extension === '.jpg' || $extension === '.jpeg') {
        echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
        } elseif ($extension === '.png') {
        echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
        } elseif ($extension === '.gif') {
            echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
        } elseif ($extension === '.jfif') {
        echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
        } else {
                echo "La imagen no pudo ser cargada";
        }
    }

    echo '<div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    </div></div>';
    } else {
        echo "El inmueble no posee imágenes";
    }
    echo "</div>"; #End imagen

    #Precio
    if($arriendo_o_venta == 1)
        echo '<p>Precio de arriendo: ' . $precio . '</p>'; 
    else if ($arriendo_o_venta == 2)
        echo '<p>Precio de venta: ' . $precio . '</p>';

    #Ubicacion
    echo '<p>Municipio de Ubicación: ' . $nombre_municipio . ' - '.$nombre_estado.' - '. $nombre_pais.'</p>';
    
    
    #Details
    ?>
    <div class="div_extra_info">
        <ul id="info_list">
        <?php 
            $prop_area_construida = (strlen($prop_area_construida)>0) ? $prop_area_construida : "No especificado";
            if($id_tipo_inmueble != 1 && $id_tipo_inmueble != 3){
            echo  "<li> <span> Área (m2): </span> <p> ".$prop_area." </p> </li> <li> <span> Habitaciones:  </span> <p>".$prop_habitaciones." </p> </li>   <li> <span> Baños: </span> <p> ".$prop_banos." </p> </li> ";
            }
            else{
                echo  "<li> <span> Área (m2): </span> <p> ".$prop_area." </p> </li> <li> <span> Habitaciones:  </span> <p>".$prop_habitaciones." </p> </li>   <li> <span> Baños: </span> <p> ".$prop_banos." </p> </li> <li> <span> Área construida (m2): </span> <p> ".$prop_area_construida." </p> </li> ";
            }
        ?>  
        </ul>
    </div>

    <?php

    #echo '<p>Dirección: ' . $direccion . '</p>';
    #echo '<p>Descripción: ' . $descripcion . '</p>';
    echo '<input type="button" value="Contactar" class="btn_contactar">';
    echo '</div></div>';
}

?>