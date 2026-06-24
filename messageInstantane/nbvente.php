<?php
include "../includes/conn.php";

    $selectNbVente = $bd->prepare("SELECT COUNT(*) as nbvente from commande where etat3=?");
        $selectNbVente->execute(array(4));
        $nb_vente= $selectNbVente->fetch();
        echo $nb_vente["nbvente"];
?>