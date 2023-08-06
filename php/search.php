<?php
    include('PDOconn.php');
    include('essentials.php');
    $ban_c = false;
    $chk_c = "";
    $sel_arriendo_venta = trim($_GET['av']);
    $sel_categoria      = trim($_GET['categoria']);
    $sel_ciudad         = trim($_GET['ciudad']);
    $sel_departamento   = trim($_GET['estado']);
    $sel_pais           = trim($_GET['pais']);

    if(isset($_GET['chk_certificados'])){
        $chk_c = $_GET['chk_certificados'];
        if($chk_c == "on"){
            $ban_c = true;
        }
    }

    #Cantidad de items que se desea aparecer por pagina
    $numero_items_por_pagina = 6;
    // Obtener el número de página actual
    $page   = isset($_GET['page']) ? $_GET['page'] : 1; #pagina actual
    $s_page = isset($_GET['s_page']) ? $_GET['s_page'] : 1; #pagina actual
    $offset = ($page - 1) * $numero_items_por_pagina;
    $ban    = true;
    $ban_2  = true;
    $s_result = array();

    #---Errores---#
    $error_1 = "<div> <h2>Número de página inválido</h2> </div>";
    $error_2 = "<div> <h2> Sin resultados </h2> </div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/hyf.css">
    <link rel="stylesheet" href="../styles/search.css">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <link rel="icon" href="../images/ArriendoFincaOld.png">
  <!-- Link Swiper's CSS -->
  <!-- Swiper JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js" defer></script>  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
  <script src="../javascript/toastNotifyTP1.js" defer></script>
  <script src="../javascript/essentials.js" defer></script>
  <script src="../javascript/search.js" defer></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda | ArriendoFinca</title>
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
                        <?php
                            session_start();
                            include('PDOconn.php');
                            $tp_user = 0;
                            if(!isset($_SESSION['username'])){
                                echo "<li><a href='loginPage.php'><input type='button' value='Iniciar Sesión' class='button hbt'></a> </li>";
                                echo "<li><a href='../index.php'><input type='button' value='Volver al home' class='button hbt'></a> </li>";
                            }
                            else{
                                echo "<li><a href='userarea.php'><input type='button' value='Area del usuario' class='button hbt'></a> </li>";
                                echo "<li><a href='../index.php'><input type='button' value='Volver al home' class='button hbt'></a> </li>";
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
                                    // echo '<li><a href="#"><input type="button" value="Editar perfil" class="button hbt"></a></li>';
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
        <div class="results_div">
            <div class="mid_top">
                <a href="../index.php"><input type="button" value="Volver" class="button" id="back_btn"></a>
                <h2>Resultados de la busqueda</h2>
                <div></div>
            </div>
            <section id="busqueda_principal" class="busquedas">
            <?php
        
            $complement = "";
            
            #-----Validaciones---#
        
            #---Arriendo o Venta---#
            $query = "SELECT * FROM tbl_arriendo_o_venta WHERE id_aov = :id_aov";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_aov', $sel_arriendo_venta, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) <= 0) {
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                header("Location: ../index.php");
                exit();
            }
        
            #---Categoría---#
            $query = "SELECT * FROM tbl_tipo_inmueble WHERE id_tipo_inmueble = :id_categoria";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_categoria', $sel_categoria, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) <= 0) {
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                header("Location: ../index.php");
                exit();
            }

            #---Ciudad---#
            $query = "SELECT * FROM tbl_municipio WHERE id_municipio = :id_municipio";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_municipio', $sel_ciudad, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) <= 0) {
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                header("Location: ../index.php");
                exit();
            }
        
            if ($ban_c) {
                $complement .= " AND id_certificado IS NOT NULL";
            }

            if($page > 0){
                $ts_query = "SELECT * FROM tbl_inmueble WHERE id_tipo_inmueble = :sel_categoria AND arriendo_o_venta = :sel_arriendo_venta AND id_municipio_ubicacion = :sel_ciudad ". $complement ."";
                $ts_stmt = $pdo->prepare($ts_query);
                $ts_stmt->bindParam(':sel_arriendo_venta', $sel_arriendo_venta, PDO::PARAM_INT);
                $ts_stmt->bindParam(':sel_categoria', $sel_categoria, PDO::PARAM_INT);
                $ts_stmt->bindParam(':sel_ciudad', $sel_ciudad, PDO::PARAM_INT);
                $ts_stmt->execute();
                $numero_resultados = $ts_stmt->rowCount();
                $s_result = $ts_stmt->fetchAll(PDO::FETCH_ASSOC);

                $s_query = "SELECT * FROM tbl_inmueble WHERE id_tipo_inmueble = :sel_categoria AND arriendo_o_venta = :sel_arriendo_venta AND id_municipio_ubicacion = :sel_ciudad ". $complement ." LIMIT :offset, :lim";

                $s_stmt = $pdo->prepare($s_query);
                $s_stmt->bindParam(':sel_arriendo_venta', $sel_arriendo_venta, PDO::PARAM_INT);
                $s_stmt->bindParam(':sel_categoria', $sel_categoria, PDO::PARAM_INT);
                $s_stmt->bindParam(':sel_ciudad', $sel_ciudad, PDO::PARAM_INT);
                $s_stmt->bindParam(':offset',$offset, PDO::PARAM_INT);
                $s_stmt->bindParam(':lim',$numero_items_por_pagina, PDO::PARAM_INT);
                $s_stmt->execute();
                $s_result = $s_stmt->fetchAll(PDO::FETCH_ASSOC);

                if($page > 0){
                    if(is_array($s_result) && count($s_result) > 0){
                        foreach($s_result as $row){
                            create_inmoviliario($row);
                        }
                    } else{
                        echo $error_2;
                    }
                }else{
                    echo $error_1;
                }
            }else{
                $ban = false;
            }
                            
            echo '</section>';
            echo '<div class="pagination">'; 
               if($numero_resultados > 0){
                 #SISTEMA DE PAGINACIÓN
                 $num_paginas = ceil($numero_resultados / $numero_items_por_pagina);
                 $visibleLinks = 5;
                 $halfVisibleLinks = floor($visibleLinks / 2);
                 $startPage = max(1, $page - $halfVisibleLinks);
                 $endPage = min($startPage + $visibleLinks - 1, $num_paginas);
                 
                 if ($page > 0) {
                     if ($page > 1) {
                         echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=1&s_page=$s_page'>  <input type='button' value='&laquo; Primera' class='button'></a> ";
                         echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=" . ($page - 1) . "&s_page=$s_page'>  <input type='button' value='&lt; Anterior' class='button'></a> ";
                     }
                 
                     for ($i = $startPage; $i <= $endPage; $i++) {
                         echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$i&s_page=$s_page'>  <input type='button' value='$i' class='button'></a> ";
                     }
                 
                     if ($page < $num_paginas) {
                         echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=" . ($page +1) . "&s_page=$s_page'>  <input type='button' value='Siguiente &gt' class='button'></a> ";
                         echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$num_paginas&s_page=$s_page'>  <input type='button' value='Última &raquo;' class='button'></a> ";
                     }
                 }
               }
            echo '</div>';
            ?>
        </div>


        <div class="results_div">
        <div>
            <h2>Busquedas similares en el departamento</h2></div>
            <section id="busqueda_relacionada" class="busquedas">
                <?php
                    #pagination
                    $s_page   = isset($_GET['s_page']) ? $_GET['s_page'] : 1;
                    $s_offset = ($s_page -1) * $numero_items_por_pagina;


                    $query = "SELECT id_estado FROM tbl_municipio WHERE id_municipio = :id_municipio";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(":id_municipio", $sel_ciudad, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $res_estado = $result[0];
                    $id_estado = $res_estado['id_estado'];

                    $sim_query = "SELECT * FROM tbl_inmueble
                    WHERE id_tipo_inmueble = :id_tipo_inmueble 
                    and arriendo_o_venta != :sel_arriendo_venta and id_municipio_ubicacion = :mpio";

                    $stmt = $pdo->prepare($sim_query);
                    $stmt->bindParam(':id_tipo_inmueble', $sel_categoria, PDO::PARAM_INT);
                    $stmt->bindParam(':sel_arriendo_venta', $sel_arriendo_venta, PDO::PARAM_INT);
                    $stmt->bindParam(':mpio', $sel_ciudad, PDO::PARAM_INT);

                    $stmt->execute();
                    $s_numero_resultados = $stmt->rowCount();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                    if($s_numero_resultados > 0 ){
                        foreach($result as $row){
                            create_inmoviliario($row);
                        }
                    }

                    $t_query = "SELECT * FROM tbl_inmueble 
                    LEFT JOIN tbl_municipio ON tbl_inmueble.id_municipio_ubicacion = tbl_municipio.id_municipio WHERE id_tipo_inmueble = :id_tipo_inmueble 
                    and tbl_municipio.id_estado = :id_estado and id_municipio_ubicacion != :id_dif_mpio";

                    $stmt = $pdo->prepare($t_query);
                    $stmt->bindParam(':id_tipo_inmueble', $sel_categoria, PDO::PARAM_INT);
                    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                    $stmt->bindParam(':id_dif_mpio', $sel_ciudad, PDO::PARAM_INT);

                    $stmt->execute();
                    $s_numero_resultados += $stmt->rowCount();
                    
                    $query = "SELECT * FROM tbl_inmueble 
                    LEFT JOIN tbl_municipio ON tbl_inmueble.id_municipio_ubicacion = tbl_municipio.id_municipio WHERE id_tipo_inmueble = :id_tipo_inmueble 
                    and tbl_municipio.id_estado = :id_estado and id_municipio_ubicacion != :id_dif_mpio LIMIT :offset, :lim";


                    if (isset($_POST['chk_certificados'])) {
                        $query .= " AND id_certificado IS NOT NULL";
                    }

                    if (isset($_POST['chk_promocion'])) {
                        $query .= " AND descuento IS NOT NULL";
                    }
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id_tipo_inmueble', $sel_categoria, PDO::PARAM_INT);
                    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                    $stmt->bindParam(':offset',$s_offset, PDO::PARAM_INT);
                    $stmt->bindParam(':lim',$numero_items_por_pagina, PDO::PARAM_INT);
                    $stmt->bindParam(':id_dif_mpio', $sel_ciudad, PDO::PARAM_INT);

                    if($s_page > 0){
                        if($stmt->execute()){
                        
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                            if($s_numero_resultados > 0 ){
                                foreach($result as $row){
                                    create_inmoviliario($row);
                                }
                            }else if($s_numero_resultados == 0){
                                echo $error_2;
                            }
                        }
                    }else{
                        echo $error_1;
                    } 
                echo '</section>';
                echo '<div class="pagination">';
                    if($s_numero_resultados > 0){
                        $s_num_paginas = ceil($s_numero_resultados / $numero_items_por_pagina);
                        $s_visibleLinks = 5;
                        $s_halfVisibleLinks = floor($s_visibleLinks / 2);
                        $s_startPage = max(1, $s_page - $s_halfVisibleLinks);
                        $s_endPage = min($s_startPage + $s_visibleLinks - 1, $s_num_paginas);
                        
                        if ($s_page > 0) {
                            if ($s_page > 1) {
                                echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$page&s_page=1'>  <input type='button' value='&laquo; Primera' class='button'></a> ";
                                echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$page&s_page=" . ($s_page - 1) . "'>  <input type='button' value='&lt; Anterior' class='button'></a> ";
                            }
                        
                            for ($i = $s_startPage; $i <= $s_endPage; $i++) {
                                echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$page&s_page=$i'>  <input type='button' value='$i' class='button'></a> ";
                            }
                        
                            if ($s_page < $s_num_paginas) {
                                echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$page&s_page=" . ($s_page +1) . "'>  <input type='button' value='Siguiente &gt' class='button'></a> ";
                                echo "<a href='search.php?av=$sel_arriendo_venta&categoria=$sel_categoria&pais=$sel_pais&estado=$sel_departamento&ciudad=$sel_ciudad&page=$page&s_page=$s_num_paginas'>  <input type='button' value='Última &raquo;' class='button'></a> ";
                            }
                        }  
                    }
                echo '</div>';
                ?>
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
                    <p><b>Celular: </b>3197750000</p>
                    <p>davidmojicav@gmail.com</p>
                </div>
            </footer>
        </div>
        <div class="publicidad">

        </div>
    </div>
</body>
</html>