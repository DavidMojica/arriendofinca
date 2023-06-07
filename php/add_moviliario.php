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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="../javascript/essentials.js" defer></script>
    <script src="../javascript/add_moviliario.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <title>Agregar Moviliario | arriendofinca.com</title>
</head>
<body>
    <h2>Bienvenido al formulario añadir un moviliario</h2>
    <p>Por favor llenar la siguiente información</p>
    <form action="">
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
    </form>
    <!-- General -->
    <div class="propiedad_general">
        <select name="sel_arriendo_venta" id="sel_arriendo_venta">
            <option value="1">Arriendo</option>
            <option value="2">Venta</option>
        </select>
        <input type="text" id="precio" name="precio" placeholder="Precio">
        <input type="text" name="direccion" id="direccion" placeholder="Dirección">
        <!-- Hacer el selector de ciudades -->
    
    </div>

    <!-- finca -->
    <div id="add_finca" class="form_prop">
        <form action="">
            <h3>Finca</h3>
            <span>Tipo de finca</span>
            <select name="tipo_finca" id="tipo_finca">
                <option value="1">Finca de recreo</option>
                <option value="2">Finca de producción</option>
            </select>

            <span>Seleccione el pais, estado y municipio del inmueble</span>
            <span>pais</span>
            <select name="add_pais" id="add_pais">
                <option value='defalult'>Seleccione un país...</option>
                <?php
                $result = get_paises();
                foreach($result as $row){
                    echo "<option value='".$row['id_pais']."'>".$row['nombre_pais']."</option>";
                }
                ?>
            </select>
            <select name="add_departamento" id="add_departamento" disabled></select>
            <select name="add_ciudad" id="add_ciudad" disabled></select>


        </form>
    </div>
    <!-- Imagenes -->
    <div class="add_imgs">
        <span>Proporcione información sobre la propiedad</span>
        <textarea name="prop_description" id="prop_description"></textarea>
        <h3>Suba fotos de la propiedad (que no superen los 5mb):</h3>
        <input type="file" name="add_fotos" id="add_fotos" class="upload_fotos" multiple>
        <input type="button" value="Registrar propiedad" id="btn_regis_prop">
    </div>


    <!-- Cabaña -->
    <!-- <div id="add_cabania">
        <form action="">
                <h3>Cabaña</h3>
                <span>Cantidad de cabañas</span>
                <input type="number" name="cantidad_cab" id="cantidad_cab" min="0" max="50">
        
                <h3>Suba fotos de la(s) cabaña(s)</h3>
        </form>
    </div>

    <div id="gen_servicios">
        <h3>Seleccione los servicios que posee el inmueble</h3>
        <span>Luz:</span>
        <input type="checkbox" name="luz" id="luz">
        <span>Agua:</span>
        <input type="checkbox" name="agua" id="agua">
        <span>Wifi:</span>
        <input type="checkbox" name="wifi" id="wifi">
        <span>Vigilancia:</span>
        <input type="checkbox" name="vigilancia" id="vigilancia">
        <span>Parqueadero:</span>
        <input type="checkbox" name="parqueadero" id="parqueadero">
    </div> -->

    


</body>
</html>