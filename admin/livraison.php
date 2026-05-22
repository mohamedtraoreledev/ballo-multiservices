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

                $selectNbCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
                $selectNbCommande->execute(array(0));
                $nb_commande = $selectNbCommande->fetch();


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
                <?php while($recupLiv = $recupLivraison->fetch()){
                    
                             ?>
                    
                        <table>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date de la commmande</th>
                        <th>Prix total de la commande</th>
                        <th id="statucommandeadmin">Statu de la commande</th>
                        <th>Ville de livraison</th>
                        <th style="color:white">Adresse de livraison</th>
                    </tr>
                    <tr>
                        <td><?=$recupLiv["nom"]?></td>
                        <td><?=$recupLiv["prenom"]?></td>
                        <td><?=$recupLiv["email"]?></td>
                        <td><?=$recupLiv["telephone"]?></td>
                        <td><?=$recupLiv["date_commande"]?></td>
                        <td><?=$recupLiv["prix_total"]?> FCFA</td>
                        <td><?=$recupLiv["statu_commande"]?></td>
                        <td><?=$recupLiv["ville"]?></td>
                        <td style="color:orange"><?=$recupLiv["adresse"]?></td>
                    </tr>
                    <tr>
                        <td><?php if($recupLiv["statu_commande"]=="<p style='color:green'>Livrer<p/>"):?>
                        <a href="../action/reçuadmin.php?idCommande=<?=$recupLiv["idCommande"]?>" style="color:white">Obtenir un reçu</a>
                        <?php endif;?>
                        </td>
                        <th>Maps</th>
                        <td><iframe src="https://maps.google.com/maps?&q=<?=$recupLiv["adresse"]?>&output=embed" width="500%" height="600px" frameborder="0"></iframe></td>
                    </tr>


                </table>
                    <?php
                    }
                ?>
                    
                    
                
            </div>

        </div>
</body>
<script src="../js.js"></script>
</html>