<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        if(isset($_GET["idCouleur"]) && isset($_GET["idCommande"])){
            $idCouleur = $_GET["idCouleur"];
            $idCommande = $_GET["idCommande"];
            $statuProduit="<p style='color:red'>Désoler<br>la quantité de la <br> couleur manque<p/>";

            $updateEtat = $bd->prepare("UPDATE commande_details set statu_produit=? where id_commande=? and id_couleur=? and etat=?");
            $updateEtat->execute(array($statuProduit,$idCommande,$idCouleur,2));
            // RECUP DU  PRIX DU MODEL ET REDUCTION DU PRIX TOTAL DE LA COMMNADE
            $recupModel = $bd->prepare("SELECT id_model from couleur_models where id=?");
            $recupModel->execute(array($idCouleur));
            $model = $recupModel->fetch();

            $recupPrix = $bd->prepare("SELECT prix from model where id=?");
            $recupPrix->execute(array($model["id_model"]));
            $prix = $recupPrix->fetch();

            // $recupPrixTotal = $bd->prepare("SELECT prix_total from commande where id=?");
            // $recupPrixTotal->execute(array($idCommande));

            // $PrixTotal = $recupPrixTotal->fetch();

            $updatePrixTotal = $bd->prepare("UPDATE commande set prix_total=prix_total-? where id=?");
            $updatePrixTotal->execute(array($prix["prix"],$idCommande));

            header("location:../admin/detailscommandeadmin.php?idCommande=".$idCommande);


        }
?>