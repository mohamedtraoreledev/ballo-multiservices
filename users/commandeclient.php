<?php

    session_start();
        if(!isset($_SESSION["auth"])){
            header("location:../signin.php");
        }

        include "../includes/conn.php";
        
        if(isset($_POST["valideC"])){
            if(!empty($_POST["villeliv"]) and !empty($_POST["adresseliv"]) and !empty($_POST["latitude"]) and !empty($_POST["longitude"])){
                $villeliv = $_POST["villeliv"];
                $adresseliv = $_POST["adresseliv"];
                $adresselivGPS = str_replace(" ","+",$adresseliv);
                $latitude = $_POST["latitude"];
                $longitude = $_POST["longitude"];
                $statu = "En attente";
                $insertCommande = $bd->prepare("INSERT INTO commande(id_client,prix_total,statu_commande) values(?,?,?)");
                $insertCommande->execute(array($_SESSION["id"],$_SESSION["total"],$statu));
                echo"commande inserer";
                print_r($adresselivGPS);
                $idcommande = $bd->lastInsertId();

                $insertDetailCommande = $bd->prepare("INSERT INTO commande_details(id_commande,id_produit,id_couleur,quantite,prix,statu_produit) values(?,?,?,?,?,?)");

                foreach($_SESSION["panier"] as $id_model=>$couleurs){
                    foreach($couleurs as $couleur=>$quantite){
                        // RECUP DE L'ID DE LA COULEUR ET DU PRIX
                        $recupIdCouleur = $bd->prepare("SELECT id from couleur_models where id_model=? and couleur=?");
                        $recupIdCouleur->execute(array($id_model,$couleur));
                        $id_couleur = $recupIdCouleur->fetch();
                        if($id_couleur){
                            $statuProduit = "En attente";
                            $idCouleur = $id_couleur["id"];
                            $recupPrix = $bd->prepare("SELECT prix from model where id=?");
                            $recupPrix->execute(array($id_model));
                            $recup_Prix = $recupPrix->fetch();
                            $insertDetailCommande->execute(array($idcommande,$id_model,$idCouleur,$quantite,$recup_Prix["prix"],$statuProduit));
                            echo"detail commande inserer";
                        }
                    }
                    unset($_SESSION["panier"]);
                }
                $insertAddressLivraison = $bd->prepare("INSERT INTO livraison(id_commande,adresse,ville,latitude,longitude) values(?,?,?,?,?)");
                $insertAddressLivraison->execute(array($idcommande,$adresselivGPS,$villeliv,$latitude,$longitude));
                echo "Adresse de livraison inserer";
            }

            
        }

        // AFFICHAGE DE LA commande
        
        $recupCommande = $bd->prepare("SELECT nom,prenom,telephone,email,date_commande,co.etat,statu_commande,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id where co.id_client=? order by co.id desc");
        $recupCommande->execute(array($_SESSION["id"]));
        if($recupCommande->rowCount()==0){
            $msg_succes = "Vos commandes s'afficheront ici";
        }
?>












<!DOCTYPE html>
<html lang="en">
<?php include "../includes/header.php"?>
<body>
    <div class="header">
        <div class="logo">
            <span class="material-symbols-outlined">add_shopping_cart</span>
            <h1>BALLO <span>Multi-Services</span></h1>
        </div>
        <div class="menu">
            <a href="index.php">Acceuil</a>
            <!-- <a href="#Produits">Nos produits</a> -->
            <!-- <a href="Contacts">Contacts</a> -->
            <a href="">Promotions</a>
            <a href="../admin/logout.php">Déconnexion</a>
        </div>
        <div class="icons">
            <!-- <form action="" method="post">
                <button><span class="material-symbols-outlined">search</span></button>
                <input type="search" name="search" id="search" placeholder="recherche...">
            </form> -->
            <a href="panier.php"><span class="material-symbols-outlined">shopping_cart</span><nav>0</nav></a>
        </div>
    </div>

    <div class="band">
        <p>Retrouvez toutes vos commandes<br>faites ici chez Ballo</p>
        <div id="trait"></div>
        <h3>Ballo Multi-Services,qualité guarantie au mali</h3>
    </div>


    <div class="commandeespace">
        <h1>MES COMMANDES</h1>
        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
        <div id="traitcomment"></div>
        <?php while($recupC=$recupCommande->fetch()){
            ?>

                 <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date de la commmande</th>
                <th>Prix total de la commande</th>
                <th>Statu de la commande</th>
            </tr>
            <tr>
                <td><?=$recupC["nom"]?></td>
                <td><?=$recupC["prenom"]?></td>
                <td><?=$recupC["email"]?></td>  
                <td><?=$recupC["telephone"]?></td>
                <td><?=$recupC["date_commande"]?></td>
                <td><?=$recupC["prix_total"]?> FCFA</td>
                <td><?=$recupC["statu_commande"]?></td>
            </tr>
            
        </table>
        <div class="btncommandes">
            <?php if($recupC["statu_commande"]!="<p style='color:red'>Commande annulée<p/>" && $recupC["statu_commande"]!="<p style='color:red'>Commande refusée<p/>" && $recupC["statu_commande"]!="<p style='color:yellow'>Commande en cours de livraison<p/>"
                        && $recupC["statu_commande"]!="<p style='color:green'>Livrer<p/>"):?>
                <a href="detailscommandeclient.php?idCommande=<?=$recupC["idCommande"]?>"><button>Voir produit(s) de la commande</button></a>
                <a href="../action/annulercommandeclient.php?idCommande=<?=$recupC["idCommande"]?>"><button>Annuler la commande</button></a>
            <?php endif;?>

            <?php if($recupC["statu_commande"]=="<p style='color:yellow'>Commande en cours de livraison<p/>"):?>
            <a href="../action/livrercommande.php?idCommande=<?=$recupC["idCommande"]?>"><button>Veuillez cliquez pour<br>signaler livrer une fois<br>la livraison faite</button></a>
            <?php endif;?>
            <?php if($recupC["statu_commande"]=="<p style='color:green'>Livrer<p/>"):?>
            <a href="../action/reçu.php?idCommande=<?=$recupC["idCommande"]?>"><button>Obtenir un reçu ici !!!</button></a>
            <?php endif;?>
            </div>



            <?php
        }?>
       
        
    </div>
</body>
<script src="../js.js"></script>
</html>