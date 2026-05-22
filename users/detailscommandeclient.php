<?php

    session_start();
        if(!isset($_SESSION["auth"])){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        if(isset($_GET["idCommande"]) and !empty($_GET["idCommande"])){
            $idCommande = $_GET["idCommande"];
            $recupDetailCommande = $bd->prepare("SELECT nom,stockage,couleur,image,quantite,cd.prix,statu_produit,col.id as id_couleur,cd.id_commande as id_commande FROM commande_details as cd join model as m on cd.id_produit=m.id join couleur_models as col on cd.id_couleur=col.id where cd.id_commande=?");
            $recupDetailCommande->execute(array($idCommande));
            if($recupDetailCommande->rowCount()==0){
                $msg_succes = "Pas de détals dans la commande";
            }
        }

?>













<!DOCTYPE html>
<html lang="en">
<?php include "../includes/header.php"?>
<body>
    <div class="header">
        <div class="logo">
            <span class="material-symbols-outlined">add_shopping_cart</span>
            <h1>BALLO <span>Multi-Services</span></h1>
        </div>
        <div class="menu">
            <a href="index.php">Acceuil</a>
            <!-- <a href="#Produits">Nos produits</a> -->
            <!-- <a href="Contacts">Contacts</a> -->
            <a href="">Promotions</a>
            <a href="../admin/logout.php">Déconnexion</a>
        </div>
        <div class="icons">
            <!-- <form action="" method="post">
                <button><span class="material-symbols-outlined">search</span></button>
                <input type="search" name="search" id="search" placeholder="recherche...">
            </form> -->
            <a href="panier.php"><span class="material-symbols-outlined">shopping_cart</span><nav>0</nav></a>
        </div>
    </div>

    <div class="band">
        <p>Retrouvez toutes vos commandes<br>faites ici chez Ballo</p>
        <div id="trait"></div>
        <h3>Ballo Multi-Services,qualité guarantie au mali</h3>
    </div>


    <div class="commandeespace">
        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
        <?php while($dC=$recupDetailCommande->fetch()){
            ?>
                <div class="detailspanier">
                    <img src="../imagebdd/<?=$dC["image"]?>" alt="">
                    <h4><?=$dC["nom"]?></h4>
                    <p><?=$dC["stockage"]?></p>
                    <p>Quantité <strong><?=$dC["quantite"]?></strong></p>
                    <p><?=$dC["couleur"]?></p>
                    <h4><?=$dC["prix"]?> FCFA</h4>
                    <div class="statuproduit">
                        <h4>Statu du produit : <br> <span><?=$dC["statu_produit"]?></span></h4>
                    </div>
                    <?php if($dC["statu_produit"]!="<p style='color:red'>Désoler<br>la quantité manque<p/>" && $dC["statu_produit"]!="<p style='color:red'>Produit Refusée<p/>" && $dC["statu_produit"]!="<p style='color:red'>Produit annulé<p/>" && $dC["statu_produit"]!="<p style='color:green'>Valider<p/>" ):?>
                    <a href="../action/annulerclientproduit.php?idCouleur=<?=$dC["id_couleur"]?>&idCommande=<?=$dC["id_commande"]?>&quantite=<?=$dC["quantite"]?>&prix=<?=$dC["prix"]?>">Annuler cet produit</a>
                    <?php endif;?>
                </div>
            
            <?php
        }?>
        
        
    </div>
</body>
<script src="../js.js"></script>
</html>