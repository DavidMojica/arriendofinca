<?php 
    #Importar el archivo de conexion a la bd
    include('PDOconn.php');
    #verifica si el método de solicitud es "POST"
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        #Variables provenientes de Ajax en login.js
        $user      = trim($_POST['user']);
        $pass      = trim($_POST['pass']);
        $user_type = trim($_POST['user_type']);
        #Variables de php
        $errors = [];
        $queryError = "error";
        $ban = false;

        #-----------Validaciones para cada campo--------------#
        #Campos vacíos.
        #Longitud.
        #Confirmar concordancia

        #Nombre
        if(empty($user)){
            $errors[] = "El campo de usuario es obligatorio. <br>";
        }
        else if(empty($pass)){
            $errors[] = "El campo contraseña es obligatorio <br>";
        }

        if(empty($errors)){
            if($user_type == 1){
                $consulta ="SELECT documento FROM tbl_usuario WHERE email = :valor AND pass = :valor2";
                $ban = true;
            }
            else if($user_type == 2){
                $consulta = "SELECT documento FROM tbl_usuario WHERE documento = :valor AND pass = :valor2";
                $ban = true;
            }
            else $ban = false;

            if($ban){ 
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':valor', $user);

                if ($user_type === 2) 
                    $stmt->bindParam(':valor2', $pass, PDO::PARAM_INT);
                 else 
                    $stmt->bindParam(':valor2', $pass, PDO::PARAM_STR);
                
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($result) == 1){
                    #Se devolvió 1 fila.
                    $response['success'] = true;
                    $response['mensaje'] = "Usuario encontrado en la Base de datos.";
                    $jsonResponse = json_encode($response);
                    header('Content-Type: application/json');
                    exit($jsonResponse);
                }        
                else if(count($result) == 0)
                    #No se devolvieron filas.
                    $queryError = "Usuario no encontrado.";
                else if(count($result) <= 2)
                    #Se devolvieron múltiples files.
                    $queryError = "Multiples coincidencias. Contacte con la administración.";      
                else
                    #Error desconocido.
                    $queryError = "Error desconocido devuelto en la consulta";   

                if($queryError !== "error"){
                    $response['success'] = false;
                    $response['mensaje'] = $queryError;
                    $jsonResponse = json_encode($response);
                    header('Content-Type: application/json');
                    exit($jsonResponse);
                }
                else{
                    $response['success'] = false;
                    $response['mensaje'] = $queryError;
                    $jsonResponse = json_encode($response);
                    header('Content-Type: application/json');
                    exit($jsonResponse);
                }
            }
        }
    }
?>