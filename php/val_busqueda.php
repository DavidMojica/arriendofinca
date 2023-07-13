<?php
    include('PDOconn.php');
    include('essentials.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sel_arriendo_venta = trim($_POST['sel_arriendo_venta']);
        $sel_categoria = trim($_POST['sel_categoria']);
        $sel_ciudad = trim($_POST['sel_ciudad']);
    
        $ban = true;
        $s_query = "SELECT * FROM tbl_inmueble WHERE id_tipo_inmueble = :sel_categoria AND arriendo_o_venta = :sel_arriendo_venta AND id_municipio_ubicacion = :sel_ciudad";
    
        
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
    
        if (isset($_POST['chk_certificados'])) {
            $s_query .= " AND id_certificado IS NOT NULL";
        }
    
        
        $s_stmt = $pdo->prepare($s_query);
        $s_stmt->bindParam(':sel_arriendo_venta', $sel_arriendo_venta, PDO::PARAM_INT);
        $s_stmt->bindParam(':sel_categoria', $sel_categoria, PDO::PARAM_INT);
        $s_stmt->bindParam(':sel_ciudad', $sel_ciudad, PDO::PARAM_INT);
        $s_stmt->execute();
        $s_result = $s_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/hyf.css">
    <link rel="stylesheet" href="../styles/search.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda | ArriendoFinca</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img">
        </div>
        <div id="div_inicio_sesion">
            <h2>Iniciar sesion</h2>
        </div>
        <a href="../index.php"><input type="button" value="Volver | Realizar nueva búsqueda"></a>
    </header>
    <div id="midpage">
    <h2>Resultados de la busqueda</h2>
        <section id="busqueda_principal" class="busquedas">
            <?php
            if(count($result) > 0){
                foreach($s_result as $row){
                    create_inmoviliario($row, $sel_ciudad);
                }
            } else{
                echo "<p>Sin resultados exactos </p>";
            }
            ?>
            
        </section>
            <h2>Busquedas similares en el departamento</h2>
            <section id="busqueda_relacionada" class="busquedas">
                <?php
                    $query = "SELECT id_estado FROM tbl_municipio WHERE id_municipio = :id_municipio";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(":id_municipio", $sel_ciudad, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $res_estado = $result[0];
                    $id_estado = $res_estado['id_estado'];

                    $query = "SELECT * FROM tbl_inmueble 
                    LEFT JOIN tbl_municipio ON tbl_inmueble.id_municipio_ubicacion = tbl_municipio.id_municipio WHERE id_tipo_inmueble = :id_tipo_inmueble 
                    AND arriendo_o_venta = :id_aov and tbl_municipio.id_estado = :id_estado";


                    if (isset($_POST['chk_certificados'])) {
                        $query .= " AND id_certificado IS NOT NULL";
                    }

                    if (isset($_POST['chk_promocion'])) {
                        $query .= " AND descuento IS NOT NULL";
                    }
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id_tipo_inmueble', $sel_categoria, PDO::PARAM_INT);
                    $stmt->bindParam(':id_aov', $sel_arriendo_venta, PDO::PARAM_INT);
                    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

                    if($stmt->execute()){
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                        if(count($result) > 0 ){
                            foreach($result as $row){
                                if($row['id_municipio_ubicacion'] != $sel_ciudad){
                                    create_inmoviliario($row, $sel_ciudad);
                                }
                            }
                        }
                        else{
                            echo "sin resultados";
                        }
                    }
                ?>
            </section>
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
</script>
</body>
</html>