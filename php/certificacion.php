<?php
    include('PDOconn.php');
    include('essentials.php');
    session_start();
    $arr = comprobar_sesion_valida($_SESSION);
    $ban = $arr['ban'];
    $documento_usuario = $arr['documento_usuario'];

    if($ban){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            #Variables provenientes de user area
            $id_inmoviliario = trim($_POST['id_inmoviliario']);
            $query = "SELECT * FROM tbl_inmueble WHERE id_inmueble = :id_moviliario";
            $resultados = get_detalles_propiedad_PDOQUERY($query, $id_inmoviliario);
            $modo             = $resultados['modo'];
            $precio           = $resultados['precio'];
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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="../javascript/essentials.js" defer></script>
    <script src="../javascript/certificacion.js" defer></script>
    <title>Certificacion | ArriendoFinca</title>
</head>
<body>
    <header>

    </header>

    <section>
        <h2>Bienvenido al formulario de certificación</h2>
        <p>Usted ha escogido la propiedad: <?php echo $tipo_inmueble ?> en <?php echo $nombre_municipio ." - ". $nombre_estado. " - ". $nombre_pais ?></p>
        <p>Descripcion del inmueble: </p>
        <p><?php echo $descripcion ?></p>
        <p>Confirmo que he leido los <a href="#">términos y condiciones</a></p>
        <input type="checkbox" name="chk_certificar" id="chk_certificar">
        <input type="button" value="Certificar" id="btn_certificar">
        <input type="hidden" name="id_mov" id="id_mov" value="<?php echo $id_inmoviliario ?>">
        
    </section>

    <footer>

    </footer>
    
</body>
</html>