<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido al sistema de transferencia de archivos</h1>
    <?php
        include('../php/dbconn.php');
        $codigo_del_pais = 1;
        $ruta_archivo = "../json/Colombia.json";
        $jsonContent = file_get_contents($ruta_archivo);
        $data = json_decode($jsonContent, true);

        if($data !== null){

            if($conex){

                //Borrar los elementos de la tabla departamentos
                $query = "DELETE FROM tbl_estado";
                mysqli_query($conex, $query);
                #reiniciar el auto-contador
                $query = "ALTER TABLE tbl_estado AUTO_INCREMENT = 0";
                mysqli_query($conex,$query);

                //Borrar tabla municipios
                $query = "DELETE FROM tbl_municipio";
                mysqli_query($conex, $query);
                #reiniciar el auto-contador
                $query = "ALTER TABLE tbl_municipio AUTO_INCREMENT = 0";
                mysqli_query($conex,$query);

                foreach($data as $item){
                    $departamento = mysqli_real_escape_string($conex, $item['departamento']);
                    $query = "INSERT INTO tbl_estado (nombre_estado, id_pais) VALUES ('$departamento', $codigo_del_pais)";
                    mysqli_query($conex, $query);

                    
                    echo($departamento);
                    $query = "SELECT id_estado FROM tbl_estado WHERE nombre_estado = '$departamento'";
                    $result = mysqli_query($conex, $query);
                    $row = mysqli_fetch_assoc($result);
                    $id_dep = $row['id_estado'];
                    echo($id_dep);
                    foreach($item['ciudades'] as $ciudad){
                        $ciudad = mysqli_real_escape_string($conex, $ciudad);
                        //Insertar la ciudad en la tbl_municipios
                        $query = "INSERT INTO tbl_municipio (nombre_municipio, id_estado, id_pais) VALUES ('$ciudad', $id_dep, $codigo_del_pais)";
                        mysqli_query($conex,$query);
                    }
                }
                mysqli_close($conex);
                echo("Los datos se han insertado correctamente");
            }
            else {
                echo 'No se pudo establecer la conexión a la base de datos.';
            }
        }
        else {
            echo 'No se pudo decodificar el archivo JSON.';
        }
        
    ?>
</body>
</html>

