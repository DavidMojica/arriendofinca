<?php
    include('PDOconn.php');
    include('essentials.php');
    session_start();

    $ban     = false;
    $tp_user = 0;
    if(!isset($_SESSION['username'])){ #Si la sesion no está iniciada se redirige al usuario al index.
        header("Location: ../index.html");
        exit;
    }
    else{
        $user = $_SESSION['username'];
        if(ctype_digit($user)){
            $tp = "documento";
            $tp_user = 1;
        }
        else{
            $tp = "email";
            $tp_user = 2;
        }

        $query = "SELECT documento FROM tbl_usuario where $tp = :user";
        $stmt = $pdo->prepare($query);

        if($tp_user == 1){
            $stmt->bindParam(':user', $user, PDO::PARAM_INT);
        }
        else{
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) > 0){
            $row = $result[0];
            $documento_usuario = $row['documento'];
            $ban = true;
        }
    }
    #-------------Verificar que la sesión esté iniciada--------------#
    if($ban){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            #Variables provenientes de JQuery Ajax en add_moviliario.js
            $id_tipo_inmueble = trim($_POST['id_tipo_inmueble']);
            $arriendo_venta   = trim($_POST['arriendo_o_venta']);
            $precio           = trim($_POST['precio']);
            $id_municipio     = trim($_POST['id_municipio']);
            $direccion        = trim($_POST['direccion']);
            $descripcion      = trim($_POST['descripcion']);
            $pais             = trim($_POST['pais']);
            $estado           = trim($_POST['estado']);
            $ciudad           = trim($_POST['ciudad']);
            
            // if (isset($_FILES['files'])) {
            //     $files            = $_FILES['files'];
            // } else {
            //     // Manejar el caso en que no se enviaron archivos
            // }
            #variables de php
            $errors           = [];
            $warnings         = [];
    
            #comprobar la existencia de la ubicacion registrada

            #id_tipo_inmueble
            if($id_tipo_inmueble > 8 && $id_tipo_inmueble <= 0){
                $errors[] = "Tipo de inmueble desconocido. <br>";
            }

            #arriendo o venta
            if($arriendo_venta != 1 && $arriendo_venta != 2){
                $errors[] = "Error al seleccionar arriendo o venta. <br>";
            }

            // foreach ($files['tmp_name'] as $key => $tmp_name) {
            //     $nombreArchivo = $files['name'][$key];
            //     $rutaArchivo = $tmp_name;
            
            //     $contenidoArchivo = file_get_contents($rutaArchivo);
            
            //     $query = "INSERT INTO `tbl_imagenes`(`id_inmueble`, `imagen`) VALUES (:id_inmueble, :imagen)";
            //     $stmt = $pdo->prepare($query);
            //     $stmt->bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_STR);
            //     $stmt->bindParam(':imagen', $contenidoArchivo, PDO::PARAM_LOB);
            //     $stmt->execute();
            // } 

            #precio
            if(empty($precio)){
                $errors[] = "El campo precio es obligatorio (s). <br>";
            }
            else if(!ctype_digit($precio)){
                $errors[] = "Se incrustó un dato no numérico en el precio (s) <br>";
            }
            
            #id_municipio
            $query = "SELECT id_municipio FROM tbl_municipio WHERE id_municipio = :id_municipio";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('id_municipio', $id_municipio, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($result) == 0){
                $errors[] = "id de municipio no encontrado en la base de datos. (s)  <br>";
            }
            #direccion
            if(empty($direccion)){
                $errors[] = "Debe proporcionar una dirección <br>";
            }

            #descripcion
            if(empty($descripcion)){
                $warnings[] = "¿Desea continuar sin proporcionar una descripción de su inmueble? <br>";
            }
            else if(strlen($descripcion) > 250){
                $errors[] = "La descripción del inmueble debe de ser concisa y no superar los 250 carácteres.  <br>";
            }

            #verificar que las imagenes no pesen mas de 5mb
            // foreach ($files['tmp_name'] as $key => $tmp_name) {
            //     $nombreArchivo = $files['name'][$key];
            //     $tamañoArchivo = $files['size'][$key];

            //     $maxFileSize = 5 * 1024 * 1024; // Tamaño máximo permitido en bytes (5 MB)

            //     if ($tamañoArchivo > $maxFileSize) {
            //         $errors[] = 'El archivo "' . $nombreArchivo . '" supera el tamaño máximo permitido de 5 MB.';
            //     } 
            // }


            #Procesar a la base de datos y devolucion a ajax en caso de algún error
            $response = array();
            if (empty($errors) && empty($warnings)) {

                $query = "INSERT INTO tbl_inmueble (id_tipo_inmueble, arriendo_o_venta, precio, id_municipio_ubicacion, direccion, descripcion, cedula_dueño, descuento, id_certificado) 
                        VALUES (:id_tipo_inmueble, :arriendo_o_venta, :precio, :id_municipio_ubicacion, :direccion, :descripcion, :cedula, :descuento, :id_certificado)";

                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id_tipo_inmueble', $id_tipo_inmueble, PDO::PARAM_INT);
                $stmt->bindParam(':arriendo_o_venta', $arriendo_venta, PDO::PARAM_INT);
                $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
                $stmt->bindParam(':id_municipio_ubicacion', $id_municipio, PDO::PARAM_INT);
                $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':cedula', $documento_usuario, PDO::PARAM_INT);
                $stmt->bindValue(':descuento', null, PDO::PARAM_NULL);
                $stmt->bindValue(':id_certificado', null, PDO::PARAM_NULL);
                if($stmt->execute()){
                    $id_inmueble = $pdo->lastInsertId();
                    return_Response(true, "Su inmoviliario ha sido creado con éxito.".$id_inmueble);
                }else 
                    return_Response(false,"No se pudo crear el inmobiliario.");
                

                // if ($stmt->execute()) {
                //     // Obtener el ID del inmueble recién insertado
                //     $id_inmueble = $pdo->lastInsertId();
                //     echo $id_inmueble;

                //     foreach ($files['tmp_name'] as $key => $tmp_name) {
                //         $nombreArchivo = $files['name'][$key];
                //         $rutaArchivo = $tmp_name;

                //         $contenidoArchivo = file_get_contents($rutaArchivo);

                //         $query = "INSERT INTO `tbl_imagenes`(`id_inmueble`, `imagen`) VALUES (:id_inmueble, :imagen)";
                //         $stmt = $pdo->prepare($query);
                //         $stmt->bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_INT);
                //         $stmt->bindParam(':imagen', $contenidoArchivo, PDO::PARAM_LOB);
                //         $stmt->execute();
                //     }

                //     $response['success'] = true;
                //     $response['mensaje'] = "Su inmoviliario ha sido creado con éxito.";
                // } else {
                //     $errors[] = "No se pudo insertar el registro en la tabla tbl_inmueble";
                //     $response['success'] = false;
                //     $response['mensaje'] = $errors;
                //     $response['state'] = 0;
                // }
            
            }
            else if(!empty($errors))
                return_Response_Bad(false, $errors, 0);
            else if(!empty($warnings))
                return_Response_Bad(false, $warnings, 1);
            else{
                return_Response_Bad(false, "Error desconocido del servidor.", 2);
            }
        }
    }
    else{
        header("Location: ../index.html");
        exit;
    }
?>