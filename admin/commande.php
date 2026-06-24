<?php



    session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }

        include "../includes/conn.php";
        // AFFICHAGE DEs COMMANDE
        
        $recupCommande = $bd->query("SELECT nom,prenom,telephone,adresse,email,date_commande,statu_commande,co.etat,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id join livraison as liv on liv.id_commande=co.id order by co.id desc");
        
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
                <a href="venteboutique.php"><span class="material-symbols-outlined">store</span>Boutique Ventes</a>
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
                <div class="load">

                <div class="mobile-commandes">
                <?php while($recupC = $recupCommande->fetch()){
                    ?>
                
                
                    <div class="commande-card-admin">

                        <p><strong>Nom :</strong> <?=$recupC["nom"]?></p>

                        <p><strong>Prénom :</strong> <?=$recupC["prenom"]?></p>

                        <p><strong>Email :</strong> <?=$recupC["email"]?></p>

                        <p><strong>Téléphone :</strong> <?=$recupC["telephone"]?></p>

                        <p><strong>Date :</strong> <?=$recupC["date_commande"]?></p>

                        <p><strong>Total :</strong> <?=$recupC["prix_total"]?> FCFA</p>

                        <p><strong>Adresse :</strong> <?=$recupC["adresse"]?></p>

                        <p><strong>Statut :</strong> <?=$recupC["statu_commande"]?></p>

                    </div>
                    <?php if($recupC["statu_commande"]!="<p style='color:red'>Commande refusée<p/>" && $recupC["statu_commande"]!="<p style='color:red'>Commande annulée<p/>" && $recupC["statu_commande"]!="<p style='color:green'>Livrer<p/>"):?>

                        <?php if($recupC["statu_commande"]=="En attente" || $recupC["statu_commande"]=="Stock disponible"):?>
                            <a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a>
                            <a href="../action/acceptercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Accepter la commande</button></a>
                            <a href="../action/refusercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Refuser la commande</button></a>
                        <?php endif;?>

                        <?php if($recupC["statu_commande"]=="<p style='color:green'>Acceptée<p/>"):?>
                            <a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a>
                            <a href="../action/refusercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Refuser la commande</button></a>
                            <a href="../action/expediercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Expédier la commande</button></a>
                        <?php endif;?>

                        <?php if($recupC["statu_commande"]=="<p style='color:yellow'>Commande en cours de livraison<p/>"):?>
                            <a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a>
                            <a href="../action/refusercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Refuser la commande</button></a>
                        <?php endif;?>

                        <?php if($recupC["statu_commande"]=="<p style='color:red'> Manque de stock <p/>"):?>
                            <a href="detailscommandeadmin.php?idCommande=<?=$recupC["idCommande"]?>"><button id="voircommande">Voir produit(s) de la commande</button></a>
                            <a href="../action/attentecommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Mettre en attente</button></a>
                        <?php endif;?>

                    <?php endif;?>

                    
                
                    
                    <?php
                }?>
                </div>
                </div>
                
            </div>

        </div>
</body>
<script>


    setInterval('load_messages()',5000);
    function load_messages(){
        $(".load").load("../messageInstantane/statucommandeadmin.php");
    }
    

</script>
<script src="../js.js"></script>
</html>