<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="../javascript/opts_mov.js"></script>
    <title>Area del usuario | arriendofinca.com</title>
</head>
<body>
    <header>
        
        <!-- Si la sesion no está iniciada, redirige al index. Si está iniciada, crea un botón de cerrar sesión y obtiene el nombre del usuario -->
        <?php
        #Comprobador de inicio de sesión
            session_start();
            include('PDOconn.php');
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
                    echo "Bienvenido, $nombre_usuario!";
                    echo '<form action="logout.php" method="post">';
                    echo '<input type="submit" value="Cerrar Sesión">';
                    echo '</form>';
                }
                else{
                    echo 'Error al obtener el nombre del usuario.';
                }
            }
    ?>

        <div id="div_logo">
            <img src="../images/ArriendoFinca.png" alt="Logo" class="logo_largo">
        </div>
    </header>
    <div id="midpage">
        <div id="nav_bar">
            <input type="button" value="- Borrar">
            <a href="add_moviliario.php"><input type="button" value="+ Añadir"></a>
        </div>
        <h2>Tus inmoviliarios: </h2>
        <div id="div_inmoviliarios">
            <?php
            include('PDOconn.php');

            $query = "SELECT * FROM tbl_inmueble WHERE cedula_dueño = :documento";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':documento', $documento,PDO::PARAM_INT);
            if($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($result) > 0){
                    foreach($result as $row){
                        ?>
                        <div class="inmoviliario">
                            <div class="info_inmoviliario"> <p>ID:<b><?php 
                            $id_inmueble = $row['id_inmueble'];
                            $id_inmueble ?></b></p>
                            <?php include('PDOconn.php');
                                #Datos a ser consultados por normalizacion
                                $id_tipo_inmueble = $row['id_tipo_inmueble'];
                                $id_municipio     = $row['id_municipio_ubicacion'];

                                #Query tipo inmueble
                                $query = "SELECT tipo_inmueble FROM tbl_tipo_inmueble WHERE id_tipo_inmueble = :id_tipo_inmueble";
                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':id_tipo_inmueble', $id_tipo_inmueble, PDO::PARAM_INT);

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

                                #Query ciudad
                                $query = "SELECT nombre_municipio, id_estado FROM tbl_municipio WHERE id_municipio = :id_municipio";
                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':id_municipio', $id_municipio, PDO::PARAM_INT);

                                if($stmt->execute()){
                                    $res_nombre_municipio = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if(count($res_nombre_municipio) > 0){
                                        $row_nombre_municipio = $res_nombre_municipio[0];
                                        $nombre_municipio     = $row_nombre_municipio['nombre_municipio'];
                                        $id_estado            = $row_nombre_municipio['id_estado'];

                                        #Query estado
                                        $query = "SELECT nombre_estado FROM tbl_estado WHERE id_estado = :id_estado";
                                        $stmt = $pdo->prepare($query);
                                        $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

                                        if($stmt->execute()){
                                            $res_nombre_estado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            if(count($res_nombre_estado) > 0){
                                                $row_nombre_estado = $res_nombre_estado[0];
                                                $nombre_estado     = $row_nombre_estado['nombre_estado'];

                                                #Imprimimos el resultado
                                                echo "<p class='info_text'> id:".$id_inmueble." ". $tipo_inmueble. " en <b>". $nombre_municipio ." - ". $nombre_estado. "</b></p>";
                                            }
                                        }
                                    }
                                }
                            else {
                                $nombre_municipio = "No se obtuvo el nombre del municipio";
                            }
                            ?>
                        <div class="inmoviliario_imgs">
                            <p>Aqui van las imagenes</p>
                        </div>
                        <div class="inmoviliario_controls">
                            <form action="edit_mov.php" method="post">
                                <input type="hidden" name="id_inmoviliario" value="<?php echo $id_inmueble; ?>">
                                <input type="submit" value="Editar inmoviliario">
                            </form>
                            <input type="button" value="Borrar inmoviliario" id="btn_inmoviliario_borrar" onclick="delete_mov(<?php echo $id_inmueble; ?>)">
                        </div>
                            <?php
                        } 
                    }
                    else{
                        echo "Usted no posee propiedades registradas";
                    }
                ?>     </div> 
            </div>
                <?php
                }
            ?>
           

        </div>
        <input type="button" value="Atras">
        <input type="button" value="Siguiente">
    </div>
    <footer>
        <div id="footer_left">
            <h4>William Montoya</h4>
            <b>Gerente</b>
            <p><b>Celular: </b>3006159008</p>
            <p>Info@arriendofinca.com</p>   
        </div>
        <div id="footer_right">
            <h4>David Mojica</h4>
            <b>Co-Gerente - Desarrollador</b>
            <p>davidmojicav@gmail.com</p>
        </div>
    </footer>
</body>
</html>