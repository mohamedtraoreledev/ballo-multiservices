<?php

    session_start();
        // if(!$_SESSION["auth"]){
        //     header("location:../signin.php");
        // }

        include "../includes/conn.php";

        if(!isset($_SESSION["panier"])){
            $_SESSION["panier"] = array();
        }

        if(isset($_GET["idmodel"]) && isset($_GET["couleur"])){
            $getIdModelForPanier = $_GET["idmodel"];
            $getCouleurForPanier = $_GET["couleur"];
            if(!isset($_SESSION["panier"][$getIdModelForPanier])){
                $_SESSION["panier"][$getIdModelForPanier]=[];
            }
            
            if(isset($_SESSION["panier"][$getIdModelForPanier][$getCouleurForPanier])){
                $_SESSION["panier"][$getIdModelForPanier][$getCouleurForPanier]++;
            }else{
                $_SESSION["panier"][$getIdModelForPanier][$getCouleurForPanier]=1;
            }

            header("location:details.php?idmodel=".$getIdModelForPanier."&couleur=".$getCouleurForPanier);
            // unset($_SESSION["panier"]);
        }

        if(isset($_GET["del"]) && isset($_GET["delcouleur"])){
            $del = $_GET["del"];
            $delcouleur = $_GET["delcouleur"];
            if(!isset($_SESSION["panier"][$del])){
                $_SESSION["panier"][$del]=[];
            }
            if(isset($_SESSION["panier"][$del][$delcouleur])){
                unset($_SESSION["panier"][$del][$delcouleur]);
            }

        }
        if(isset($_GET["red"]) && isset($_GET["redcouleur"])){
            $red = $_GET["red"];
            $redcouleur = $_GET["redcouleur"];
            if(!isset($_SESSION["panier"][$red])){
                $_SESSION["panier"][$red]=[];
            }
            if(isset($_SESSION["panier"][$red][$redcouleur])){
                if($_SESSION["panier"][$red][$redcouleur]>1){
                    $_SESSION["panier"][$red][$redcouleur]--;
                }else{
                    unset($_SESSION["panier"][$red][$redcouleur]);
                }
            }
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
            <!-- <a href="#footer">Contacts</a> -->
            <a href="">Promotions</a>
            <a href="../admin/logout.php">Déconnexion</a>
        </div>
        <div class="icons">
            <!-- <form action="" method="post">
                <button><span class="material-symbols-outlined">search</span></button>
                <input type="search" name="search" id="search" placeholder="recherche...">
            </form> -->
            <a href="panier.php"><span class="material-symbols-outlined">shopping_cart </span>
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

    <div class="panierpage">
        <h1>Mon panier</h1>
        
        
            <?php
            $total=0;
            $array_keys = array_keys($_SESSION["panier"]);
            // var_dump($array_keys);
            if(empty($array_keys)){
                echo "Votre panier est vide pour le moment,qu'attendez-vous pour choisir et commander ?";
            }else{
                
                foreach($_SESSION["panier"] as $id=>$couleurs){
                    foreach($couleurs as $couleur=>$quantite){
                        $recupPanier = $bd->prepare("SELECT*FROM model as m join couleur_models as col on m.id=col.id_model where m.id=? and col.couleur=?");
                        $recupPanier->execute(array($id,$couleur));
                        $produit = $recupPanier->fetch();
                    
                        if($produit){
                            $total += $produit["prix"]* intval($quantite);
                            $_SESSION["total"] = $total;
                
                    ?>
                        <div class="contentpanier">
                        
                            <img src="../imagebdd/<?=$produit["image"]?>" alt="">
                            <h4><?=$produit["nom"]?></h4>
                            <p><?=$produit["stockage"]?></p>
                            <p>Quantité <strong><?=$quantite?></strong></p>
                            <p><?=$produit["couleur"]?></p>
                            <h4><?=$produit["prix"]?> FCFA</h4>
                            <div class="btn">
                                <a href="panier.php?del=<?=$id?>&delcouleur=<?=$couleur?>"><span class="material-symbols-outlined">delete</span></a>
                                <a href="panier.php?red=<?=$id?>&redcouleur=<?=$couleur?>"><button><span class="material-symbols-outlined">remove</span></button></a>
                            </div>
                        </div>
                        <div id="traitcomment"></div>
                    <?php
                }
                }
                }
            }
            
        ?>
        <div class="total">TOTAL :<?=$total?>FCFA</div>
        <a href="index.php"><div class="continuerachat">Continuer vos achats</div></a>
        <a href="commandeclient.php"><div class="continuerachat">Mes commandes</div></a>
    </div>

    <div class="infoslivraison">
        <legend>Information de Livraison de la commande</legend>
        <form action="commandeclient.php" method="post" class="myform">
            <div class="localite">
                <label for="ville">Ville de Livraison</label>
                <input type="text" name="villeliv" placeholder="Bamako..." required="required">
                <label for="ville">Adresse de Livraison</label>
                <input type="text" name="adresseliv" placeholder="Adeken près du cap..." required="required">
                <label for="ville">Latitude</label>
                <input type="text" name="latitude" placeholder="47758559.384..." required="required" value="">
                <label for="ville">longitude</label>
                <input type="text" name="longitude" placeholder="85969496.54..." required="required" value="">
            </div>
            <div class="btncommande">
                <input type="submit" value="PASSER LA COMMANDE" name="valideC">
            </div>
        </form>

         <script type="text/javascript">
               document.addEventListener("DOMContentLoaded", () => {
                alert("Autoriser la localisation pour la livraison en cas de commande");

    console.log("DOM chargé");

    if (navigator.geolocation) {
        console.log("Géolocalisation supportée");

        navigator.geolocation.getCurrentPosition(
            function(position) {
                console.log("Position trouvée !");
                console.log(position.coords.latitude, position.coords.longitude);

                document.querySelector('input[name="latitude"]').value = position.coords.latitude;
                document.querySelector('input[name="longitude"]').value = position.coords.longitude;
            },
            function(error) {
                console.log("Erreur géolocalisation :", error);
            }
        );

    } else {
        console.log("Géolocalisation non supportée");
    }

});
            </script>
    </div>
</body>
</html>