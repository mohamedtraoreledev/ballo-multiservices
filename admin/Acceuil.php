<?php
        session_start();
        if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }


        include "../includes/conn.php";

        $selectNbClient = $bd->query("SELECT COUNT(*) as nbclient from client");
        $nb_client = $selectNbClient->fetch();


        $selectNbCommande = $bd->query("SELECT COUNT(*) as nbcommande from commande");
        $nb_commande = $selectNbCommande->fetch();

        $selectvpCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
        $selectvpCommande->execute(array(0));
        $nb_vp = $selectvpCommande->fetch();


        $selectCA= $bd->prepare("SELECT sum(prix_total) as ca from commande where etat=?");
        $selectCA->execute(array(3));
        $nb_CA = $selectCA->fetch();


        $selectStockProduit = $bd->query("SELECT*from model as m join couleur_models as col on m.id=col.id_model");

        $recupPlusVendu = $bd->prepare("SELECT nom,stockage,couleur,image,c.etat,quantite,date_commande,cd.prix,statu_produit,col.id as id_couleur,cd.id_commande as id_commande FROM commande_details as cd join model as m on cd.id_produit=m.id join couleur_models as col on cd.id_couleur=col.id join commande c on c.id=cd.id_commande where c.etat=?");
        $recupPlusVendu->execute(array(3));


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
                
                <a href="commande.php"><span class="material-symbols-outlined">inventory_2</span>Commandes<nav class="nbcommande"><?=$nb_vp["nbcommande"]?></nav></a>
                
                <a href="vente.php"><span class="material-symbols-outlined">store</span>Ventes<nav class="nbvente"><?=$nb_vente["nbvente"]?></nav></a>
                <a href="venteboutique.php"><span class="material-symbols-outlined">store</span>Boutique Ventes</a>
                <a href="livraison.php"><span class="material-symbols-outlined">local_shipping</span>Livraison</a>
                <a href="logout.php"><span class="material-symbols-outlined">logout</span>Déconnexion</a>
            </div>
        </div>

        <div class="Contentadmin">

                <div class="nommenu">
                    <h1> <?php echo ucfirst(str_replace(".php","",basename($_SERVER["PHP_SELF"])));?></h1>
                </div>

            <div class="load">

            
                <div class="statisadmin">
                    <div class="statisclientnb">
                        <h5><?=$nb_client["nbclient"]?><br><span><span class="material-symbols-outlined">accessibility_new</span>Clients</span></h5>
                    </div>
                    <div class="statiscommandenb">
                        <h5><?=$nb_commande["nbcommande"]?><br><span><span class="material-symbols-outlined">inventory_2</span>Commandes</span></h5>
                    </div>
                    <div class="statisCA">
                        <h5><?=$nb_CA["ca"]." FCFA "?><br><span><span class="material-symbols-outlined">bar_chart</span>Chiffre affaire (ca)</span></h5>
                    </div>
                </div>
                <div id="trait"></div>


                <div class="statisadmintwo">
                        <div class="sectionproduitvendu">
                            <h5>Produits vendus</h5>
                            
                                <?php while($recupPlusV = $recupPlusVendu->fetch()){
                                    ?>
                                        <div class="contentproduitvendu">
                                            <p><?=$recupPlusV["nom"]." ".$recupPlusV["couleur"]." ".$recupPlusV["stockage"]." ".$recupPlusV["date_commande"]?></p>
                                        </div>
                                        
                                    
                                    <?php
                                }?>
                                
                        </div>
                        <div class="sectionproduitStock">
                            <h5>Produits Stock</h5>
                            <?php while($StockProduit = $selectStockProduit->fetch()){
                                ?>
                                    <div class="contentproduitStock">
                                        <p><?=$StockProduit["nom"]." ".$StockProduit["stockage"]." ".$StockProduit["couleur"]." "."stock : ".$StockProduit["stock"]?></p>
                                    </div>
                                
                                
                                <?php
                            }?>
                        
                        </div>
                </div>
            </div>

        </div>
</body>
<script>


  
setInterval(function(){

    $(".load").load("../messageInstantane/dashbordadmin.php");

    $.get("../messageInstantane/commandenb.php", function(data){
        $(".nbcommande").text(data);
    });

    $.get("../messageInstantane/nbvente.php", function(data){
        $(".nbvente").text(data);
    });

},5000);
    

</script>

</html>