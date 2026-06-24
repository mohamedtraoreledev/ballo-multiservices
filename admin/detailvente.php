<?php

    session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }

        include "../includes/conn.php";

        if(isset($_GET["idvente"]) and !empty($_GET["idvente"])){
            $idvente = $_GET["idvente"];
            $recupDetailvente = $bd->prepare("SELECT nom,stockage,couleur,image,etat,dv.quantite,prix_unitaire,dv.id_vente as id_vente FROM  detail_vente as dv join vente as vd on dv.id_vente=vd.id join model as m on dv.id_model=m.id join couleur_models as col on dv.id_couleur=col.id where dv.id_vente=?");
            $recupDetailvente->execute(array($idvente));
            if($recupDetailvente->rowCount()==0){
                $msg_succes = "Pas de détails dans la vente";
            }
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
            <?php if(isset($recupDetailvente)):?>

            <?php while($detailVente = $recupDetailvente->fetch()){
                ?>
                
                        <div class="detailspanier">
                            <img src="../imagebdd/<?=$detailVente["image"]?>" alt="">
                            <h4><?=$detailVente["nom"]?></h4>
                            <p><?=$detailVente["stockage"]?></p>
                            <p>Quantité <strong><?=$detailVente["quantite"]?></strong></p>
                            <p><?=$detailVente["couleur"]?></p>
                            <h4><?=$detailVente["prix_unitaire"]?> FCFA</h4>
                            <!-- <a href=""><button>Refuser la commande</button></a> -->
                        </div>
                
                
                
                <?php
            }?>
            <?php endif;?>
                
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