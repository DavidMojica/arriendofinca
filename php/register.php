<?php
    #Importar el archivo de conexion a la bd
    include('PDOconn.php');
    include('essentials.php');

    #verifica si el método de solicitud es "POST"
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        #Variables provenientes de JQuery Ajax en register.js
        $nombre           = trim($_POST['nombre']);
        $documento        = trim($_POST['documento']);
        $tipo_documento   = trim($_POST['tipo_documento']);
        $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
        $email            = trim($_POST['email']);
        $pass             = trim($_POST['contraseña']);
        $celular          = trim($_POST['celular']);
        $auth_whatsapp    = trim($_POST['auth_whatsapp']);
        $pais             = trim($_POST['pais']);
        $departamento     = trim($_POST['departamento']);
        $ciudad           = trim($_POST['ciudad']);
        #Variables de php
        $errors           = [];
        $warnings         = [];
        #
        $id_municipio = 0;


        #-------------Validaciones para cada campo--------------#
        #Campos vacíos: Todos
        #Longitud.
        #Que no exista en la base de datos: cedula, celular, correo.

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
            $query = "SELECT * FROM tbl_usuario where documento = :valor";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('valor',$documento, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($result) > 0){
                $errors[] = "Ya hay un usuario con su cédula registrado.<br>"; 
            }
        }

        #Que el tipo de documento esté en la base de datos
        if(empty($tipo_documento)){
            $errors[] = "Tipo de documento obligatorio. <br>";
        }
        else if(strlen($tipo_documento) != 1){
            $errors[] = "Longitud de tipo de documento no prevista. <br>";
        }
        else if($tipo_documento != 1 && $tipo_documento != 2){
            $errors[] = "Tipo de documento desconocido<br>";
        }

        #email -Si la expresión regular coincide con el formato del correo electrónico, se considera válido
        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email)){
            // Return Error - Invalid Email 
            $errors[] = "Email no valido";
        }else{
            $query = "SELECT email FROM tbl_usuario WHERE email = :valor";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('valor', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(count($result) > 0){
                $errors[] = "Ya hay un usuario con este e-mail registrado. <br>"; 
            }
        }

        #Contraseña
        if(empty($pass)){
            $errors[] = "La pass es obligatoria. <br>";
        }
        else if(strlen($pass) <= 7){
            $errors[] = "La pass es demasiado corta. <br>";
        }
        else if(strlen($pass) >= 21){
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
                $query = "SELECT celular FROM tbl_usuario WHERE celular = :valor";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam('valor', $celular, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($result) > 0){
                    $errors[] = "Ya hay un usuario con este celular registrado. <br>"; 
                }
            }
        }

        #Check whatsapp
        if($auth_whatsapp == 1 && strlen($celular) == 0){
                $warnings[] = "n";
                $w_response = "Marcó la casilla de whatsApp sin haber proporcionado un número <br>Por favor vuelve al paso 3 e ingresa un número o desmarca la casilla de autorizar whatsapp.";
            }

        #Parse fecha de nacimiento
        $fecha_nacimiento = date('Y-m-d', strtotime($fecha_nacimiento));

        # País - Verificar que el país existe en la base de datos
        $query = "SELECT id_pais FROM tbl_pais WHERE nombre_pais = :valor";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('valor', $pais, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) == 0){
            $errors[] = "Su país no se encuentra registrado.<br>"; 
        }
        else{
            $row = $result[0]; # Obtener el resultado como una fila
            $id_pais   = $row['id_pais'];
            # Departamento - Verificar que el estado existe en la base de datos
            $query = "SELECT id_estado FROM tbl_estado WHERE nombre_estado = :valor AND id_pais = :valor2";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('valor', $departamento, PDO::PARAM_STR);
            $stmt->bindParam('valor2', $id_pais, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);     
            if(count($result) == 0){
                $errors[] = "Su departamento/estado no se encuentra registrado.<br>"; 
            }
            else{
                $row = $result[0]; # Obtener el resultado como una fila
                $id_estado = $row['id_estado'];
             
                # Verificar que la ciudad sí existe en la base de datos
                $query = "SELECT id_municipio FROM tbl_municipio WHERE nombre_municipio = :valor AND id_estado = :valor2 AND id_pais = :valor3";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam('valor', $ciudad, PDO::PARAM_STR);
                $stmt->bindParam('valor2', $id_estado, PDO::PARAM_INT);
                $stmt->bindParam('valor3', $id_pais, PDO::PARAM_INT);
                $stmt->execute();
                 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

                if(count($result) == 0){
                    $errors[] = "Su municipio no se encuentra registrado.<br>"; 
                }
                else{
                    $row = $result[0]; # Obtener el resultado como una fila
                    $id_municipio = $row['id_municipio'];
                }
            }
        }

        if(empty($errors) && empty($warnings)){            
            $hash = md5(rand(0,1000));
            $pass_hash = rand(1000,5000);
            $hash = md5($pass_hash);
            $cantidad_propiedades = 0;
            $indice_confianza = 0;
            $active = 0;
            
            
            
            try{
                // utilizamos consultas preparadas con marcadores de posición (:nombre) en lugar de incrustar directamente los val ores en la cadena de consulta. 
                // Luego, vinculamos los valores a los marcadores de posición utilizando el método bindParam() para asegurarnos de que los valores se pasen de manera segura 
                // y se eviten problemas de seguridad como la inyección de SQL.
                //----Creacion del email de confirmacion-----//

                $para    = $email;
                $asunto  = "Registro | Verificacion | arriendofinca.com";
                $mensaje = '
                Gracias por registrarte! 
                Su cuenta ha sido creada, puede iniciar sesión con las siguientes credenciales después de haber activado su cuenta presionando la URL a continuación.

                ------------------------ 
                Email: '.$email.' 
                Password: '.$pass.' 
                ------------------------ 
                Tenga en cuenta que también podra ingresar con su documento.

                Haga clic en este enlace para activar su cuenta:
                http://localhost/arriendofinca/php/verify.php?email='.$email.'&hash='.$hash.'
                
                ';
                try{
                    $query = "INSERT INTO tbl_usuario(documento, nombre, tipo_documento, id_municipio_residencia, pass, email, celular, whatsapp, fecha_nacimiento, cantidad_propiedades, indice_confianza, hash, active) VALUES (:documento, :nombre, :tipo_documento, :id_municipio, :pass, :email, :celular, :whatsapp, :fecha_nacimiento, :cantidad_propiedades, :indice_confianza, :hashx, :active)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':documento', $documento, PDO::PARAM_INT);
                    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(':tipo_documento', $tipo_documento, PDO::PARAM_INT);
                    $stmt->bindParam(':id_municipio', $id_municipio, PDO::PARAM_INT);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
                    $stmt->bindParam(':whatsapp', $auth_whatsapp, PDO::PARAM_INT);
                    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
                    $stmt->bindParam(':cantidad_propiedades', $cantidad_propiedades, PDO::PARAM_INT);
                    $stmt->bindParam(':indice_confianza', $indice_confianza, PDO::PARAM_INT);
                    $stmt->bindParam(':hashx',$hash , PDO::PARAM_STR);
                    $stmt->bindParam(':active',$active , PDO::PARAM_INT);
                    $stmt->execute();

                    return_Response(true, "Su cuenta ha sido creada. Por favor verifíquela haciendo click en el enlace de activación que fue enviado a su correo. Las cuentas no verificadas se borran en 1 día.");
                }
                catch(Exception $e){
                    return_Response(false, "Hubo un error al enviar el correo de verificación. Por favor, verifique su correo o inténtelo de nuevo más tarde.".$e);
                }
            }
            catch(Exception $e){
                return_Response(false,'Error al crear la cuenta: ' . $e->getMessage());
            }           
        }
        #Con errores
        else if(!empty($errors)){
            return_Response_Bad(false, $errors, 0);
        }
        else if(!empty($warnings)){
            return_Response_Bad(false, $w_response, 0);
        }
        else{
            return_Response_Bad(false,"Error desconocido del servidor.",2);
        }
    }
?>