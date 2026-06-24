

                
                <?php
                session_start();
                include "../includes/conn.php";

                $recupCommande = $bd->query("SELECT nom,prenom,telephone,adresse,email,date_commande,statu_commande,co.etat,prix_total, co.id as idCommande from commande as co join client as c on co.id_client=c.id join livraison as liv on liv.id_commande=co.id order by co.id desc");
        
        if($recupCommande->rowCount()==0){
            $msg_succes = "Les commandes de vos clients s'afficheront ici";
        }
                while($recupC = $recupCommande->fetch()){
                    ?>
                <div class="mobile-commandes">

                
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