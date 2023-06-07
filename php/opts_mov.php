<?php
    #Comprobador de inicio de sesión
    #Comprobamos nuevamente que el usuario tenga la sesión iniciada.
    session_start();
    include('PDOconn.php');
    include('essentials.php');
    $ban     = false;
    $tp_user = 0;
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
        #-------------Verificar que la sesión esté iniciada--------------#
    if($ban){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            #Variables provenientes de JQuery Ajax en opts_mov.js
            $id_moviliario = trim($_POST['id_moviliario']);
            $type          = trim($_POST['type']);

            #--------Verificar que el moviliario sí sea del usuario y no haya alterado el id del moviliario del lado del cliente------#
            $ban           = false;
            
            $query = "SELECT * FROM tbl_inmueble WHERE id_inmueble = :id_inmueble AND cedula_dueño = :cedula";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_inmueble', $id_moviliario, PDO::PARAM_INT);
            $stmt->bindParam(':cedula', $documento, PDO::PARAM_INT);
            if($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($result) > 0){
                    $ban = true;
                }
                else{
                    return_Response(false,"No se logró verificar la veracidad del la informacion del usuario. 1.");
                }
            }
            if($ban){
                #Hacemos una cosa u otra dependiendo del tipo de peticion
                #------Borrar moviliario-----#
                if($type == 0){
                    $query = "DELETE FROM tbl_inmueble WHERE id_inmueble = :id_inmueble AND cedula_dueño = :cedula";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id_inmueble', $id_moviliario, PDO::PARAM_INT);
                    $stmt->bindParam(':cedula', $documento, PDO::PARAM_INT);
                    if($stmt->execute()){
                        return_Response(true,"Se borró el inmueble con éxito.");
                    }
                    else{
                        return_Response(false, "No se logró verificar la veracidad del la informacion del usuario. 2.");
                    }
                }
            }
        }
    }
?>