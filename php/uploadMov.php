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
            $area             = trim($_POST['area']);
            $banos            = trim($_POST['banos']);
            $habitaciones     = trim($_POST['habitaciones']);
            $area_construida  = trim($_POST['area_construida']);
            
            if (isset($_FILES['files'])) {
                $imagenes           = $_FILES['files'];
            }else{
                $imagenes           = [];
            } 

            #variables de php
            $errors           = [];
            $warnings         = [];
            $tamanoMaximo = 5242880; // 5 MB en bytes

            #comprobar la existencia de la ubicacion registrada

            #id_tipo_inmueble
            if($id_tipo_inmueble > 8 && $id_tipo_inmueble <= 0){
                $errors[] = "Tipo de inmueble desconocido. <br>";
            }

            #arriendo o venta
            if($arriendo_venta != 1 && $arriendo_venta != 2){
                $errors[] = "Error al seleccionar arriendo o venta. <br>";
            }

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
            #Area
            if(empty($area)){
                $errors[] = "El campo área es obligatorio (s). <br>";
            }
            else if(!ctype_digit($area)){
                $errors[] = "Se incrustó un dato no numérico en el área (s) <br>";
            }
            #Habitaciones
            if(empty($habitaciones)){
                $errors[] = "El campo nÚmero de habitaciones es obligatorio (s). <br>";
            }
            else if(!ctype_digit($habitaciones)){
                $errors[] = "Se incrustó un dato no numérico en el nÚmero de habitaciones (s) <br>";
            }
            #Baños
            if(empty($banos)){
                $errors[] = "El campo nÚmero de baños es obligatorio (s). <br>";
            }
            else if(!ctype_digit($banos)){
                $errors[] = "Se incrustó un dato no numérico en el nÚmero de baños (s) <br>"; 
            }

            if($id_tipo_inmueble == 1 || $id_tipo_inmueble == 3){
                if(empty($area_construida)){
                    $errors[] = "El campo área construida es obligatorio (s). <br>";
                }
                else if(!ctype_digit($area_construida)){
                    $errors[] = "Se incrustó un dato no numérico en el área construida (s) <br>";
                }
            }

            #Procesar a la base de datos y devolucion a ajax en caso de algún error
            $response = array();
            if (empty($errors) && empty($warnings)) {

            $query = "INSERT INTO tbl_inmueble (id_tipo_inmueble, arriendo_o_venta, precio, id_municipio_ubicacion, direccion, descripcion, cedula_dueño, descuento, id_certificado, area, habitaciones, banos, area_construida) 
        VALUES (:id_tipo_inmueble, :arriendo_o_venta, :precio, :id_municipio_ubicacion, :direccion, :descripcion, :cedula, :descuento, :id_certificado, :area, :habitaciones, :banos, :area_construida)";

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
                $stmt->bindParam(':area', $area, PDO::PARAM_INT);
                $stmt->bindParam(':habitaciones', $habitaciones, PDO::PARAM_INT);
                $stmt->bindParam(':banos', $banos, PDO::PARAM_INT);
                if($id_tipo_inmueble == 1 || $id_tipo_inmueble == 3){
                    $stmt->bindParam(':area_construida', $area_construida, PDO::PARAM_INT);
                } else{
                    $stmt->bindValue(':area_construida', null, PDO::PARAM_NULL);
                }
                $stmt->execute();

                
                $id_inmueble = (int) $pdo->lastInsertId();
                if(!empty($imagenes)){
                    foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
                        $nombreArchivo = $imagenes['name'][$key];
                        $rutaArchivo = $tmp_name;
                        
                        // Verificar el tamaño de la imagen
                        if (filesize($rutaArchivo) <= $tamanoMaximo) {
                            $contenidoArchivo = file_get_contents($rutaArchivo);
                            
                            $query = "INSERT INTO `tbl_imagenes`(`id_inmueble`, `imagen`) VALUES (:id_inmueble, :imagen)";
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_INT);
                            $stmt->bindParam(':imagen', $contenidoArchivo, PDO::PARAM_LOB);
                            $stmt->execute();
                        } 
                    }
                    return_Response(true, "Inmueble creado");
                }else{
                    return_Response(true, "Inmueble creado sin imágenes");
                }
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