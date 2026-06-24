<?php



    session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }

        include "../includes/conn.php";
        // AFFICHAGE DEs COMMANDE
        
        $recupCommande= $bd->query("SELECT nom,prenom,telephone,email,date_inscript from client order by id desc");
        
        if($recupCommande->rowCount()==0){
            $msg_succes = "Retrouvez ici les infos de vos clients ";
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
                <h1>MES CLIENTS</h1>
                <p style="color:white"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                <div id="traitcomment"></div>
                <?php while($recupC = $recupCommande->fetch()){ ?>

<div class="clientcard">

    <div class="ligneclient">
        <strong>Nom :</strong>
        <span><?=$recupC["nom"]?></span>
    </div>

    <div class="ligneclient">
        <strong>Prénom :</strong>
        <span><?=$recupC["prenom"]?></span>
    </div>

    <div class="ligneclient">
        <strong>Email :</strong>
        <span><?=$recupC["email"]?></span>
    </div>

    <div class="ligneclient">
        <strong>Téléphone :</strong>
        <span><?=$recupC["telephone"]?></span>
    </div>

    <div class="ligneclient">
        <strong>Date inscription :</strong>
        <span><?=$recupC["date_inscript"]?></span>
    </div>

</div>

<?php } ?>
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