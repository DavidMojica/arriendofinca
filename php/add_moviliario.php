<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/hyf.css">
    <link rel="stylesheet" href="../styles/add_mov.css">
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="../javascript/essentials.js" defer></script>
    <script src="../javascript/add_moviliario.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <title>Agregar Moviliario | arriendofinca.com</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img">
        </div>
        <?php
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

                $query = "SELECT nombre FROM tbl_usuario where $tp = :user";
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
                echo "<h2>Añadir un moviliario</h2>";
                    echo '<form action="logout.php" method="post">';
                    echo '<input type="submit" value="Cerrar Sesión">';
                    echo '</form>';
                }
                else{
                    echo 'Error al obtener el nombre del usuario.';
                }
            }
    ?>
    </header>

    
    <div id="datos">
            <!-- General -->
    <div class="propiedad_general">
        <div id="paso1" class="add_pasos">
            <p>Registre su inmoviliario en 4 sensillos pasos!</p>
            <p>Paso 1: Información general del inmueble</p>
            <div>
                <span>Mi propiedad es para: </span>
                <select name="sel_arriendo_venta" id="sel_arriendo_venta">
                    <option value="1">Arrendar</option>
                    <option value="2">Vender</option>
                </select>
            </div>
            <div>
                <span>Tipo de propiedad:     </span>
                <select name="add_tipo" id="add_tipo">
                <option value="default">Seleccione una opción...</option>
                <?php
                    include('essentials.php');
                    $result = get_tipos_inmueble();
                    foreach ($result as $row) {
                        echo "<option value='" . $row['id_tipo_inmueble'] . "'>" . $row['tipo_inmueble'] . "</option>";
                    }
                ?>
                </select>
            </div>
                
                <input type="text" id="precio" name="precio" placeholder="Precio" class="custom-input">
                <input type="text" name="direccion" id="direccion" placeholder="Dirección" class="custom-input">
                
        </div>
        
        <!-- selector de ciudades -->
        <div id="paso2" class="add_pasos">
            <p>Paso 2: Ubicación del inmueble</p>
            <span>Seleccione el pais, estado y municipio del inmueble</span>
            <div>
                <span>Pais</span> <br>
                <select name="add_pais" id="add_pais">
                    <option value='defalult'>Seleccione un país...</option>
                    <?php
                    $result = get_paises();
                    foreach($result as $row){
                        echo "<option value='".$row['id_pais']."'>".$row['nombre_pais']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <span>Departamento - Estado</span> <br>
                <select name="add_departamento" id="add_departamento" disabled></select>
            </div>
            <div>
                <span>Municipio</span> <br>
                <select name="add_ciudad" id="add_ciudad" disabled></select>
            </div>
        </div>
    </div>


    <div id="paso3" class="add_pasos">
        <p>Paso 3: Información adicional del inmueble</p>
        <div>
            <span>Area del inmueble (en m2):</span>
            <input type="text" id="area">
        </div>
        <div>
            <span>Número de Habitaciones:</span>
            <input type="text" name="" id="add_habitaciones">
        </div>
        <div>
            <span>Número de baños</span>
            <input type="text" name="" id="add_banos">
        </div>
        <div id="area_tp2">
            <span>Area construida (m2):</span>
            <input type="text" name="" id="add_area_construida">
        </div>
        
        <span>Describa su propiedad</span> <br>
        <!-- finca -->
        <div class="form_prop">
            <h3>Finca</h3>
            <p>Sugerencia para descripcion: Especifique si la finca es de recreo o de produccion, metros cuadrados, habitaciones, si cuenta con parqueadero, wifi, luz, agua etc.. etc..</p>
        </div>
        
        <!-- Cabañas -->
        <div class="form_prop">
            <h3>Cabaña</h3>
            <p>Sugerencia para descripcion: Especifique si es una o varias cabañas, metros cuadrados, habitaciones, si cuenta con parqueadero, wifi, luz, agua etc..</p>
        </div> 

        <!-- Lotes -->
        <div class="form_prop">
            <h3>Lote</h3>
            <p>Sugerencia para descripcion: Especifique si el lote posee acceso a la carretera directamente, si posee vigilancia privada, si tiene acceso a servicios públicos etc...</p>
        </div> 

        <!-- Casas -->
        <div class="form_prop">
            <h3>Casa</h3>
            <p>Sugerencia para descripcion: Especifique si la casa está en una unidad abierta o cerrada o si no pertenece a alguna unidad, si cuenta con servicios, si posee parqueadero, vigilancia y metros cuadrados.</p>
        </div> 

        <!-- Apartamentos -->
        <div class="form_prop">
            <h3>Apartamento</h3>
            <p>Sugerencia para descripcion: Especifique si el Apartamento está en una unidad abierta o cerrada o si no pertenece a alguna unidad, si cuenta con servicios, si posee parqueadero, vigilancia y metros cuadrados.</p>
        </div>

        <!-- Oficina -->
        <div class="form_prop">
            <h3>Oficina</h3>
            <p>Sugerencia para descripcion: Especifique en qué piso se ubica la oficina, si posee vigilancia, servicios publicos, metros cuadrados etc...</p>
        </div>

        <!-- Consultorio -->
        <div class="form_prop">
            <h3>Consultorio</h3>
            <p>Sugerencia para descripcion: Especifique en qué piso se ubica el consultorio, si posee vigilancia, servicios publicos, metros cuadrados etc...</p>
        </div>

        <!-- Hotel -->
        <div class="form_prop">
            <h3>Hotel</h3>
            <p>Sugerencia para descripcion: Especifique el tamaño aproximado del cuarto, los servicios que el hotel ofrece etc...</p>
        </div>
        <textarea name="prop_description" id="prop_description"></textarea>
        </div>
    </div>
    
        <!-- Imagenes -->
    <div id="paso4" class="add_pasos">
        <div class="add_imgs" >
            <p>Paso 4: Añada imágenes</p>
            <h3>Suba fotos de la propiedad (que no superen los 5mb):</h3>
            
            <input type="file" name="add_fotos" id="add_fotos" class="upload_fotos" multiple>
            <a href="userarea.php"><input type="button" value="Cancelar"></a>
            <input type="button" value="Registrar propiedad" id="btn_regis_prop">
        </div>
    </div>
    
</div>
<div id="cont_buttons">
        <input type="button" value="Atrás" id="atras" class="bnt_n">
        <input type="button" value="Siguiente" id="siguiente" class="bnt_n">
    </div>

    
    

</body>
</html>