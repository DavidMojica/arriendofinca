<?php 
    #Importar el archivo de conexion a la bd
    include('PDOconn.php');
    include('essentials.php');
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
                #sanear y validar los datos de entrada
                $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
                $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

                $stmt->bindParam(':valor', $user);

                if ($user_type === 2) 
                    $stmt->bindParam(':valor2', $pass, PDO::PARAM_INT);
                 else 
                    $stmt->bindParam(':valor2', $pass, PDO::PARAM_STR);
                
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($result) == 1){
                    #Se devolvió 1 fila.
                    try{
                        session_start();
                        $_SESSION['username'] = $user;
                        return_Response(true, $result);
                    }
                    catch(Exception $e){
                        return_Response(false, $e);
                    }
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
                    return_Response(false, $queryError);
                }
                else{
                    return_Response(false, $queryError);
                }
            }
        }
    }
?>