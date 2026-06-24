<?php

        include "../includes/conn.php";

        // $selectNbClient = $bd->query("SELECT COUNT(*) as nbclient from client");
        // $nb_client = $selectNbClient->fetch();



        $selectvpCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
        $selectvpCommande->execute(array(0));
        $nb_vp = $selectvpCommande->fetch();



        $selectNbVente = $bd->prepare("SELECT COUNT(*) as nbvente from commande where etat3=?");
        $selectNbVente->execute(array(4));
        $nb_vente= $selectNbVente->fetch();

?>


                
                <a href="commande.php"><span class="material-symbols-outlined">inventory_2</span>Commandes<nav class="nbcommande"><?=$nb_vp["nbcommande"]?></nav></a>
                <a href="vente.php"><span class="material-symbols-outlined">store</span>Ventes<nav class="nbvente"><?=$nb_vente["nbvente"]?></nav></a>
                
            
</div>