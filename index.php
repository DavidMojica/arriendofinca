<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="extralibs/ToastNotify/ToastNotify.css">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/hyf.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="images/ArriendoFincaOld.png">
    <script src="extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="javascript/toastNotifyTP1.js" defer></script>
    <script src="javascript/essentials.js" defer></script>
    <script src="javascript/index.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <title>Home | ArriendoFinca</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="images/ArriendoFinca.png" alt="Logo" class="logo_img"></a>
        </div>
        <a id="menu-icon" class="menu-icon" onclick="onMenuClick()">
            <i class="fa fa-bars fa-3x" id="var-icon"></i>
        </a>
        <div id="navigation-bar" class="nav-bar">
            <a href="#" >Contáctenos</a>
            <a href="#" >Sobre nosotros</a>
            <a href="#" >Cotiza tu pagina web</a>
            <a href="#" >Publica tu inmueble</a>
        </div>
        
        <div class="header-right">
            
            <ul class="nav">
                <li id="clicker"><img src="images/icon_user.png" alt="" >
                <ul>
                <?php
                    session_start();
                    include('php/PDOconn.php');
                    $tp_user = 0;
                    if(!isset($_SESSION['username'])){
                        echo "<li><a href='php/loginPage.php'><input type='button' value='Iniciar Sesión' class='button hbt'></a> </li>";
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
                            // echo "Bienvenido, $nombre_usuario!";
                            echo '<li> <form action="php/i_logout.php" method="post">';
                            echo '<input type="submit" value="Cerrar Sesión" class="button hbt">';
                            echo '</form> </li>';

                            echo "<li> <a class='a_ini_sesion' href='php/userarea.php'><input type='button' class='button hbt' value='Area del usuario'></a> </li>";
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
        <nav class="busqueda" id="busqueda">
            <div class="registrar">
            </div> 
            <form action="php/search.php?page=1&s_page=1" method="GET" id="search_form" onsubmit="return checkValues()">
                <h2>Busca tu inmueble ideal</h2>
                <div id="busquedas_1">
                    <div id="div_select" class="busquedas it1">
                        <span>Busco para</span>
                        <select name="av" id="sel_arriendo_venta">
                            <option value="1">Arrendar</option>
                            <option value="2">Comprar</option>
                        </select>
                    </div>
                    <div id="div_categoria" class="busquedas it1">
                        <span>Categoría</span>
                        <select name="categoria" id="sel_categoria">
                            <option value="1">Fincas</option>
                            <option value="2">Cabañas</option>
                            <option value="3">Lotes</option>
                            <option value="4">Casas</option>
                            <option value="5">Apartamentos</option>
                            <option value="6">Oficinas</option>
                            <option value="7">Consultorios</option>
                            <option value="8">Hotel</option>
                        </select>
                    </div>
                </div>
                
                <div class="busquedas2">
                    <p><b>Seleccione la ubicacion del inmueble</b></p>
                    <div id="div_pais" class="busquedas it1">
                        <select name="pais" id="sel_pais">
                            <option value="default">Seleccione un pais...</option>        
                            <?php
                                include('php/essentials.php');
                                $result = get_paises();
                                foreach($result as $row){
                                    echo "<option value='".$row['id_pais']."'>".$row['nombre_pais']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div id="div_estado" class="busquedas it1">
                        <select name="estado" id="sel_estado" disabled>
                            <option value="default">Seleccione un departamento...</option>
                        </select>
                    </div>
                    <div id="div_ciudad" class="busquedas it1" disabled>
                        <select name="ciudad" id="sel_ciudad" disabled>
                        <option value="default">Seleccione una ciudad...</option>
                        </select>
                    </div>
                </div>
                
                <div id="div_check_certificado" class="busquedas">
                    <label class="checkbox">
                        <input type="checkbox" name="chk_certificados" id="chk_certificados">
                        <span class="checkmark"></span>
                        <span>Buscar sólo inmuebles certificados por arriendofinca.com</span>
                    </label>
                </div>
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="s_page" value="1">

                <input type="submit" value="Buscar" class="button">
            </form>
        </nav>

        
        


        <div id="div_info">
            <div class="points" id="point1">
                <p class="point_text"><b class="point_title"> Tu finca gratis en internet! </b> <br><br><b>Registre y publique su finca facil y rápido <a href="php/add_moviliario.php">aqui</a></b></p>
            </div>
            <div class="points" id="point2">
                <p class="point_text"><b class="point_title">¿No ves tu país, estado o ciudad?</b> <br><br><b>Manda un correo a info@arriendofinca.com <br>
                Confirmaremos tu información y pronto estará disponible :)</b></p>
            </div>
            <div class="points" id="point3">
                <p class="point_text"> <b class="point_title">¿Ya eres usuario?</b> <br><br> <b>Ingresa <a href="php/loginPage.php">aquí</a></b></p>
            </div>
            <div class="points" id="point4">
                <p class="point_text"><b class="point_title"> Amplia variedad </b> <br><br><b>Consigue las mejores fincas a tus medidas, con excelentes precios</b>
            </div>
            <div class="points" id="point5">
                <p class="pont_text"> <b class="point_title"> Los inmoviliarios certificados se venden hasta un 50% más rápido </b></p>
            </div>
            <div class="points" id="point6">
                <p class="point_text"><b class="point_title"> Sé tu propio administrador </b> <br><br> <b>Da a conocer tu inmoviliario rápidamente</b></p>
            </div>
            <div class="points" id="point7">
                <p class="point_text"><b class="point_title"> No alquiles a ciegas </b> <br><br> <b>Los inmuebles certificados por arriendofinca están totalmente comprobados :)</b></p>
            </div>
            <div class="points" id="point8"> 
                <p class="point_text"> <b class="point_title"> 0 Restricciones </b> <br><br> <b>Publica tus inmuebles sin preocupaciones, nosotros no limitamos tu cantidad de anuncios :)</b></p>
            </div>
        </div>
        <!-- 285 x 227 px -->
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
        </div>

        <div class="publicidad">
        </div>
    </div>
</body>
</html>
