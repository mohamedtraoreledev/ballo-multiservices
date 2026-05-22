<?php

    session_start();
        if(!$_SESSION["auth"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        // AFFICHAGE DE LA commande
        if(isset($_GET["idCommande"])){
            $idCommande = $_GET["idCommande"];
            $recupCommande = $bd->prepare("SELECT c.nom as nomclient,prenom,telephone,quantite,description,email,m.nom as nommodel,cd.prix as prixu,couleur,date_commande,co.etat,statu_commande,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id join commande_details as cd on cd.id_commande=co.id join couleur_models as col on cd.id_couleur=col.id join model as m on cd.id_produit=m.id where co.id=?");
            $recupCommande->execute(array($idCommande));
            $reçu=$recupCommande->fetch(PDO::FETCH_ASSOC);
            $recupCommande->execute(array($idCommande));
            // $recu = $recupCommande->fetch();
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
            <!-- <a href="../admin/logout.php">Déconnexion</a> -->
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
        <h1>Reçu de la commande</h1>
        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
        <div id="traitcomment"></div>
        <div class="logo">
            <span class="material-symbols-outlined">add_shopping_cart</span>
            <h1 style="color:black">BALLO <span>Multi-Services</span></h1>
        </div>
        <div class="nomprenom">
            <h1>Nom : <?=$reçu["nomclient"]?> </h1>
            <h1>Prenom :<?=$reçu["prenom"]?> </h1>
        </div>

        <table>
            <tr>
                <th>PRODUIT(S) ACHETÉ(S)</th>
                <th>DESCRIPTION</th>
                <th>QUANTITÉ</th>
                <th>PRIX UNITAIRE (PU)</th>
            </tr>
            <?php while($reç = $recupCommande->fetch(PDO::FETCH_ASSOC)){
                    ?>
                        <tr>
                <td><?=$reç["nommodel"]?> - Couleur : <?=$reç["couleur"]?></td>
                <td><?=$reç["description"]?></td>
                <td><?=$reç["quantite"]?></td>
                <td><?=$reç["prixu"]?>FCFA</td>
            </tr>
                    <?php
                }?>
            
            <tr>
                <th>PRIX TOTAL : <?=$reçu["prix_total"]?> FCFA</th>
            </tr>
            <tr>
                <a href="reçupdf.php?idCommande=<?= $idCommande ?>">
                    Télécharger en PDF
                </a>
            </tr>
        </table>

    </div>
</body>
<script src="../js.js"></script>
</html>