<?php



    session_start();
        if(!$_SESSION["authAdmin"]){
            header("location:../signin.php");
        }

        include "../includes/conn.php";
        // AFFICHAGE DEs COMMANDE
        
        $recupCommande = $bd->prepare("SELECT nom,prenom,telephone,email,date_commande,statu_commande,co.etat,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id where co.etat=? order by co.id desc");
        $recupCommande->execute(array(3));
        if($recupCommande->rowCount()==0){
            $msg_succes = "Les commandes de vos clients s'afficheront ici";
        }

                $selectNbCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
                $selectNbCommande->execute(array(0));
                $nb_commande = $selectNbCommande->fetch();


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
                        <table>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date de la commmande</th>
                        <th>Prix total de la commande</th>
                        <th id="statucommandeadmin">Statu de la commande</th>
                    </tr>
                    <tr>
                        <td><?=$recupC["nom"]?></td>
                        <td><?=$recupC["prenom"]?></td>
                        <td><?=$recupC["email"]?></td>
                        <td><?=$recupC["telephone"]?></td>
                        <td><?=$recupC["date_commande"]?></td>
                        <td><?=$recupC["prix_total"]?> FCFA</td>
                        <td id="enattente"><?=$recupC["statu_commande"]?></td>
                    </tr>
                    <?php if($recupC["statu_commande"]=="<p style='color:green'>Livrer<p/>"):?>

                        
                            <td><a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a></td>
                            
                        

                       

                    <?php endif;?>


                </table>
                    
                    <?php
                }?>
                
            </div>

        </div>
</body>
<script src="../js.js"></script>
</html>
