<?php

    // MODE DU SITE
    if($_SERVER['SERVER_NAME'] == "localhost"){

        $mode = "dev";

    }else{

        $mode = "production";

    }


    // GESTION DES ERREURS
    if($mode == "dev"){

        ini_set('display_errors',1);

    }else{

        ini_set('display_errors',0);

    }

    error_reporting(E_ALL);


    try{
        $bd = new PDO("mysql:kodama.proxy.rlwy.net:55129/railway;port=55129;dbname=railway;charset=utf8","root","PgnEHOHbhDvJybGCBHgCnYfgNGDGKDII", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
        $bd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // echo "<p style=color:white>connexion reussi</p> ";
    }catch(Exception $e){
        echo'Erreur = '.$e->getMessage();
    }

?>