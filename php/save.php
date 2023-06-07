<?php
    session_start();
    include('PDOconn.php');
    include('essentials.php');
    $tp_user = 0;
    $ban=false;
    if(!isset($_SESSION['username'])){
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

        $query = "SELECT nombre, documento FROM tbl_usuario where $tp = :user";
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
            $nombre_usuario = $row['nombre'];
            $documento = $row['documento'];
            $ban = true;
        }
        else{
            echo 'Error al obtener el nombre del usuario.';
        }
    }

    if($ban){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            #Variables provenientes de ajax desde edit_mov.js
            $edit_tipo_inmueble  = trim($_POST['edit_tipo_inmueble']);
            $edit_arriendo_venta = trim($_POST['edit_arriendo_venta']);
            $edit_precio         = trim($_POST['edit_precio']);
            $edit_direccion      = trim($_POST['edit_direccion']);
            $id_edit_municipio   = trim($_POST['id_edit_municipio']);
            $edit_descripcion    = trim($_POST['edit_descripcion']);
            $id_inmoviliario     = trim($_POST['id_inmoviliario']);

            
            if($id_edit_municipio != 0){
                $query = "UPDATE tbl_inmueble SET id_tipo_inmueble = :edit_tipo_inmueble, 
                arriendo_o_venta = :edit_arriendo_venta, precio = :edit_precio, 
                id_municipio_ubicacion = :edit_id_municipio, direccion = :edit_direccion, 
                descripcion = :edit_descripcion WHERE tbl_inmueble.id_inmueble = :id_inmueble
                AND cedula_dueño = :documento";

                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':edit_tipo_inmueble', $edit_tipo_inmueble, PDO::PARAM_INT);
                $stmt->bindParam(':edit_arriendo_venta', $edit_arriendo_venta, PDO::PARAM_INT);
                $stmt->bindParam(':edit_precio', $edit_precio, PDO::PARAM_INT);
                $stmt->bindParam(':edit_direccion', $edit_direccion, PDO::PARAM_STR);
                $stmt->bindParam(':edit_descripcion', $edit_descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':edit_id_municipio', $id_edit_municipio, PDO::PARAM_INT);
                $stmt->bindParam(':id_inmueble', $id_inmoviliario, PDO::PARAM_INT);
                $stmt->bindParam(':documento', $documento, PDO::PARAM_INT);
                try {
                    if ($stmt->execute()) {
                        return_Response(true, "Inmueble actualizado");
                    } else {
                        return_Response(false, "Error desde el servidor");
                    }
                } catch (PDOException $e) {
                    if ($e->errorInfo[1] === 1452) {
                        return_Response(false, "Error de restricción de clave externa: " . $e->getMessage());
                    } else {
                        return_Response(false, "Error desde el servidor: " . $e->getMessage());
                    }
                } catch (Exception $e) {
                    return_Response(false, "Error desde el servidor: " . $e->getMessage());
                }
            }
            else{
                $query = "UPDATE tbl_inmueble SET id_tipo_inmueble = :edit_tipo_inmueble, 
                arriendo_o_venta = :edit_arriendo_venta, precio = :edit_precio, 
                direccion = :edit_direccion, 
                descripcion = :edit_descripcion WHERE tbl_inmueble.id_inmueble = :id_inmueble 
                AND cedula_dueño = :documento";
               
               $stmt = $pdo->prepare($query);
                $stmt->bindParam(':edit_tipo_inmueble', $edit_tipo_inmueble, PDO::PARAM_INT);
                $stmt->bindParam(':edit_arriendo_venta', $edit_arriendo_venta, PDO::PARAM_INT);
                $stmt->bindParam(':edit_precio', $edit_precio, PDO::PARAM_INT);
                $stmt->bindParam(':edit_direccion', $edit_direccion, PDO::PARAM_STR);
                $stmt->bindParam(':edit_descripcion', $edit_descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':id_inmueble', $id_inmoviliario, PDO::PARAM_INT);
                $stmt->bindParam(':documento', $documento, PDO::PARAM_INT);
                try {
                    if ($stmt->execute()) {
                        return_Response(true, "Inmueble actualizado");
                    } else {
                        return_Response(false, "Error desde el servidor");
                    }
                } catch (PDOException $e) {
                    if ($e->errorInfo[1] === 1452) {
                        return_Response(false, "Usted ha modificado los valores. <br> Recargue la página y vuelva a llenar el formulario.");
                    } else {
                        return_Response(false, "Error desde el servidor: ");
                    }
                } catch (Exception $e) {
                    return_Response(false, "Error desde el servidor: ");
                }
            }
        }
    }
?>