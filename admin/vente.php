<?php



    session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }

        include "../includes/conn.php";
        // AFFICHAGE DEs COMMANDE
        
        $recupCommande = $bd->prepare("SELECT nom,prenom,telephone,email,date_commande,statu_commande,co.etat,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id where co.etat=? order by co.id desc");
        $recupCommande->execute(array(3));
        if($recupCommande->rowCount()==0){
            $msg_succes = "Les commandes de vos clients s'afficheront ici";
        }

                $selectvpCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
        $selectvpCommande->execute(array(0));
        $nb_vp = $selectvpCommande->fetch();


                $selectNbVente = $bd->prepare("SELECT COUNT(*) as nbvente from commande where etat3=?");
                $selectNbVente->execute(array(4));
                $nb_vente= $selectNbVente->fetch();


                // $updateV = $bd->prepare("UPDATE commande set etat3=?");
                // $updateV->execute([5]);
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
                <h1>MES VENTES</h1>
                <p style="color:white"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                <div id="traitcomment"></div>
                <?php while($recupC = $recupCommande->fetch()){
                    ?>
                       <div class="vente-card">

                            <p><strong>Nom :</strong> <?=$recupC["nom"]?></p>

                            <p><strong>Prénom :</strong> <?=$recupC["prenom"]?></p>

                            <p><strong>Email :</strong> <?=$recupC["email"]?></p>

                            <p><strong>Téléphone :</strong> <?=$recupC["telephone"]?></p>

                            <p><strong>Date :</strong> <?=$recupC["date_commande"]?></p>

                            <p><strong>Total :</strong> <?=$recupC["prix_total"]?> FCFA</p>

                            <p><strong>Statut :</strong> <?=$recupC["statu_commande"]?></p>

                            <div class="vente-actions">
                                <a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>">
                                    <button>Voir produit(s)</button>
                                </a>
                            </div>

                        </div>
                    
                    <?php
                }?>
                
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
