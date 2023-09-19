<?php
#Comprobador de inicio de sesión
#Comprobamos nuevamente que el usuario tenga la sesión iniciada.
include('PDOconn.php');
include('essentials.php');
session_start();
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

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/hyf.css">
    <link rel="stylesheet" href="../styles/edit_mov.css">
    <link rel="icon" href="../images/ArriendoFincaOld.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="../javascript/essentials.js" defer></script>
    <script src="../javascript/edit_mov.js" defer></script>
    <title>Editando Moviliario | ArriendoFinca</title>
</head>
<body>
<header>
    <div class="logo">
        <a href="../index.php"><img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img"></a>
        </div>
        <!-- Si la sesion no está iniciada, redirige al index. Si está iniciada, crea un botón de cerrar sesión y obtiene el nombre del usuario -->
        
        <div class="header-right">
            <ul class="nav">
                <li><img src="../images/icon_user.png" alt="">
                    <ul class="sub_nav">
                        <li><input type="button" value="ayuda" class="button hbt"></li>
                        <?php
                            include('PDOconn.php');
                            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                $id_inmoviliario = trim($_POST['id_inmoviliario']);
                                $query = "SELECT * FROM tbl_inmueble WHERE id_inmueble = :id_moviliario";
                                $resultados = get_detalles_propiedad_PDOQUERY($query, $id_inmoviliario);
                                $modo             = $resultados['modo'];
                                $precio           = $resultados['precio'];
                                $area             = $resultados['area'];
                                $habitaciones     = $resultados['habitaciones'];
                                $banos            = $resultados['banos'];
                                $area_construida  = $resultados['area_construida'];
                                $direccion        = $resultados['direccion'];
                                $descripcion      = $resultados['descripcion'];
                                $tipo_inmueble    = $resultados['tipo_inmueble'];     
                                $ubicacion        = $resultados['ubicacion'];
                                $nombre_municipio = $ubicacion['nombre_municipio'];
                                $nombre_estado    = $ubicacion['nombre_estado'];
                                $nombre_pais      = $ubicacion['nombre_pais'];
                                $id_estado        = $ubicacion['id_estado'];
                                $id_pais          = $ubicacion['id_pais'];
                                
                                    
                            }
                            $tp_user = 0;
                            if(!isset($_SESSION['username'])){
                                header("Location: ../index.php");
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
                                    #Obtencion de datos
                                    $row = $result[0];
                                    $nombre_usuario = $row['nombre'];
                                    $documento = $row['documento'];
                                echo '<li><a href="../index.php"><input type="button" class="button hbt" value="Volver al Home"></a></li>';
                                echo '<li><a href="#"><input type="button" value="Editar perfil" class="button hbt"></a></li>';
                                echo '<li> <form action="logout.php" method="post">';
                                echo '<input type="submit" value="Cerrar Sesión" class="button hbt">';
                                echo '</form> </li>';
                                }
                                else{
                                    echo 'Error al obtener el nombre del usuario.'; 
                                }
                            }
                        ?>  
                        
                    </ul>
                </li>
                <?php echo '<li> <p class="username">'. $nombre_usuario .'</p> </li>'; ?>
            </ul>
        </div>
    </header>

    <section>
        <div class="publicidad">

        </div>

        <div>
        <div class="card">
        <h2>Editar Moviliario</h2>
        <p>ID del inmueble: <b id="id_inmoviliario"><?php echo $id_inmoviliario; ?></b> (no se puede editar el id del inmueble)</p>
        <hr>
        <div class="grid_doble">
            
            <label for="edit_tipo_inmueble">Tipo de inmueble: </label>
            <select name="edit_tipo_inmueble" id="edit_tipo_inmueble" class="formulario">
                <option value="default">Seleccione una opción...</option>
                <?php
                    $result = get_tipos_inmueble();
                    foreach ($result as $row) {
                        echo "<option value='" . $row['id_tipo_inmueble'] . "' " . ($row['tipo_inmueble'] == $tipo_inmueble ? 'selected' : '') . ">" . $row['tipo_inmueble'] . "</option>";
                    }
                ?>
            </select>
            
            <label for="edit_arriendo_venta">Tipo de gestion</label>
            <select name="edit_arriendo_venta" id="edit_arriendo_venta" class="formulario">
                <option value="1" <?php echo ($modo == "Arriendo") ? 'selected' : ''; ?>>Arriendo</option>
                <option value="2" <?php echo ($modo == "Venta") ? 'selected' : ''; ?>>Venta</option>
            </select>
            <label for="edit_precio">Precio: </label>
            <input type="text" name="edit_precio" id="edit_precio" class="formulario" value="<?php echo $precio ?>"> 

        </div>
        <hr>
        <input type="button" value="Cambiar municipio de ubicación" id="btn_edit_municipio" class="button">
        <div id="div_edit_location">
            <p>Ubicacion actual:</p>
            <p><?php echo $nombre_municipio ?> - <?php echo $nombre_estado ?> -<?php  echo $nombre_pais?></p>
            <select name="edit_pais" id="edit_pais" disabled class="formulario">
                <option value="default">Seleccione un país...</option>
                <?php
                $result = get_paises();
                foreach ($result as $row) {
                    echo "<option value='" . $row['id_pais'] . "'>" . $row['nombre_pais'] . "</option>";
                }
                ?>
            </select><br>

            <select name="edit_estado" id="edit_estado" disabled class="formulario" class="formulario">
                <option value='default'>Seleccione un estado...</option>
            </select><br>

            <select name="edit_municipio" id="edit_municipio" disabled class="formulario" class="formulario">
                <option value="default">Seleccione un municipio...</option>
            </select><br>

            <p>Confirmo los cambios en la ubicacion del inmueble</p>
            <input type="checkbox" name="confirm_new_location" id="confirm_new_location">
        </div>
        <hr>
        <p>Detalles del inmueble</p>
        <div class="grid_doble">
            <span>Area (mts2)</span>
            <input type="text" name="edit_area" id="edit_area" class="formulario" value="<?php echo $area ?>"> 
            <?php   
                if($tipo_inmueble != 1 && $tipo_inmueble != 3){
                    echo '<span>Area construida (mts2)</span>
                    <input type="text" name="edit_area_construida" class="formulario" id="edit_area_construida" value="'. $area_construida .'"> ';
                }
            ?>
            <span>Habitaciones</span>
            <input type="text" name="edit_habitaciones" id="edit_habitaciones" class="formulario" value="<?php echo $habitaciones ?>">
            
            <span>Baños</span>
            <input type="text" name="edit_banos" id="edit_banos" class="formulario" value="<?php echo $banos ?>">
            <p>Dirección</p>
            <input type="text" name="edit_direccion" id="edit_direccion" class="formulario" value="<?php echo $direccion ?>">

        </div>

        <hr>
        <p>Descripción del inmueble</p>
        <textarea name="edit_descripcion" class="formulario" id="edit_descripcion"><?php echo $descripcion ?></textarea>
        <hr>
        <h3>Imágenes</h3>
         <!-- Imagenes -->
         <div class="inmoviliario_imgs">
            <?php
            $query = "SELECT id_imagen, imagen FROM tbl_imagenes WHERE id_inmueble = :id_inmueble"; 
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_inmueble', $id_inmoviliario, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
            echo '<div class="swiper mySwiper">
                <div class="swiper-wrapper">';

            foreach ($result as $row) {
                $image_blob = $row['imagen'];
                $id_imagen  = $row['id_imagen'];

                // Obtener información sobre la imagen
                $image_info = getimagesizefromstring($image_blob);
                $mime_type = $image_info['mime'];

                // Obtener la extensión basada en el tipo MIME
                $extension = image_type_to_extension($image_info[2]);

                // Establecer el encabezado Content-type según la extensión
                if ($extension === '.jpg' || $extension === '.jpeg') {
                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'>
                     <div>
                         <input type='button' value='Borrar foto' class='button' onclick=borrar_imagen($id_imagen)>
                     </div></div>";
                } elseif ($extension === '.png') {
                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'>
                <div>
                         <input type='button' value='Borrar foto' class='button' onclick=borrar_imagen($id_imagen)>
                     </div></div>";
                } elseif ($extension === '.gif') {
                    echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'>
                    <div>
                         <input type='button' value='Borrar foto' class='button' onclick=borrar_imagen($id_imagen)>
                     </div></div>";
                } elseif ($extension === '.jfif') {
                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'>
                <div>
                         <input type='button' value='Borrar foto' class='button' onclick=borrar_imagen($id_imagen)>
                     </div></div>";
                } else {
                        echo "La imagen no pudo ser cargada";
                }

            }
            echo '</div><div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            </div>';
            } else {
                echo "El inmueble no posee imágenes";
            }
            ?>
        </div> <!--end imgs-->
        
        <p>Añadir fotos</p>
        <input type="file" name="edit_add_fotos" id="edit_add_fotos" multiple>
        <hr>
        <a href="userarea.php"><input type="button" value="Cancelar" class="button"></a>
        <input type="button" value="Guardar cambios" id="edit_save" class="button" onclick="save(<?php echo $id_inmoviliario ?>)">
        </div>
        </div>

        <div class="publicidad">

        </div>
    </section>
    <footer>
            <div id="footer_left">
                <h4>William Montoya</h4>
                <b>Gerente</b>
                <p><b>Celular: </b>3006159008</p>
                <p>Arriendofinca@gmail.com</p>   
            </div>
            <div id="footer_right">
                <h4>David Mojica</h4>
                <b>Co-Gerente - Desarrollador</b>
                <p>davidmojicav@gmail.com</p>
            </div>
        </footer>

</body>
</html>