<?php

    session_start();

        if(!isset($_SESSION["auth"])){

        $_SESSION["redirect_after_connect"] = $_SERVER["REQUEST_URI"];

            header("location:../signin.php");
            exit;
        }


        include "../includes/conn.php";
        
        if(isset($_POST["valideC"])){
            if(!empty($_POST["villeliv"]) and !empty($_POST["adresseliv"])){
                $villeliv = $_POST["villeliv"];
                $adresseliv = $_POST["adresseliv"];
                $adresselivGPS = str_replace(" ","+",$adresseliv);
                $latitude = $_POST["latitude"];
                $longitude = $_POST["longitude"];
                $statu = "En attente";
                
                $insertCommande = $bd->prepare("INSERT INTO commande(id_client,prix_total,statu_commande) values(?,?,?)");
                $insertCommande->execute(array($_SESSION["id"],$_SESSION["total"],$statu));
                
                
                // echo"commande inserer";
                // print_r($adresselivGPS);
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
                            // echo"detail commande inserer";
                        }
                    }
                    unset($_SESSION["panier"]);
                }
                $insertAddressLivraison = $bd->prepare("INSERT INTO livraison(id_commande,adresse,ville,latitude,longitude) values(?,?,?,?,?)");
                $insertAddressLivraison->execute(array($idcommande,$adresselivGPS,$villeliv,$latitude,$longitude));
                // echo "Adresse de livraison inserer";
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

        <div class="load">

        <?php while($recupC=$recupCommande->fetch()){ ?>

        <div class="commande-card">

            <div class="ligne">
                <strong>Nom :</strong>
                <span><?=$recupC["nom"]?></span>
            </div>

            <div class="ligne">
                <strong>Prénom :</strong>
                <span><?=$recupC["prenom"]?></span>
            </div>

            <div class="ligne">
                <strong>Email :</strong>
                <span><?=$recupC["email"]?></span>
            </div>

            <div class="ligne">
                <strong>Téléphone :</strong>
                <span><?=$recupC["telephone"]?></span>
            </div>

            <div class="ligne">
                <strong>Date :</strong>
                <span><?=$recupC["date_commande"]?></span>
            </div>

            <div class="ligne">
                <strong>Total :</strong>
                <span><?=$recupC["prix_total"]?> FCFA</span>
            </div>

            <div class="ligne">
                <strong>Statut :</strong>
                <span style="color:orange"><?=$recupC["statu_commande"]?></span>
            </div>

            

            <div class="btncommandes">

                <?php if($recupC["statu_commande"]!="<p style='color:red'>Commande annulée<p/>"
                && $recupC["statu_commande"]!="<p style='color:red'>Commande refusée<p/>"
                && $recupC["statu_commande"]!="<p style='color:yellow'>Commande en cours de livraison<p/>"
                && $recupC["statu_commande"]!="<p style='color:green'>Livrer<p/>"): ?>

                <a href="detailscommandeclient.php?idCommande=<?=$recupC["idCommande"]?>">
                    <button>Voir produit(s)</button>
                </a>

                <a href="../action/annulercommandeclient.php?idCommande=<?=$recupC["idCommande"]?>">
                    <button>Annuler</button>
                </a>

                <?php endif; ?>
                <?php if($recupC["statu_commande"]=="<p style='color:yellow'>Commande en cours de livraison<p/>"):?>
                    <a href="../action/livrercommande?idCommande=<?=$recupC["idCommande"]?>"><button>Signaler livraison faite Ici</button></a>
                <?php endif;?>
                <?php if($recupC["statu_commande"]=="<p style='color:green'>Livrer<p/>"):?>
                    <a href="../action/reçu.php?idCommande=<?=$recupC["idCommande"]?>"><button>Obtenir un reçu</button></a>
                <?php endif;?>

            </div>

        </div>

        <?php } ?>

        </div>
       
        
</body>
<script>
    setInterval('load_messages()', 5000);
    function load_messages(){
        $(".load").load("../messageInstantane/statucommandeclient.php");
    }
    

</script>
<script src="../js.js"></script>
</html>