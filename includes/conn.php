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
        // $bd = new PDO("mysql:host=kodama.proxy.rlwy.net;port=55129;dbname=railway;charset=utf8","root","PgnEHOHbhDvJybGCBHgCnYfgNGDGKDII");
        $bd = new PDO("mysql:host=127.0.0.1;port=3308;dbname=ballo;charset=utf8","root","TRA123");
        $bd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // echo "<p style=color:white>connexion reussi</p> ";
    }catch(Exception $e){
        echo'Erreur = '.$e->getMessage();
    }

?>