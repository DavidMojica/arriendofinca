<?php
    include('PDOconn.php');
    include('essentials.php');
    session_start();

    $ban     = false;
    $tp_user = 0;
    if(!isset($_SESSION['username'])){ #Si la sesion no está iniciada se redirige al usuario al index.
        header("Location: ../index.html");
        exit;
    }
    else{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_imagen = $_POST['id_imagen'];

            $query = "DELETE FROM tbl_imagenes WHERE id_imagen = :id_imagen";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_imagen', $id_imagen, PDO::PARAM_INT); 
            if($stmt->execute()){
                return_Response(true, "La imagen ha sido borrada");
            }
            else{
                return_Response(false, "No se pudo eliminar la imagen");
            }
        }
    }
?>