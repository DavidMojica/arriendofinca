<?php
    #Comprobador de inicio de sesión
    session_start();
    include('PDOconn.php');
    $tp_user = 0;
    if(!isset($_SESSION['username'])){
        header("Location: loginPage.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es" id="html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../extralibs/ToastNotify/ToastNotify.css">
    <link rel="stylesheet" href="../styles/add_mov.css">
    <link rel="icon" href="../images/ArriendoFincaOld.png">
    <script src="../json/paises.json"></script>
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <!-- <script src="../javascript/login.js" defer></script> -->
    <!-- <script src="../javascript/register.js" defer></script> -->
    <script src="../javascript/essentials.js"></script>
    <script src="../javascript/add_moviliario.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <title>Registrar Propiedad | ArriendoFinca</title>
</head>
<body id="body">
    <section>
        <div id="formulary">
            <div id="div_registro" class="card card--accent">
                <div>
                    <a href="userarea.php"><img src="../images/back.png" alt="back.png" id="btn_login_open"></a>
                </div>
            
                <a href="../index.php" id="ref_img"><img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img"></a>
                <form action="" method="" onsubmit="return false">
                <h2 class="wrap">Inscribe tu propiedad en 4 pasos!</h2>
                    
                <div class="f_content">
                        <div class="paso">
                        <p>Paso 1: Información general del inmueble</p>
                        <div>
                            <span>Mi propiedad es para: </span>
                            <select name="sel_arriendo_venta" id="sel_arriendo_venta" class="formulario">
                                <option value="1">Arrendar</option>
                                <option value="2">Vender</option>
                            </select>
                        </div>
                        <div>
                            <span>Tipo de propiedad:     </span>
                            <select name="add_tipo" id="add_tipo" class="formulario">
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
                        <input type="text" id="precio" name="precio" placeholder="Precio" class="formulario">
                        <input type="text" name="direccion" id="direccion" placeholder="Dirección" class="formulario">
                    </div>
                        
                    <div id="paso_2" class="paso">
                        <p>Paso 2: Ubicación del inmueble</p>
                        <span>Seleccione el pais, estado y municipio del inmueble</span>
                        <div>
                            <span>Pais</span> <br>
                            <select name="add_pais" id="add_pais" class="formulario">
                                <option value='default'>Seleccione un país...</option>
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
                            <select name="add_departamento" id="add_departamento" class="formulario" disabled></select>
                        </div>
                        <div>
                            <span>Municipio</span> <br>
                            <select name="add_ciudad" class="formulario" id="add_ciudad" disabled></select>
                        </div>
                    </div>
                        
                    <div id="paso_3" class="paso">
                        <p>Paso 3: Información adicional del inmueble</p>
                        <div class="double_grid">
                            
                            <span>Area del inmueble (en m2):</span>
                            <input type="text" id="area" class="formulario">
                        
                        
                            <span>Número de Habitaciones:</span>
                            <input type="text" name="" id="add_habitaciones" class="formulario">
                        
                        
                            <span>Número de baños</span>
                            <input type="text" name="" id="add_banos" class="formulario">
                        
                        
                            <span>Area construida (m2):</span>
                            <input type="text" name="" id="add_area_construida" class="formulario">
                        
                        </div>
                        
                        <span>Describa su propiedad</span> <br>
                        <!-- finca -->
                        <div class="form_prop" >
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
                        <textarea name="prop_description" id="prop_description" class="formulario"></textarea>
                    </div>

                    <div id="paso_4" class="paso">
                    <div class="add_imgs" >
                            <p>Paso 4: Añada imágenes</p>
                            <h3>Suba fotos de la propiedad (que no superen los 5mb):</h3>
                            
                            <input type="file" name="add_fotos" id="add_fotos" class="upload_fotos" multiple class="formulario">
                            <a href="userarea.php"><input type="button" value="Cancelar"></a>
                        </div>
                    </div>
                    <div class="pagination">
                        <button id="atras">Atrás</button>
                        <button id="siguiente">Siguiente</button>
                        <button id="btn_regis_prop">Registrar propiedad</button>
                    </div>

                </div>

                </form>
            </div>
        </div>
    </section>
</body>
</html>
