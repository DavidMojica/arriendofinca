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


function comprobar_existencia_ubicacion($id_pais, $id_estado, $id_municipio){
    include('PDOconn.php');

    $query = "SELECT * FROM tbl_pais WHERE id_pais = :id_pais";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_pais',$id_pais, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) > 0){
        $query = "SELECT * FROM tbl_estado WHERE id_estado = :id_estado";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_estado',$id_estado, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) > 0){
            $query = "SELECT * FROM tbl_municipio WHERE id_municipio = :id_municipio";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_municipio',$id_municipio, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($result) > 0){
                return true;
            }
            else return "No se pudo obtener el municipio";
        }
        else return "No se pudo obtener el estado";
    }
    else return "No se pudo obtener el país. E.";
}

?>