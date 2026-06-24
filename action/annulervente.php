<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        if(isset($_GET["idvente"])){
            $idvente = $_GET["idvente"];
            $update = $bd->prepare("UPDATE vente set etat=? where id=?");
            $update->execute([0,$idvente]);

            $recupIdCouleur = $bd->prepare("SELECT*FROM detail_vente where id_vente=?");
            $recupIdCouleur->execute(array($idvente));
            while($IdCouleur = $recupIdCouleur->fetch()){
                $updateQuantite = $bd->prepare("UPDATE couleur_models set stock=stock+? where id=?");
                $updateQuantite->execute(array($IdCouleur["quantite"],$IdCouleur["id_couleur"]));
                header("location:../admin/venteboutique.php");

            }
        }