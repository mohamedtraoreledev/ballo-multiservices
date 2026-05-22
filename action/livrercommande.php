<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

    if(isset($_GET["idCommande"]) and !empty($_GET["idCommande"])){
        $id_commande = $_GET["idCommande"];
                
            $statu = "<p style='color:green'>Livrer<p/>";
                $updateStatu = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                $updateStatu->execute(array($statu,$id_commande));
                $updateStatu = $bd->prepare("UPDATE commande set etat=? where id=?");
                $updateStatu->execute(array(3,$id_commande));
                header("location:../users/commandeclient.php");
                
                
            }


?>