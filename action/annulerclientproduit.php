<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        if(isset($_GET["idCouleur"]) && isset($_GET["idCommande"]) && isset($_GET["quantite"]) && isset($_GET["prix"])){
            $idCouleur = $_GET["idCouleur"];
            $idCommande = $_GET["idCommande"];
            $quantite = $_GET["quantite"];
            $prix = $_GET["prix"]*$_GET["quantite"];

            $updateEtatProduit = $bd->prepare("UPDATE commande_details set etat=? where id_commande=? and id_couleur=?");
            $updateEtatProduit->execute(array(0,$idCommande,$idCouleur));

            $recupStatuCommande = $bd->prepare("SELECT statu_commande from commande where id=?");
            $recupStatuCommande->execute(array($idCommande));
            $statuCommande = $recupStatuCommande->fetch();
            if($statuCommande["statu_commande"]=="<p style='color:green'>Acceptée<p/>"){
                $statu_produit = "<p style='color:red'>Produit annulé<p/>";
                $updateStock = $bd->prepare("UPDATE couleur_models set stock=stock+? where id=?");
                $updateStock->execute(array($quantite,$idCouleur));
                $updateStatuProduit = $bd->prepare("UPDATE commande_details set statu_produit=? where id_commande=? and id_couleur=?");
                $updateStatuProduit->execute(array($statu_produit,$idCommande,$idCouleur));
                $updatePrixTotal = $bd->prepare("UPDATE commande set prix_total=prix_total-? where id=?");
                $updatePrixTotal->execute(array($prix,$idCommande));
                header("location:../users/commandeclient.php");

            }else{
                $statu_produit = "<p style='color:red'>Produit annulé<p/>";
                $updateStatuProduit = $bd->prepare("UPDATE commande_details set statu_produit=? where id_commande=? and id_couleur=?");
                $updateStatuProduit->execute(array($statu_produit,$idCommande,$idCouleur));

                $updatePrixTotal = $bd->prepare("UPDATE commande set prix_total=prix_total-? where id=?");
                $updatePrixTotal->execute(array($prix,$idCommande));
                header("location:../users/commandeclient.php");
            }

        }
?>