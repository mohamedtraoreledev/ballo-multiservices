<?php

    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";

        // AFFICHAGE DE LA commande
        if(isset($_GET["idvente"])){
            $idvente = $_GET["idvente"];
            $recupvente = $bd->prepare("SELECT nomclient,prenomclient,telephone,dv.quantite,description,m.nom as nommodel,prix_unitaire,couleur,date_vente,prix_total from detail_vente as dv join vente as v on dv.id_vente=v.id join model as m on dv.id_model=m.id join couleur_models as col on dv.id_couleur=col.id where dv.id_vente=?");
            $recupvente->execute(array($idvente));
            $reçu=$recupvente->fetch(PDO::FETCH_ASSOC);
            $recupvente->execute(array($idvente));
            // $recu = $recupCommande->fetch();
        }
?>












<!DOCTYPE html>
<html lang="en">
<?php include "../includes/header.php"?>
<body>
   


    <div class="commandeespace">
        <h1>Reçu de la vente</h1>
        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
        <div id="traitcomment"></div>
        <div class="logo">
            <span class="material-symbols-outlined">add_shopping_cart</span>
            <h1 style="color:black">BALLO <span>Multi-Services</span></h1>
        </div>
        <div class="nomprenom">
            <h1>Nom : <?=$reçu["nomclient"]?> </h1>
            <h1>Prenom :<?=$reçu["prenomclient"]?> </h1>
            <h1>Téléphone:<?=$reçu["telephone"]?></h1>
        </div>

        <table>
            <tr class="threçu">
                <th>PRODUIT(S) ACHETÉ(S)</th>
                <th>DESCRIPTION</th>
                <th>QUANTITÉ</th>
                <th>PRIX UNITAIRE (PU)</th>
                <!-- <th>TELEPHONE</th> -->
            </tr>
            <?php while($reç = $recupvente->fetch(PDO::FETCH_ASSOC)){
                    ?>
                        <tr>
                <td><?=$reç["nommodel"]?> - Couleur : <?=$reç["couleur"]?></td>
                <td><?=$reç["description"]?></td>
                <td><?=$reç["quantite"]?></td>
                <td><?=$reç["prix_unitaire"]?>FCFA</td>
                <!-- <td><?=$reç["telephone"]?></td> -->
            </tr>
                    <?php
                }?>
            
            <tr class="threçu">
                <th>PRIX TOTAL : <?=$reçu["prix_total"]?> FCFA</th>
            </tr>
            <tr>
                <?php

                    $num = preg_replace('/[^0-9]/', '', $reçu["telephone"]);
                    $lienRecu = "https://ballo-multiservices-10.onrender.com/action/recupdfventebtq.php?idvente=".$idvente;
                    $message = "Bonjour ".$reçu["nomclient"]." ".$reçu["prenomclient"]."\n\n";
                    $message .= "Merci pour votre achat chez BALLO MULTI-SERVICES.\n\n";
                    $message .= "REÇU DE VENTE\n".$lienReçu;
                    

                  
                ?>
                <a href="reçupdfventebtq.php?idvente=<?= $idvente ?>">
                    Télécharger en PDF
                </a>
                <a style="background:green;color:white;padding:8px 12px;border-radius:5px;text-decoration:none;" href="https://wa.me/<?php echo $num; ?>?text=<?php echo urlencode($message); ?>"
target="_blank">
                    Envoyer le reçu par whatsApp
                </a>
                <!-- <button id="impreçu" style="color:red">Imprimer</button> -->
            </tr>
        </table>

    </div>
</body>
<script>
    var imprimer = document.querySelector("#impreçu");
        imprimer.addEventListener("click",()=>{
            window.print();
        })
</script>
<script src="../js.js"></script>
</html>