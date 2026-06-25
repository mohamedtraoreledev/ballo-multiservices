<?php

    session_start();
        // if(!$_SESSION["auth"]){
        //     header("location:../signin.php");
        // }

        include "../includes/conn.php";

        if(isset($_GET["idmodel"]) && isset($_GET["couleur"])){
            $idmodel = $_GET["idmodel"];
            $couleur = $_GET["couleur"];

            $recupDetailModel = $bd->prepare("SELECT m.id,m.id_gamme as id_gamme,nom,prix,description,stockage,col.couleur,col.image,col.stock from model as m left join couleur_models as col on m.id=col.id_model where col.id_model=? and col.couleur=?");
            $recupDetailModel->execute(array($idmodel,$couleur));
            // if($recupDetailModel->rowCount()>0){
            //     // echo"bon"; 
            // }else{
            //     echo"non trouver";
            // }

            if(isset($_POST["valider"])){
                    if(!isset($_SESSION["auth"])){
                    header("location:../signin.php");
                }else{
                    if(!empty($_POST["commentaire"])){
                    $contenu = nl2br($_POST["commentaire"]);
                    $insertCommentaire = $bd->prepare("INSERT INTO commentaire(id_client,id_model,contenu) values(?,?,?)");
                    $insertCommentaire->execute(array($_SESSION["id"],$idmodel,$contenu));
                    // echo"comm ok";

                }
                }
               
            }
            
                    $selectCommentaire = $bd->prepare("SELECT c.nom,contenu,date_commentaire FROM commentaire as comm join client as c on comm.id_client=c.id join model as m on comm.id_model=m.id where comm.id_model=?");
                    $selectCommentaire->execute(array($idmodel));


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
        <a href="panier.php"><span class="material-symbols-outlined">pan_tool_alt</span><span class="material-symbols-outlined">shopping_cart</span>
            <nav>
                <?php
                $total = 0;

                if(isset($_SESSION["panier"])){
                    foreach($_SESSION["panier"] as $model){

                        if(is_array($model)){
                            $total += array_sum($model);
                        } else {
                            // cas ancien format
                            $total += $model;
                        }

                    }
                }

                echo $total;
                ?>
            </nav>
        </a>
        </div>
    </div>
    <?php if(isset($recupDetailModel)): ?>
    <?php while($recupDetail = $recupDetailModel->fetch()){
        ?>
            <div class="allpage">
                <div class="imgdetails">
                    <img src="../imagebdd/<?=$recupDetail["image"]?>" alt="">
                </div>
                <div class="infosproduit">
                    <h2><?=$recupDetail["nom"]?></h2>
                    <p>Capacité : <?=$recupDetail["stockage"]?></p>
                    <p>Couleur : <?=$recupDetail["couleur"]?></p>
                    <p id="prix">Prix : <?=$recupDetail["prix"]?> FCFA</p>
                    <div id="traitdetails"></div>
                    <p><?=$recupDetail["description"]?></p>
                    <button><a href="panier.php?idmodel=<?=$recupDetail["id"]?>&couleur=<?=$recupDetail["couleur"]?>">Ajouter au panier</a><button>
                    <a href="">Acheter maintenant<p>Paiement en ligne<br>bientot disponible</p></a>
                </div>
            </div>
        <?php
    }?>
    <?php endif;?>
    
    <div class="commentspace">
        <form action="" method="post">
            <label for="comment">Commentaire</label>
            <textarea name="commentaire" id="comment"placeholder="Qu'en pensez-vous de cet iphone..."></textarea>
            <input type="submit" name="valider" id="valider" value="publier">
        </form>
    </div>
    <?php if(isset($selectCommentaire)):?>
    <?php while($comm = $selectCommentaire->fetch()){
        ?>
            <div class="affcomment">
                <h1><?=$comm["nom"]?></h1>
                <div id="traitcomment"></div>
                <div class="contcomment">
                    <?=$comm["contenu"]?>
                </div>
                <div id="traitcomment"></div>
                <div class="datepublic">
                    <h2><i>Date de publication : </i><?=$comm["date_commentaire"]?></h2>
                </div>
            </div>
        
        <?php
    }?>
    <?php endif;?>

</body>
</html>