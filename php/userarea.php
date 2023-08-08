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
    <link rel="icon" href="../images/ArriendoFincaOld.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
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
        <a href="../index.php"><img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img"></a>
        </div>
        <!-- Si la sesion no está iniciada, redirige al index. Si está iniciada, crea un botón de cerrar sesión y obtiene el nombre del usuario -->
        <div>
            <a id="menu-icon" class="menu-icon" onclick="onMenuClick()">
                <i class="fa fa-bars fa-3x" id="var-icon"></i>
            </a>
            <div id="navigation-bar" class="nav-bar">
                <!-- <a href="#" class="button">Contáctenos</a>
                <a href="#" class="button">Sobre nosotros</a>
                <a href="#" class="button">Cotiza tu pagina web</a>
                <a href="#" class="button">Publica tu inmueble</a> -->
            </div>
        </div>

        <div class="header-right">
            <ul class="nav">
                <li><img src="../images/icon_user.png" alt="">
                    <ul class="sub_nav">
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
                                #echo '<li><a href="#"><input type="button" value="Editar perfil" class="button hbt"></a></li>';
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
                <?php 
                if(!isset($_SESSION['username'])){
                    echo '<li> <p class="username">Usuario anónimo</p> </li>';
                }else{
                    echo '<li> <p class="username">'. $nombre_usuario .'</p> </li>';
                }
                ?>
            </ul>
        </div>
        

            
    </header>
    <div id="midpage">
        <div class="publicidad">

        </div>

        <div>
        <div id="nav_bar">
            <h2 id="imv_title">Tus inmoviliarios: </h2>
            <div id="nav_bar_controls">
                <ul class="nav">
                    <li><a href="add_moviliario.php"><input type="button" value="+ Añadir" class="button hbt"></a></li>
                    <li><a href="userarea.php"><input type="button" value="Borrar filtros" class="button hbt"></a></li>
                    <li><input type="button" value="Filtrar" class="button hbt">
                        <ul>
                            <div id="filter_div">
                                <h3>Filtrar</h3>
                                <form action="userarea.php" method="GET">
                                    <select name="f_tipo" id="filter_tipo">
                                        <option value="null">Tipo inmoviliario...</option>
                                        <option value="1">Fincas</option>
                                        <option value="2">Cabañas</option>
                                        <option value="3">Lotes</option>
                                        <option value="4">Casas</option>
                                        <option value="5">Apartamentos</option>
                                        <option value="6">Oficinas</option>
                                        <option value="7">Consultorios</option>
                                        <option value="8">Hotel</option>
                                    </select>

                                    <select name="f_aov" id="filter_tipo">
                                        <option value="null">Tipo transaccion...</option>
                                        <option value="1">Arriendo</option>
                                        <option value="2">Venta</option>
                                    </select>

                                    <div id="div_filter_lugar">
                                        <div id="div_pais" class="busquedas it1">
                                            <select name="f_pais" id="sel_pais">
                                                <option value="null">Seleccione un pais...</option>        
                                                <?php
                                                include_once('essentials.php');
                                                    $result = get_paises();
                                                    foreach($result as $row){
                                                        echo "<option value='".$row['id_pais']."'>".$row['nombre_pais']."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div id="div_estado" class="busquedas it1">
                                            <select name="f_estado" id="sel_estado" disabled>
                                                <option value="null">Seleccione un departamento...</option>
                                            </select>
                                        </div>
                                        <div id="div_ciudad" class="busquedas it1" disabled>
                                            <select name="f_ciudad" id="sel_ciudad" disabled>
                                            <option value="null">Seleccione una ciudad...</option>
                                            </select>
                                        </div>
                                    </div>

                                    
                                    <input type="text" name="f_id" id="" placeholder="ID del inmueble">
                                    
                                    <br>
                                    <div id="div_check_certificado" class="busquedas">
                                    <label class="checkbox">
                                        <input type="checkbox" name="chk_certificados" id="chk_certificados">
                                        <span class="checkmark"></span>
                                        <span>Sólo inmuebles certificados</span>
                                    </label>
                                    <div>
                                    <input type="submit" value="Filtrar" class="button">

                                    </div>
                                </div>
                                </form>
                            </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div id="div_inmoviliarios">
            <?php
            include('PDOconn.php');
            #Cantidad de items que se desea aparecer por pagina
            $numero_items_por_pagina = 9;
            $filter_complement = "";
            $join_complement   = "";
            $f_tipo = "";
            $f_aov  = "";
            
            $f_pais = "";
            $f_estado = "";
            $f_ciudad = "";
            $f_id     = "";

            if(isset($_GET['f_tipo']) && $_GET['f_tipo'] !== 'null'){
                 
                $f_tipo = $_GET['f_tipo'];
                $filter_complement .= " AND id_tipo_inmueble = :f_tipo ";
            }
            
            if(isset($_GET['f_aov']) && $_GET['f_aov'] !== 'null'){
                 
                $f_aov         = $_GET['f_aov'];
                $filter_complement .= " AND arriendo_o_venta = :f_aov ";
            }

            if(isset($_GET['f_ciudad']) && $_GET['f_ciudad'] !== 'null'){
                 
                $f_ciudad        = $_GET['f_ciudad'];
                $filter_complement .= " AND id_municipio_ubicacion = :f_ciudad ";
            }

            if(isset($_GET['f_id']) && $_GET['f_id'] !== ''){
                 
                $f_id        = $_GET['f_id'];
                $filter_complement .= " AND id_inmueble = :f_id ";
            }

            if(isset($_GET['chk_certificados']) && $_GET['chk_certificados'] == 'on'){
                 
                $chk_certificados       = $_GET['chk_certificados'];
                $filter_complement .= " AND id_certificado IS NOT NULL ";
            }

            
            // Obtener el número de página actual
            $page   = isset($_GET['page']) ? $_GET['page'] : 1; #pagina actual
            $offset = ($page - 1) * $numero_items_por_pagina;
            $total_query = "SELECT * FROM tbl_inmueble WHERE cedula_dueño = :documento". $filter_complement ."";
            $t_stmt = $pdo->prepare($total_query);
            $t_stmt->bindParam(':documento', $documento,PDO::PARAM_INT);
            if($f_tipo !== ""){
                $t_stmt->bindParam(':f_tipo', $f_tipo, PDO::PARAM_INT);
            }
            if($f_aov !== ""){
                $t_stmt->bindParam(':f_aov',$f_aov,PDO::PARAM_INT);
            }
            if($f_ciudad !== ""){
                $t_stmt->bindParam(':f_ciudad',$f_ciudad,PDO::PARAM_INT);
            }
            if($f_id !== ""){
                $t_stmt->bindParam(':f_id',$f_id,PDO::PARAM_INT);
            }
            $t_stmt->execute();
            $numero_resultados = $t_stmt->rowCount();



            if($page > 0){
                $query = "SELECT * FROM tbl_inmueble WHERE cedula_dueño = :documento ". $filter_complement ." LIMIT :offset, :lim";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':documento', $documento,PDO::PARAM_INT);
                $stmt->bindParam(':offset',$offset, PDO::PARAM_INT);
                $stmt->bindParam(':lim',$numero_items_por_pagina, PDO::PARAM_INT);
                if($f_tipo !== ""){
                    $stmt->bindParam(':f_tipo', $f_tipo, PDO::PARAM_INT);
                }
                if($f_aov !== ""){
                    $stmt->bindParam(':f_aov',$f_aov,PDO::PARAM_INT);
                }
                if($f_ciudad !== ""){
                    $stmt->bindParam(':f_ciudad',$f_ciudad,PDO::PARAM_INT);
                }
                if($f_id !== ""){
                    $stmt->bindParam(':f_id',$f_id,PDO::PARAM_INT);
                }
                


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
                                    $prop_area_construida = $row['area_construida'];
                                    $descripcion          = $row['descripcion'];
                                    
                            ?>

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
                            echo '</div><div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                            </div>';
                            } else {
                                echo "El inmueble no posee imágenes";
                            }
                            ?>
                            </div> <!--end imgs-->
                            <?php  
                            echo '<div class="info_hidden">';
                            
                                echo "<span>Descripcion:</span> <p>". $descripcion ."</p>";
                                echo '<ul class="grid_list info_list">';
                                    $prop_area_construida = (strlen($prop_area_construida)>0) ? $prop_area_construida : "No especificado";
                                    if($id_tipo_inmueble != 1 && $id_tipo_inmueble != 3){
                                        echo  "<li> <span> Área (m2): </span>  </li> <p> ".$prop_area." </p> <li> <span> Habitaciones:  </span>  </li> <p>".$prop_habitaciones." </p> <li> <span> Baños: </span> </li> <p> ".$prop_banos." </p>";
                                    }
                                    else{
                                        echo  "<li> <span> Área (m2): </span>  </li> <p> ".$prop_area." </p> <li> <span> Área construida (m2): </span> </li> <p> ".$prop_area_construida." </p> <li> <span> Habitaciones:  </span>  </li> <p>".$prop_habitaciones." </p> <li> <span> Baños: </span> </li>  <p> ".$prop_banos." </p> ";
                                    }
                                    echo '<li><form action="edit_mov.php" method="post">
                                              <input type="hidden" name="id_inmoviliario" value="'.$id_inmueble.'">
                                              <input type="submit" class="button_2 hbt" value="Editar">
                                        </form></li>';
                                        echo '<li><input type="button" class="button_2 hbt" value="Borrar" id="btn_inmoviliario_borrar" onclick="delete_mov('.$id_inmueble.')"></li>';
                                        echo '</ul>';
                                        echo '</div>';
                                        ?>
                                
                            </div>
                            <?php 
                                $color = "#fff";

                                if($id_tipo_inmueble == 1)
                                    $color = "#E0F2D8";
                                else if($id_tipo_inmueble == 2)
                                    $color = "#F6E5E3";
                                else if($id_tipo_inmueble == 3)
                                    $color = "#FFF5DA";
                                else if($id_tipo_inmueble == 4)
                                    $color = "#CAD3D2";
                                else if($id_tipo_inmueble == 5)
                                    $color = "#B8F1D8";
                                else if($id_tipo_inmueble == 6)
                                    $color = "#D2F4F9";
                                else if($id_tipo_inmueble == 7)
                                    $color = "#a1a6ca";
                                else if ($id_tipo_inmueble == 8)
                                    $color = "#e6e2b4";
                                else if($id_tipo_inmueble == 9)
                                    $color = "#7d9599";
                                else if($id_tipo_inmueble == 10)
                                    $color = "#8a7d99";
                                else if ($id_tipo_inmueble == 11)
                                    $color == "#99967d";
                                else $color = "#fff";

                                echo '<input type="hidden" value="'. $color .'" class="color">';
                            ?>
                            
                            <input type="button" value="Detalles" class="button_2 btn_expandir">
                             
                            

                    </div>
                    <?php
                            } 
                        }
                        else{
                            echo "<h2>Usted no posee propiedades registradas</h2>";
                        }
                    } # $stmt->rowCount() > 0
                    else{
                        echo "<div> <h2 class='non_results'>No se encontraron más resultados...</h2> </div>";
                    }
                    ?></div> 
                
            <?php
            } #if execute
            echo '<div id="pagination"> ';
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
                
            echo '</div>';
        
            } //if page > 0
            else{
                echo "<div> <h2>Número de página inválido</h2> </div>";
            }
            ?>
        
            <footer>
                <!-- Sección Izquierda del Pie de Página -->
                <div id="footer_left">
                    <h4>William Montoya</h4>
                    <b>Gerente</b>
                    <p><b>Celular: </b>3006159008</p>
                    <p>Info@arriendofinca.com</p>   
                </div>
                <div>
                    <h4>SOMOS EMPRESA DE DESARROLLO</h4>
                    <p>Cotice sus sitios web con nosotros <br>
                       Solicite informacion en cualquiera <br>
                       los correos dados.
                    </p>
                </div>

                <!-- Sección Derecha del Pie de Página -->
                <div id="footer_right">
                    <h4>David Mojica</h4>
                    <b>Cogerente - Desarrollador</b>
                    <p><a href="http://davidmojica.42web.io/"><b>Visita mi sitio aqui!</b></a></p>
                    <p>davidmojicav@gmail.com</p>
                </div>
            </footer>
        </div>

        <div class="publicidad">

        </div>
    </div>
    



</body>
</html>