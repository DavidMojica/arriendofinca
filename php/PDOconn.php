<?php
    $dsn      = "mysql:host=localhost;dbname=arriendofinca;charset=UTF8";
    $username = "root";
    // $password = "Wm2023@pascual!";
    $password = "";
    try{
        $pdo = new PDO($dsn, $username, $password);
        $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "Error de conexion = ". $e->getMessage();
    }
?>