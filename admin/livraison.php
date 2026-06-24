<?php



    session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }

        include "../includes/conn.php";
        // AFFICHAGE DES COMMANDES

        $recupLivraison = $bd->prepare("SELECT nom,prenom,telephone,email,date_commande,statu_commande,prix_total, co.id as idCommande,co.etat,adresse,latitude,longitude,ville from commande as co join client as c on co.id_client=c.id join livraison as liv on co.id=liv.id_commande where co.etat=? order by liv.id desc");
        $recupLivraison->execute(array(3));
        if($recupLivraison->rowCount()==0){
            $msg_succes = "Vous retrouverez toutes vos livraisons à effectuer ici !!!";
        }

                $selectvpCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
        $selectvpCommande->execute(array(0));
        $nb_vp = $selectvpCommande->fetch();


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

            <div class="nommenu">
                <h1><?php
            echo ucfirst(str_replace(".php","",basename($_SERVER["PHP_SELF"])));
        ?></h1>
            </div>
            
            <div class="commandeespaceadmin">
                <h1>Livraison à faire</h1>
                <p style="color:white"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                <div id="traitcomment"></div>
                <div class="mobile-livraison">

                <?php while($recupLiv = $recupLivraison->fetch()){ ?>

                <div class="livraison-card">

                    <p><strong>Nom :</strong> <?=$recupLiv["nom"]?></p>

                    <p><strong>Prénom :</strong> <?=$recupLiv["prenom"]?></p>

                    <p><strong>Email :</strong> <?=$recupLiv["email"]?></p>

                    <p><strong>Téléphone :</strong> <?=$recupLiv["telephone"]?></p>

                    <p><strong>Date :</strong> <?=$recupLiv["date_commande"]?></p>

                    <p><strong>Montant :</strong> <?=$recupLiv["prix_total"]?> FCFA</p>

                    <p><strong>Ville :</strong> <?=$recupLiv["ville"]?></p>

                    <p><strong>Adresse :</strong> <?=$recupLiv["adresse"]?></p>

                    <p><strong>Statut :</strong> <?=$recupLiv["statu_commande"]?></p>

                    <iframe
                        src="https://maps.google.com/maps?&q=<?=$recupLiv["adresse"]?>&output=embed">
                    </iframe>

                    <?php if($recupLiv["statu_commande"]=="<p style='color:green'>Livrer<p/>"){ ?>
                        <a href="../action/reçuadmin.php?idCommande=<?=$recupLiv["idCommande"]?>">
                            Obtenir le reçu
                        </a>
                    <?php } ?>

                </div>

                <?php } ?>

                </div>
                    
                    
                
            </div>

        </div>
</body>
<script>


setInterval(function(){

    $.get("../messageInstantane/commandenb.php", function(data){
        $(".nbcommande").text(data);
    });

    $.get("../messageInstantane/nbvente.php", function(data){
        $(".nbvente").text(data);
    });

}, 5000);
    

</script>
<script src="../js.js"></script>
</html>