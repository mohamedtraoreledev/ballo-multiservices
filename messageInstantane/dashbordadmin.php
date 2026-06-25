<?php
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