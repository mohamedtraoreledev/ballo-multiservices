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
        $stock_manquant = false;
        while($recupQt = $recupQtCommande->fetch()){
            $recupStock = $bd->prepare("SELECT stock from couleur_models where id=?");
            $recupStock->execute(array($recupQt["id_couleur"]));
            $recupSt=$recupStock->fetch();
            $stock = $recupSt["stock"];
            if($stock<$recupQt["quantite"]){
                $stock_manquant = true;
                
                $updateEtat = $bd->prepare("UPDATE commande_details set etat=? where id_couleur=? and id_commande=?");
                $updateEtat->execute(array(2,$recupQt["id_couleur"],$id_commande));
                
            }else{
                $statu = "<p style='color:green'>Acceptée<p/>";
                $statuProduit="<p style='color:green'>Valider<p/>";
                
                $updateQuantite = $bd->prepare("UPDATE couleur_models set stock = stock-? where id=?");
                $updateQuantite->execute(array($recupQt["quantite"],$recupQt["id_couleur"]));
                $updateStatu = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                $updateStatu->execute(array($statu,$id_commande));
                $updateStatuProduit = $bd->prepare("UPDATE commande_details set statu_produit=? where id_commande=? and etat=?");
                $updateStatuProduit->execute(array($statuProduit,$id_commande,1));
                
                
            }

            if($stock_manquant==true){
                $statu = "<p style='color:red'> Manque de stock <p/>";
                $updateStatu = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                $updateStatu->execute(array($statu,$id_commande));
                // $statuProduitManque="<p style='color:red'>Son stock manque<p/>";
                // $updateStatuProduit = $bd->prepare("UPDATE commande_details set statu_produit=? where id_commande=? and etat=?");
                // $updateStatuProduit->execute(array($statuProduitManque,$id_commande,2));
            }
            header("location:../admin/commande.php");

        }

    // VERIFUCATION DU STOCK DES PRODUITS A ETAT 2 en cas de remise de stock

        $recupQtCommandeD = $bd->prepare("SELECT id_couleur,quantite FROM commande_details where id_commande=? and etat=?");
        $recupQtCommandeD->execute(array($id_commande,2));
        while($recupQtD = $recupQtCommandeD->fetch()){
            $recupStockD = $bd->prepare("SELECT stock from couleur_models where id=?");
            $recupStockD->execute(array($recupQtD["id_couleur"]));
            $recupStD=$recupStockD->fetch();
            $stockD = $recupStD["stock"];
            if($stockD>$recupQtD["quantite"]){
                
                $updateEtatD = $bd->prepare("UPDATE commande_details set etat=? where id_couleur=? and id_commande=?");
                $updateEtatD->execute(array(1,$recupQtD["id_couleur"],$id_commande));

                $statuD = "<p style='color:green'>Acceptée<p/>";
                $statuProduitD="<p style='color:green'>Valider<p/>";
                
                $updateQuantiteD = $bd->prepare("UPDATE couleur_models set stock = stock-? where id=?");
                $updateQuantiteD->execute(array($recupQtD["quantite"],$recupQtD["id_couleur"]));
                $updateStatuD = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                $updateStatuD->execute(array($statuD,$id_commande));
                $updateStatuProduitD = $bd->prepare("UPDATE commande_details set statu_produit=? where id_commande=? and etat=?");
                $updateStatuProduitD->execute(array($statuProduitD,$id_commande,1)); 
            }
            header("location:../admin/commande.php");

        }
        


    }

?>