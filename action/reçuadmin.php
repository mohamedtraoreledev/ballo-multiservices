<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
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

        <div class="liste-produits-recu">

<?php while($reç = $recupCommande->fetch(PDO::FETCH_ASSOC)){ ?>

    <div class="card-recu">

        <p class="nom-produit">
            <strong>Couleur :</strong><?=$reç["nommodel"]?>
        </p>

        <p><strong>Couleur :</strong> <?=$reç["couleur"]?></p>

        <p><strong>Description :</strong> <?=$reç["description"]?></p>

        <p><strong>Quantité :</strong> <?=$reç["quantite"]?></p>

        <p><strong>Prix unitaire :</strong> <?=$reç["prixu"]?> FCFA</p>

    </div>

<?php } ?>

</div>

<div class="total-recu">
    <h2>PRIX TOTAL : <?=$reçu["prix_total"]?> FCFA</h2>
</div>

<div class="actions-recu">
    <a href="reçupdf.php?idCommande=<?=$idCommande?>" class="btn-pdf">
        Télécharger en PDF
    </a>
</div>

    </div>
</body>
<script src="../js.js"></script>
</html>