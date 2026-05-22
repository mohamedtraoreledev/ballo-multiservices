<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        if(isset($_GET["idCommande"]) and !empty($_GET["idCommande"])){
            $idCommande = $_GET["idCommande"];
            $recupDetailCommande = $bd->prepare("SELECT nom,stockage,couleur,image,etat,quantite,cd.prix,statu_produit,col.id as id_couleur,cd.id_commande as id_commande FROM commande_details as cd join model as m on cd.id_produit=m.id join couleur_models as col on cd.id_couleur=col.id where cd.id_commande=?");
            $recupDetailCommande->execute(array($idCommande));
            if($recupDetailCommande->rowCount()==0){
                $msg_succes = "Pas de détails dans la commande";
            }
        }

         $selectNbCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
                $selectNbCommande->execute(array(0));
                $nb_commande = $selectNbCommande->fetch();


                $selectNbVente = $bd->prepare("SELECT COUNT(*) as nbvente from commande where etat3=?");
                $selectNbVente->execute(array(4));
                $nb_vente= $selectNbVente->fetch();

                $updateV = $bd->prepare("UPDATE commande set etat3=? where etat=?");
                 $updateV->execute([4,3]);

?>

















<!DOCTYPE html>
<html lang="en">
<?php include "../includes/header.php"?>
<body class="pageadmin">
        <?php include "../includes/navbar.php"?>

        <div class="Contentadmin">
            <?php if(isset($recupDetailCommande)):?>

            <?php while($detailCommande = $recupDetailCommande->fetch()){
                ?>
                
                        <div class="detailspanier">
                            <img src="../imagebdd/<?=$detailCommande["image"]?>" alt="">
                            <h4><?=$detailCommande["nom"]?></h4>
                            <p><?=$detailCommande["stockage"]?></p>
                            <p>Quantité <strong><?=$detailCommande["quantite"]?></strong></p>
                            <p><?=$detailCommande["couleur"]?></p>
                            <h4><?=$detailCommande["prix"]?> FCFA</h4>
                            <div class="statuproduitadmin">
                                <h4>Statu du produit : <br> <span id="statupadmin"><?=$detailCommande["statu_produit"]?></span></h4>
                            </div>
                            <?php if($detailCommande["statu_produit"]!="<p style='color:red'>Désoler<br>la quantité manque<p/>" && $detailCommande["statu_produit"]!="<p style='color:red'>Produit Refusée<p/>" && $detailCommande["statu_produit"]!="<p style='color:red'>Produit annulé<p/>" && $detailCommande["statu_produit"]!="<p style='color:green'>Valider<p/>"):?>
                            <?php if($detailCommande["etat"]==2):?>
                                <a href="../action/signalerStock.php?idCouleur=<?=$detailCommande["id_couleur"]?>&idCommande=<?=$detailCommande["id_commande"]?>">Manque de ce stock<br>signaler le client</a>
                            <?php endif;?>
                            <?php endif;?>
                            <?php if($detailCommande["statu_produit"]!="<p style='color:red'>Désoler<br>la quantité manque<p/>" && $detailCommande["statu_produit"]!="<p style='color:red'>Produit Refusée<p/>" && $detailCommande["statu_produit"]!="<p style='color:red'>Produit annulé<p/>" && $detailCommande["statu_produit"]!="<p style='color:green'>Valider<p/>"):?>
                            <a href="../action/refuserproduit.php?idCouleur=<?=$detailCommande["id_couleur"]?>&idCommande=<?=$detailCommande["id_commande"]?>&quantite=<?=$detailCommande["quantite"]?>&prix=<?=$detailCommande["prix"]?>">Refuser cet produit</a>
                            <?php endif;?>
                            <!-- <a href=""><button>Refuser la commande</button></a> -->
                        </div>
                
                
                
                <?php
            }?>
            <?php endif;?>
                
            </div>

        </div>
</body>
<script src="../js.js"></script>
</html>