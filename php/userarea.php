<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
<link rel="stylesheet" href="../styles/hyf.css">
<link rel="stylesheet" href="../styles/styles.css">
<link rel="stylesheet" href="../styles/userarea.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="../javascript/essentials.js" defer></script>
    <script src="../javascript/opts_mov.js" defer></script>
    <title>Area del usuario | arriendofinca.com</title>
</head>
<body>
    <header>
    <div class="logo">
        <img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img">
        </div>
        <!-- Si la sesion no está iniciada, redirige al index. Si está iniciada, crea un botón de cerrar sesión y obtiene el nombre del usuario -->
        
        <div class="header-right">
            <ul class="nav">
                <li><img src="../images/icon_user.png" alt="">
                    <ul class="sub_nav">
                        <li><input type="button" value="ayuda" class="button hbt"></li>
                        <?php
                            session_start();
                            include('PDOconn.php');
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
                                echo '<li> <form action="php/logout.php" method="post">';
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
                <?php echo '<li> <p>'. $nombre_usuario .'</p> </li>'; ?>
            </ul>
        </div>
            
    </header>
    <div id="midpage">
        <div id="nav_bar">
            <h2 id="imv_title">Tus inmoviliarios: </h2>
            <div id="nav_bar_controls">
                <a href="add_moviliario.php"><input type="button" value="+ Añadir" class="button hbt"></a>
                <input type="button" value="Filtrar" class="button hbt">
            </div>
        </div>

        <div id="div_inmoviliarios">
            <?php
            include('PDOconn.php');
            include('essentials.php');
            #Cantidad de items que se desea aparecer por pagina
            $numero_items_por_pagina = 9;
            // Obtener el número de página actual
            $page   = isset($_GET['page']) ? $_GET['page'] : 1; #pagina actual
            $offset = ($page - 1) * $numero_items_por_pagina;
            $total_query = "SELECT * FROM tbl_inmueble WHERE cedula_dueño = :documento";
            $t_stmt = $pdo->prepare($total_query);
            $t_stmt->bindParam(':documento', $documento,PDO::PARAM_INT);
            $t_stmt->execute();
            $numero_resultados = $t_stmt->rowCount();

            if($page > 0){
                $query = "SELECT * FROM tbl_inmueble WHERE cedula_dueño = :documento LIMIT :offset, :lim";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':documento', $documento,PDO::PARAM_INT);
                $stmt->bindParam(':offset',$offset, PDO::PARAM_INT);
                $stmt->bindParam(':lim',$numero_items_por_pagina, PDO::PARAM_INT);
                if($stmt->execute()){
                    if($stmt->rowCount() > 0){
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($result) > 0){

                        foreach($result as $row){ 
                            ?>
                            <div class="inmoviliario">
                            <div class="info_inmoviliario"> 
                            <?php   #Obtención de datos -
                                    $id_inmueble          = $row['id_inmueble'];
                                    $prop_area            = $row['area'];
                                    $prop_habitaciones    = $row['habitaciones'];
                                    $prop_banos           = $row['banos'];
                                    $prop_area_construida = $row['area_construida'];?>

                                <?php include('PDOconn.php');
                                    #Datos a ser consultados por normalizacion
                                    $id_tipo_inmueble = $row['id_tipo_inmueble'];
                                    $id_municipio     = $row['id_municipio_ubicacion'];

                                    #Query tipo inmueble
                                    $query = "SELECT tipo_inmueble FROM tbl_tipo_inmueble WHERE id_tipo_inmueble = :id_tipo_inmueble";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->bindParam(':id_tipo_inmueble', $id_tipo_inmueble, PDO::PARAM_INT);

                                    $tipo_inmueble = get_tipo_inmueble_PDOQUERY($stmt);

                                    #Query ciudad
                                    $query = "SELECT nombre_municipio, id_estado FROM tbl_municipio WHERE id_municipio = :id_municipio";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->bindParam(':id_municipio', $id_municipio, PDO::PARAM_INT);

                                    $nombres = get_nombres_ubicacion_PDOQUERY($stmt);
                                    if(!empty($nombres)){
                                        $nombre_municipio = $nombres['nombre_municipio'];
                                        $nombre_estado    = $nombres['nombre_estado'];
                                        $nombre_pais      = $nombres['nombre_pais'];
                                    }else{
                                        $nombre_municipio = "Not obtained";
                                        $nombre_estado    = "Not obtained";
                                        $nombre_pais      = "Not obtained";
                                    }
                            echo "<p class='info_text'> ID del inmueble: ".$id_inmueble." <br> ". $tipo_inmueble. " en <b>". $nombre_municipio ." - ". $nombre_estado. " - ".$nombre_pais."</b></p>";
                                ?>
                                <!-- Imagenes -->
                            <div class="inmoviliario_imgs">
                            <?php
                            $query = "SELECT imagen FROM tbl_imagenes WHERE id_inmueble = :id_inmueble"; 
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_INT);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (count($result) > 0) {
                            echo '<div class="swiper mySwiper">
                                <div class="swiper-wrapper">';

                            foreach ($result as $row) {
                                $image_blob = $row['imagen'];

                                // Obtener información sobre la imagen
                                $image_info = getimagesizefromstring($image_blob);
                                $mime_type = $image_info['mime'];

                                // Obtener la extensión basada en el tipo MIME
                                $extension = image_type_to_extension($image_info[2]);

                                // Establecer el encabezado Content-type según la extensión
                                if ($extension === '.jpg' || $extension === '.jpeg') {
                                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                                } elseif ($extension === '.png') {
                                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                                } elseif ($extension === '.gif') {
                                    echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                                } elseif ($extension === '.jfif') {
                                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                                } else {
                                        echo "La imagen no pudo ser cargada";
                                }
                            }
                            echo '<div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                            </div></div>';
                            } else {
                                echo "El inmueble no posee imágenes";
                            }
                            ?>
                            </div>
                        <div class="div_extra_info">
                            <ul id="info_list">
                            <?php 
                                if($id_tipo_inmueble != 1 && $id_tipo_inmueble != 3){
                                echo  "<li> <span> Área (m2): </span> <p> ".$prop_area." </p> </li> <li> <span> Habitaciones:  </span> <p>".$prop_habitaciones." </p> </li>   <li> <span> Baños: </span> <p> ".$prop_banos." </p> </li> ";
                                }
                                else{
                                    echo  "<li> <span> Área (m2): </span> <p> ".$prop_area." </p> </li> <li> <span> Habitaciones:  </span> <p>".$prop_habitaciones." </p> </li>   <li> <span> Baños: </span> <p> ".$prop_banos." </p> </li> <li> <span> Área construida (m2): </span> <p> ".$prop_area_construida." </p> </li> ";
                                }
                            ?>  
                            </ul>
                        </div>
                            <div class="inmoviliario_controls">
                                <form action="edit_mov.php" method="post">
                                    <input type="hidden" name="id_inmoviliario" value="<?php echo $id_inmueble; ?>">
                                <input type="submit" class="button hbt" value="Editar inmueble">
                                </form>
                                <form action="certificacion.php" method="post">
                                    <input type="hidden" name="id_inmoviliario" value="<?php echo $id_inmueble; ?>">
                                    <input type="submit" class="button hbt" value="Certificar inmueble">
                                </form>
                        <input type="button" class="button hbt" value="Borrar" id="btn_inmoviliario_borrar" onclick="delete_mov(<?php echo $id_inmueble; ?>)">
                            </div> </div></div>
                                <?php
                            } 
                        }
                        else{
                            echo "Usted no posee propiedades registradas";
                        }
                    } # $stmt->rowCount() > 0
                    else{
                        echo "<div> <h2>No se encontraron más resultados...</h2> </div>";
                    }
                    ?></div> </div>
                
            <?php
            } #if execute
            ?>
            <div id="pagination"> 
                <?php #SISTEMA DE PAGINACIÓN
                    $num_paginas = ceil($numero_resultados / $numero_items_por_pagina);
                    $visibleLinks = 5;
                    $halfVisibleLinks = floor($visibleLinks / 2);
                    $startPage = max(1, $page - $halfVisibleLinks);
                    $endPage = min($startPage + $visibleLinks - 1, $num_paginas);
                    
                    if ($num_paginas > 0) {
                        if ($page > 1) {
                            echo "<a href='userarea.php?page=1'><input type='button' value='&laquo; Primera' class='button'></a> ";
                            echo "<a href='userarea.php?page=" . ($page - 1) . "'><input type='button' value='&lt; Anterior' class='button'></a> ";
                        }
                    
                        for ($i = $startPage; $i <= $endPage; $i++) {
                            echo "<a href='userarea.php?page=$i'>  <input type='button' value='$i' class='button'></a>";
                        }
                    
                        if ($page < $num_paginas) {
                            echo "<a href='userarea.php?page=" . ($page + 1) . "'><input type='button' value='Siguiente &gt;' class='button'></a> ";
                            echo "<a href='userarea.php?page=$num_paginas'> <input type='button' value='Última &raquo;' class='button'> </a>";
                        }
                    }                  
                ?>
            </div>
        <?php
            } //if page > 0
            else{
                echo "<div> <h2>Número de página inválido</h2> </div>";
            }
            ?>
        </div>
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

<!-- Initialize Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
    /****SWIPPER**** */
document.addEventListener("DOMContentLoaded", function() {
const swiper = new swiper('.mySwiper', {
  // Optional parameters
  direction: 'vertical',
  loop: true,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  // And if we need scrollbar
  scrollbar: {
    el: '.swiper-scrollbar',
  }
});
});

function goToPrevStep() {
    swiper.slidePrev();
  }

  function goToNextStep() {
    swiper.slideNext();
  }
</script>

</body>
</html>