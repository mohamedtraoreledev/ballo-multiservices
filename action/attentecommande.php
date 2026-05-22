<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

    if(isset($_GET["idCommande"]) and !empty($_GET["idCommande"])){
        $id_commande = $_GET["idCommande"];

        $recupQtCommande = $bd->prepare("SELECT id_couleur,quantite FROM commande_details where id_commande=? and etat=?");
        $recupQtCommande->execute(array($id_commande,1));
        // $stock_manquant = false;
        if($recupQtCommande->rowCount()>0){

        while($recupQt = $recupQtCommande->fetch()){
            $recupStock = $bd->prepare("SELECT stock from couleur_models where id=?");
            $recupStock->execute(array($recupQt["id_couleur"]));
            $recupSt=$recupStock->fetch();
                
            $statu = "En attente";
                $updateQuantite = $bd->prepare("UPDATE couleur_models set stock = stock+? where id=?");
                $updateQuantite->execute(array($recupQt["quantite"],$recupQt["id_couleur"]));
                $updateStatu = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                $updateStatu->execute(array($statu,$id_commande));
                header("location:../admin/commande.php");
                
                
            }

           
    }else{
        $statu = "En attente";
                $updateStatu = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                $updateStatu->execute(array($statu,$id_commande));
                header("location:../admin/commande.php");
    }

        }
        


    

?>