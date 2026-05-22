<?php
        session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }
        include "../includes/conn.php";

        if(isset($_GET["idmodel"]) && isset($_GET["couleur"])){
            $idmodel = intval($_GET["idmodel"]);
            $couleur = ($_GET["couleur"]);
            $selectModelCouleur = $bd->prepare("SELECT id_model,couleur from couleur_models where id_model=? and couleur=?");
            $selectModelCouleur->execute(array($idmodel,$couleur));
            if($selectModelCouleur->rowCount()>0){
                $idmodel = intval($_GET["idmodel"]);
                $selectGamme = $bd->prepare("SELECT id_gamme from model where id=?");
                $selectGamme->execute(array($idmodel));
                $gamme = $selectGamme->fetch();
                $deleteCouleur = $bd->prepare("DELETE from couleur_models where id_model=? and couleur=?");
                $deleteCouleur->execute(array($idmodel,$couleur));
                header("location:pagemodel.php?idgamme=".$gamme["id_gamme"]."&idmodel=".$idmodel);
            }
        }elseif(isset($_GET["idmodel"])){
            $idmodel = intval($_GET["idmodel"]);
            $selectModel = $bd->prepare("SELECT * from model where id=?");
            $selectModel->execute(array($idmodel));
            if($selectModel->rowCount()>0){
                $selectGamme = $bd->prepare("SELECT id_gamme from model where id=?");
                $selectGamme->execute(array($idmodel));
                $gamme = $selectGamme->fetch();
                $delete_model = $bd->prepare("DELETE FROM model where id=?");
                $delete_model->execute(array($idmodel));
                $delete_model = $bd->prepare("DELETE FROM couleur_models where id_model=?");
                $delete_model->execute(array($idmodel));
                header("location:pagemodel.php?idgamme=".$gamme["id_gamme"]."&idmodel=".$idmodel);
            }
        }
?>