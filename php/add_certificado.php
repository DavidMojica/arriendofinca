<?php 
    include('PDOconn.php');
    include('essentials.php');
    session_start();
    $arr = comprobar_sesion_valida($_SESSION);
    $ban = $arr['ban'];
    $documento_usuario = $arr['documento_usuario'];

    #--------------------CERTIFICACIONES-------------------#
    if($ban){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_mov = trim($_POST['id_mov']);

            $query = "SELECT cedula_dueño FROM tbl_inmueble WHERE id_inmueble = :id_inmueble";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_inmueble', $id_mov, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($result) > 0){
                $res_mov = $result[0];
                $ced_dueno = $res_mov['cedula_dueño'];

                if($ced_dueno === $documento_usuario){
                    $query ="SELECT * FROM tbl_certificaciones WHERE id_inmueble = :id_inmueble";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id_inmueble', $id_mov, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(count($result) == 0){
                        $query = "INSERT INTO tbl_certificaciones(id_inmueble) VALUES (:id_inmueble)";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':id_inmueble', $id_mov, PDO::PARAM_INT);
                        $stmt->execute();
                        return_Response(true,"Su inmueble fue certificado");
                    }else return_Response(false, "Este inmueble ya está certificado");
                }else return_Response(false, "No se pudo comprobar el documento del usuario");
            } else return_Response(false, "No hay un inmueble con este id.");
        }else return_Response(false, "La solicitud no es en POST.");
    }else return_Response(false, "La sesion no pudo ser comprobada.");
?>