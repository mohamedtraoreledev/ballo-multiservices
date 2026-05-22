<?php



    session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }

        include "../includes/conn.php";
        // AFFICHAGE DEs COMMANDE
        
        $recupCommande = $bd->query("SELECT nom,prenom,telephone,email,date_commande,statu_commande,co.etat,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id order by co.id desc");
        
        if($recupCommande->rowCount()==0){
            $msg_succes = "Les commandes de vos clients s'afficheront ici";
        }

                // $selectNbCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
                // $selectNbCommande->execute(array(0));
                // $nb_commande = $selectNbCommande->fetch();

                $updatevp = $bd->prepare("UPDATE commande set vp=?");
                $updatevp->execute(array(1));

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
        <div class="navbar">
            <div class="logo">
                <span class="material-symbols-outlined">add_shopping_cart</span>
                <h1>BALLO<span>Multi-Services</span></h1>
            </div>
            <div class="lienmenu">
                <a href="Acceuil.php"><span class="material-symbols-outlined">home</span>Acceuil</a>
                <a href="produit.php"><span class="material-symbols-outlined">add_shopping_cart</span>Produits</a>
                <a href="client.php"><span class="material-symbols-outlined">accessibility_new</span>Clients</a>
                <a href="commande.php"><span class="material-symbols-outlined">inventory_2</span>Commandes<nav class="nbcommande">0</nav></a>
                <a href="vente.php"><span class="material-symbols-outlined">store</span>Ventes<nav class="nbvente"><?=$nb_vente["nbvente"]?></nav></a>
                <a href="livraison.php"><span class="material-symbols-outlined">local_shipping</span>Livraison</a>
                <a href="logout.php"><span class="material-symbols-outlined">logout</span>Déconnexion</a>
            </div>
        </div>

        <div class="Contentadmin">

            <div class="nommenu">
                <h1><?php
            echo ucfirst(str_replace(".php","",basename($_SERVER["PHP_SELF"])));
        ?></h1>
            </div>
            
            <div class="commandeespaceadmin">
                <h1>MES COMMANDES</h1>
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
                    <?php if($recupC["statu_commande"]!="<p style='color:red'>Commande refusée<p/>" && $recupC["statu_commande"]!="<p style='color:red'>Commande annulée<p/>" && $recupC["statu_commande"]!="<p style='color:green'>Livrer<p/>"):?>

                        <?php if($recupC["statu_commande"]=="En attente" || $recupC["statu_commande"]=="Stock disponible"):?>
                            <td><a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a></td>
                            <td><a href="../action/acceptercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Accepter la commande</button></a></td>
                            <td><a href="../action/refusercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Refuser la commande</button></a></td>
                        <?php endif;?>

                        <?php if($recupC["statu_commande"]=="<p style='color:green'>Acceptée<p/>"):?>
                            <td><a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a></td>
                            <td><a href="../action/refusercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Refuser la commande</button></a></td>
                            <td><a href="../action/expediercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Expédier la commande</button></a></td>
                        <?php endif;?>

                        <?php if($recupC["statu_commande"]=="<p style='color:yellow'>Commande en cours de livraison<p/>"):?>
                            <td><a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a></td>
                            <td><a href="../action/refusercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Refuser la commande</button></a></td>
                        <?php endif;?>

                        <?php if($recupC["statu_commande"]=="<p style='color:red'> Manque de stock <p/>"):?>
                            <td><a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a></td>
                            <td><a href="../action/attentecommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Mettre en attente</button></a></td>
                        <?php endif;?>

                    <?php endif;?>


                </table>
                    
                    <?php
                }?>
                
            </div>

        </div>
</body>
<script src="../js.js"></script>
</html>