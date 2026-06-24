<?php 
session_start();
include "../includes/conn.php";

$recupCommande = $bd->prepare("SELECT nom,prenom,telephone,email,date_commande,co.etat,statu_commande,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id where co.id_client=? order by co.id desc");
        $recupCommande->execute(array($_SESSION["id"]));
        if($recupCommande->rowCount()==0){
            $msg_succes = "Vos commandes s'afficheront ici";
        }

while($recupC=$recupCommande->fetch()){ ?>


        <div class="commande-card">

            <div class="ligne">
                <strong>Nom :</strong>
                <span><?=$recupC["nom"]?></span>
            </div>

            <div class="ligne">
                <strong>Prénom :</strong>
                <span><?=$recupC["prenom"]?></span>
            </div>

            <div class="ligne">
                <strong>Email :</strong>
                <span><?=$recupC["email"]?></span>
            </div>

            <div class="ligne">
                <strong>Téléphone :</strong>
                <span><?=$recupC["telephone"]?></span>
            </div>

            <div class="ligne">
                <strong>Date :</strong>
                <span><?=$recupC["date_commande"]?></span>
            </div>

            <div class="ligne">
                <strong>Total :</strong>
                <span><?=$recupC["prix_total"]?> FCFA</span>
            </div>

            <div class="ligne">
                <strong>Statut :</strong>
                <span style="color:orange"><?=$recupC["statu_commande"]?></span>
            </div>

            

            <div class="btncommandes">

                <?php if($recupC["statu_commande"]!="<p style='color:red'>Commande annulée<p/>"
                && $recupC["statu_commande"]!="<p style='color:red'>Commande refusée<p/>"
                && $recupC["statu_commande"]!="<p style='color:yellow'>Commande en cours de livraison<p/>"
                && $recupC["statu_commande"]!="<p style='color:green'>Livrer<p/>"): ?>

                <a href="detailscommandeclient.php?idCommande=<?=$recupC["idCommande"]?>">
                    <button>Voir produit(s)</button>
                </a>

                <a href="../action/annulercommandeclient.php?idCommande=<?=$recupC["idCommande"]?>">
                    <button>Annuler</button>
                </a>

                <?php endif; ?>
                <?php if($recupC["statu_commande"]=="<p style='color:yellow'>Commande en cours de livraison<p/>"):?>
                    <a href="../action/livrercommande?idCommande=<?=$recupC["idCommande"]?>"><button>Signaler livraison faite Ici</button></a>
                <?php endif;?>
                <?php if($recupC["statu_commande"]=="<p style='color:green'>Livrer<p/>"):?>
                    <a href="../action/reçu.php?idCommande=<?=$recupC["idCommande"]?>"><button>Obtenir un reçu</button></a>
                <?php endif;?>

            </div>

        </div>

        <?php } ?>