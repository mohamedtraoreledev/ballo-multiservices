

<?php
include "../includes/conn.php";

    $selectvpCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
        $selectvpCommande->execute(array(0));
        $nb_vp = $selectvpCommande->fetch();
        echo $nb_vp["nbcommande"];
?>

