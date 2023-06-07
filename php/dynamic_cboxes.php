<?php
    include('PDOconn.php');
    include('essentials.php');
    #Verifica si el metodo de la solicitud es post.
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        #Variables provenientes de JQUERY ajax
        $pais_activo = trim($_POST['pais_activo']);
        $estado_activo = trim($_POST['estado_activo']);
        $type        = trim($_POST['type']);

        if($type == 0){
            $query = "SELECT * FROM tbl_estado WHERE id_pais = :id_pais";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('id_pais', $pais_activo, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($result)>0){
                return_Response(true,$result);
            }
        }
        else if($type == 1){
            $query = "SELECT * FROM tbl_municipio WHERE id_pais = :id_pais AND id_estado = :id_estado";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('id_pais', $pais_activo, PDO::PARAM_INT);
            $stmt->bindParam('id_estado', $estado_activo, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($result)>0){
                return_Response(true,$result);
            }
        }
        else{
                return_Response(false, $response);
        }
    }
?>