<?php
    //Conexiona la bd
    include('dbconn.php');
    //verifica si el método de solicitud es "POST"
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Variables provenientes de JQuery Ajax en register.js
        $nombre           = trim($_POST['nombre']);
        $documento        = trim($_POST['documento']);
        $tipo_documento   = trim($_POST['tipo_documento']);
        $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
        $email            = trim($_POST['email']);
        $contraseña       = trim($_POST['contraseña']);
        $celular          = trim($_POST['celular']);
        $auth_whatsapp    = trim($_POST['auth_whatsapp']);
        $pais             = trim($_POST['pais']);
        $departamento     = trim($_POST['departamento']);
        $ciudad           = trim($_POST['ciudad']);
        //Variables de php
        $errors           = [];
        $warnings         = [];


        //-------------Validaciones para cada campo--------------//
        #Campos vacíos: Todos
        #Longitud: Nombre, doc, tipo_doc
        #Que no exista en la base de datos: cedula

        #Nombre 
        if(empty($nombre)){
            $errors[] = "El campo nombre es obligatorio. <br>";
        }
        else if(strlen($nombre) < 10){
            $errors[] = "Nombre demasiado corto. <br>";
        }

        #Cedula que no esté en base de datos.
        if(empty($documento)){
            $errors[] = "Campo documento obligatorio. <br>";
        }
        else if(strlen($documento) < 5){
            $errors[] = "Documento demasiado corto. <br>";
        }
        else if(!ctype_digit($documento)){
            $errors[] = "Documento no valido. Se incrustó una letra. <br>";
        }
        else{
            $query = "SELECT * FROM tbl_usuario where documento = $documento";
            $result = mysqli_query($conex, $query);
            if(mysqli_num_rows($result) > 0){
                $errors[] = "Ya hay un usuario con su cédula registrado.<br>"; 
            }
        }

        #Que el tipo de documento esté en la base de datos
        if(empty($tipo_documento)){
            $errors[] = "Tipo de documento obligatorio. <br>";
        }
        else if(strlen($tipo_documento) != 2){
            $errors[] = "Longitud de tipo de documento no prevista. <br>";
        }
        else if($tipo_documento != "cc" && $tipo_documento != "ce"){
            $errors[] = "Tipo de documento desconocido<br>";
        }

        #email - no hay que hacer validaciones con el codigo de verificacion
        $query = "SELECT email FROM tbl_usuario WHERE email = '$email'";
        $result = mysqli_query($conex, $query);
        if(mysqli_num_rows($result) > 0){
            $errors[] = "Ya hay un usuario con este e-mail registrado."; 
        }

        #Contraseña
        if(empty($contraseña)){
            $errors[] = "La contraseña es obligatoria. <br>";
        }
        else if(strlen($contraseña) <= 7){
            $errors[] = "La contraseña es demasiado corta. <br>";
        }
        else if(strlen($contraseña) >= 21){
            $errors[] = "La contraseña es demasiado larga. <br>";
        }

        #Numero celular - Verificar que el dato sea un número realmente
        if(strlen($celular) >= 1){
            if(strlen($celular)>=20){
                $errors[] = "Estás ingresando un numero demasiado largo. <br>";
            }
            else if(!is_numeric($celular)){
                $errors[] = "Ingrese un número válido. <br>";
            }
            else if(!ctype_digit($celular)){
                $errors[] = "celular no valido. Se incrustó una letra. <br>";
            }
            #Verificar si el celular ingresado ya está en la base de datos
            else{
                $query = "SELECT celular FROM tbl_usuario WHERE celular = $celular";
                $result = mysqli_query($conex, $query);
                if(mysqli_num_rows($result) > 0){
                    $errors[] = "Ya hay un usuario con este celular registrado. <br>"; 
                }
            }
        }

        #Check whatsapp
        if($auth_whatsapp == 1 && strlen($celular) == 0){
                $warnings[] = "Marcó la casilla de whatsApp sin haber proporcionado un número <br>Pulsa X para regresar e ingresar un número. <br>Pulsa continuar para ignorar este mensaje.";
        }

        # País - Verificar que el país existe en la base de datos
        $query = "SELECT id_pais FROM tbl_pais WHERE nombre_pais = '$pais'";
        $result = mysqli_query($conex, $query);
        if(mysqli_num_rows($result) == 0){
            $errors[] = "Su país no se encuentra registrado.<br>"; 
        }
        else{
            $row = mysqli_fetch_assoc($result); // Obtener el resultado como una fila
            $id_pais   = $row['id_pais'];
            # Departamento - Verificar que el estado existe en la base de datos
            $query = "SELECT id_estado FROM tbl_estado WHERE nombre_estado = '$departamento' AND id_pais = $id_pais";
            $result = mysqli_query($conex, $query);
            if(mysqli_num_rows($result) == 0){
                $errors[] = "Su departamento/estado no se encuentra registrado.<br>"; 
            }
            else{
                $row = mysqli_fetch_assoc($result); // Obtener el resultado como una fila

                $id_estado = $row['id_estado'];
             
                # Verificar que la ciudad sí existe en la base de datos
                $query = "SELECT * FROM tbl_municipio WHERE nombre_municipio = '$ciudad' AND id_estado = $id_estado AND id_pais = $id_pais";
                $result = mysqli_query($conex, $query);
                if(mysqli_num_rows($result) == 0){
                    $errors[] = "Su municipio no se encuentra registrado.<br>"; 
                }
            }
        }

        $response = array();
        if(empty($errors) && empty($warnings)){
            $response['success'] = true;
            $response['mensaje'] = "Validaciones completadas con éxito.";
            $jsonResponse = json_encode($response);
            header('Content-Type: application/json');
            exit($jsonResponse);
        }
        #Con errores
        else if(!empty($errors)){
            $response['success'] =  false;
            $response['mensaje'] = $errors;
            $response['state']   = 0;
            $jsonResponse = json_encode($response);
            header('Content-Type: application/json');
            exit($jsonResponse);
        }
        else if(!empty($warnings)){
            $response['success'] =  false;
            $response['mensaje'] = $warnings;
            $response['state']   = 1;
            $jsonResponse = json_encode($response);
            header('Content-Type: application/json');
            exit($jsonResponse);
        }
        else{
            $response['success'] =  false;
            $response['mensaje'] = "Error desconocido del servidor.";
            $response['state']   = 2;
            $jsonResponse = json_encode($response);
            header('Content-Type: application/json');
            exit($jsonResponse);
        }
    }
    

?>