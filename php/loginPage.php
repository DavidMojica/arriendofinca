<?php
    #Comprobador de inicio de sesión
    session_start();
    include('PDOconn.php');
    $tp_user = 0;
    if(isset($_SESSION['username'])){
        header("Location: ../index.php");
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
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="icon" href="../images/ArriendoFincaOld.png">
    <script src="../json/paises.json"></script>
    <script src="../extralibs/ToastNotify/ToastNotify.js" defer></script>
    <script src="../javascript/toastNotifyTP1.js" defer></script>
    <script src="../javascript/login.js" defer></script>
    <script src="../javascript/register.js" defer></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <title>Login | ArriendoFinca</title>
</head>
<body id="body">
    <section>
        <div id="formulary">
            <div id="div_login">
                <div class="card card--accent">
                    <a href="../index.php" id="ref_img"><img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img"></a>
                    <h2><svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-coffee" href="#icon-coffee" />
                    </svg>Bienvenido nuevamente.</h2>
                    <input type="text" placeholder="Documento/Correo" id="log_user" class="formulario"> <br>
                    <input type="password" placeholder="Contraseña" id="log_pass" class="formulario">
                    <div class="button-group">
                        <button type="reset">Olvidé mi contraseña</button> <br>
                        <a href="../index.php"><button id="back_login">Regresar</button></a> 
                        <button id="log_btn">Ingresar</button> <br>
                        <p>¿No tienes una cuenta?</p>
                        <button id="btn_reg_open">Regístrate</button>
                        
                    </div>  
                </div>  
            </div> 
                  

            <div id="div_registro" class="card card--accent">
                <div>
                    <img src="../images/back.png" alt="back.png" id="btn_login_open">
                </div>
            
                <a href="../index.php" id="ref_img"><img src="../images/ArriendoFinca.png" alt="Logo" class="logo_img"></a>
                <h2>Crea tu cuenta en 4 simples pasos</h2>
                <form action="" method="" onsubmit="return false">

                <div class="div_info_p1">
                        
                    <div class="paso">
                        <h3>Paso 1: Proporcione sus datos</h3>
                        <div class="reg_item">
                            <input type="text" name="reg_nombre" id="reg_nombre" class="formulario" placeholder="Nombre Completo">
                        </div>
                        <div class="reg_item">
                            <select name="reg_tipo_documento" id="reg_tipo_documento" class="formulario">
                                <option value="default">Seleccione su tipo de documento...</option>
                                <option value="1">Cedula de ciudadanía</option>
                                <option value="2">Cedula de extranjería</option>
                            </select> <br>
                            <input type="text" name="reg_documento" id="reg_documento" class="formulario" placeholder="Documento"> <br>
                        </div>

                        <div class="reg_item">
                            <label for="reg_fecha">Fecha de nacimiento</label> <br>
                            <input type="date" name="reg_fecha" id="reg_fecha" class="formulario">
                        </div>
                    </div>
                        
                    <div id="paso_2" class="paso">
                        <h3>Paso 2: Cree una contraseña.</h3>
                        <span>Anótala, la usarás siempre para ingresar :)</span>
                        <div class="reg_item">
                            <input type="password" name="reg_contraseña" id="reg_contraseña" class="formulario" placeholder="Contraseña">
                        </div>
                        <div class="reg_item">
                            <input type="password" name="reg_conf_contraseña" id="reg_conf_contraseña" class="formulario" placeholder="Confirma tu contraseña">
                        </div>
                    </div>
                        
                    <div id="paso_3" class="paso">
                        <h3>Paso 3: Datos de contacto</h3>
                        <span>El correo a donde tus clientes te contactarán</span> 
                        <div class="reg_item">
                            <input type="email" name="reg_email" id="reg_email" class="formulario" placeholder="Email">
                        </div>
                        
                        <div class="reg_item">
                            <select name="reg_indicativo" id="reg_indicativo" class="formulario" > <!--Se le pondrá indicativo al pais que seleccionen-->
                            <option value="default" class="formulario">Seleccione su país...</option>
                        </select> 
                        <input type="text" name="reg_celular" id="reg_celular" class="formulario" placeholder="Número celular">
                        <p><input type="checkbox" name="check_whatsapp" id="check_whatsapp"> Quiero que mis clientes me contacten a WhatsApp con este mismo número.</p>  
                        </div>
                    </div>

                    <div id="paso_4" class="paso">
                        <h3>Paso 4: Tu lugar de residencia</h3>
                        <div class="reg_item">
                            <select name="reg_pais" id="reg_pais" class="formulario">
                                <option value="default">Seleccione su país...</option>
                            </select>
                        </div>
                        <div class="reg_item">
                            <select name="reg_estado_departamento" id="reg_estado_departamento" disabled class="formulario">
                                <option value="default">Seleccione su departamento...</option>
                            </select>
                        </div>
                        <div class="reg_item">
                            <select name="reg_ciudad" id="reg_ciudad" disabled class="formulario">
                                <option value="default">Seleccione su ciudad...</option>
                            </select>
                        </div>
                        <a href="#">Mi ubicación no aparece :(</a>
                        <h2>Aqui va el captcha</h2>
                    </div>

                    <div class="pagination">
                        <button id="atras">Atrás</button>
                        <button id="siguiente">Siguiente</button>
                        <button id="reg_btn_registrarme">Registrarme</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
