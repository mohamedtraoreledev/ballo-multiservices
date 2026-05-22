<?php
        session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }
        include "../includes/conn.php";

        if(!empty($_GET["idgamme"])){
            $getIdGamme =$_GET["idgamme"];
            // var_dump($_GET["idgamme"]);
            $recupIdGamme = $bd->prepare("SELECT * FROM gamme where id=?");
            $recupIdGamme->execute(array($getIdGamme));
            if($recupIdGamme->rowCount()>0){
                $recupModel = $bd->prepare("SELECT m.id,m.id_gamme as id_gamme,nom,prix,description,stockage,col.couleur,col.image,col.stock from model as m left join couleur_models as col on m.id=col.id_model where m.id_gamme=?");
                $recupModel->execute(array($getIdGamme));
                if($recupModel->rowCount()>0){

                $produits = [];
                while($row = $recupModel->fetch(PDO::FETCH_ASSOC)){
                    $id =$row["id"];
                    
                    if(!isset($produits[$id])){
                        $produits[$id] = [
                            'nom'=> $row["nom"],
                            'prix'=>$row["prix"],
                            'stockage'=>$row["stockage"],
                            'description'=>$row["description"],
                            'couleurs'=>[]
                        ];
                    }
                        if($row["couleur"]){
                            $produits[$id]["couleurs"][]=[
                                'couleur'=> $row["couleur"],
                                'image'=> $row["image"],
                                'stock'=> $row["stock"]
                            ];
                        }
                    } 
                    // RECUPERATION DU NOMBRE DE COULEUR DE CHAQUE MODEL
                    if(isset($id)){
                    $recupNbCouleur = $bd->prepare("SELECT count(couleur) as nbCoul from couleur_models where id_model=?");
                    $recupNbCouleur->execute(array($id));
                    $nb_coul = $recupNbCouleur->fetch();
                    $nbCouleur = $nb_coul["nbCoul"];
                    }
                }else{
                    $msg_succes="Pas de model dans la gamme pour le moment";
                }

                }
                // print_r($recupModel->errorInfo());
                // if($recupModel->rowCount()>0){
                //     echo "<p style='color:red'>Model recuperer</p>";
                // }else{
                //     $msg_succes = "Pas de model dans la gamme";
                // }

                
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
<body>
    <section  class="pageadmin">
    
    <?php include "../includes/navbar.php"?>

        <div class="Contentadmin">

            <div class="nommenu">
                <h1><?php
            echo ucfirst(str_replace(".php","",basename($_SERVER["PHP_SELF"])));
        ?></h1>
            </div>

            <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
            <section id="Produits">
                
                <?php if(isset($produits)) : ?>
                <?php foreach ($produits as $id => $produit) :?>
                    <?php if(isset($produit["couleurs"][0])) : ?>
                    <div class="allproduitmin">
                        <div class="img">
                            <img class="imageProduit" src="../imagebdd/<?=$produit["couleurs"][0]["image"]?>" alt="">
                        </div>
                        <div class="nomprix">
                            <p><?= $produit["nom"]?></p>
                            <p><?= $produit["prix"]?> FCFA</p>
                            <p><?= $produit["description"]?></p>
                            <p><?= $produit["stockage"]?></p>
                            <p class="coul"><?=$produit["couleurs"][0]["couleur"]?></p>
                            <p>Quantité <strong><?=$produit["couleurs"][0]["stock"]?></strong></p>
                        </div>

                        <div class="couleurs">
                            <?php foreach($produit["couleurs"] as $c):?>
                                <span class="couleur" data-stock="<?=$c["stock"]?>" data-couleur="<?=$c["couleur"]?>" data-img="../imagebdd/<?=$c["image"]?>" style="background:<?=$c["couleur"]?>; border:1px solid #000;"></span>
                            <?php endforeach;?>
                        </div>
                        <?php if(isset($nbCouleur)):?>
                            <?php if($nbCouleur>1):?>
                        <div class="btndetails">
                            <a class="SuppCoul" href="delete.php?idmodel=<?=$id?>&couleur=<?=$produit["couleurs"][0]["couleur"]?>">Supprimer la couleur</a>
                            <a class="suppModel"href="delete.php?idmodel=<?=$id?>">Supprimer le model</a>
                            <a class ="ModifModel"href="edit.php?idmodel=<?=$id?>&couleur=<?=$produit["couleurs"][0]["couleur"]?>&idgamme=<?=$getIdGamme?>">Modifier</a>
                            <a href="ajoutCouleur.php?idmodel=<?=$id?>&idgamme=<?=$getIdGamme?>">Ajouter couleur</a>
                        </div>
                            <?php endif;?>
                            <?php if($nbCouleur<=1):?>
                                <div class="btndetails">
                                    
                                    <a class="suppModel"href="delete.php?idmodel=<?=$id?>">Supprimer le model</a>
                                    <a class ="ModifModel" href="edit.php?idmodel=<?=$id?>&couleur=<?=$produit["couleurs"][0]["couleur"]?>&idgamme=<?=$getIdGamme?>">Modifier</a>
                                    <a href="ajoutCouleur.php?idmodel=<?=$id?>&idgamme=<?=$getIdGamme?>">Ajouter couleur</a>

                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        
                    </div>
                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
                

            </section>

        </div>
    </section>
            
</body>
<script src="../js.js"></script>
</html>
