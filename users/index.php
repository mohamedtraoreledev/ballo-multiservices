<?php
        session_start();
        // if(!$_SESSION["auth"]){
        //     header("location:../signin.php");
        // }

        include "../includes/conn.php";

        $recupGamme = $bd->query("SELECT * FROM gamme");
             
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
            <a href="#Produits">Nos produits</a>
            <a href="#footer">Contacts</a>
            <a href="">Promotions</a>
            <a href="../admin/logout.php">Déconnexion</a>
        </div>
        <div class="icons">
            <form action="" method="post">
                <button type="submit"><span class="material-symbols-outlined">search</span></button>
                <input type="search" name="search" id="search" placeholder="recherche...">
            </form>
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
        <div class="btndetailsconn">
            <a href="../signin.php">Connexion</a>
        </div>
    </div>

    <div class="band">
        <p>Achetez vos iPhones<br>originaux aux meilleurs prix</p>
        <div id="trait"></div>
        <h3>Ballo Multi-Services,qualité guarantie au mali</h3>
    </div>
    <div class="indProduit">
            <h4 class="pofproduct">
                Nos modèles populaires
            </h4>
            <sup>Découvrez nos iphones</sup>
    </div>

                    
    <section id="Produits">
        <?php while($gamme = $recupGamme->fetch()){
            ?>
                <div class="allproduit">
                    <div class="img">
                        <img src="../image/<?=$gamme["imagegamme"]?>" alt="">
                    </div>
                    <div class="nomprix">
                        <p><?=$gamme["nom"]?></p>
                    </div>
                    <div class="btndetails">
                        <a href="voirproduit.php?idgamme=<?=$gamme["id"]?>">Voir</a>
                    </div>
                </div>
            <?php
        }?>
        
    </section>

    <div class="qualite">
            <p>Pourquoi choisir Ballo Multi-Services ?</p>
            <div class="allqual">
                <div class="q1">
                    <span class="material-symbols-outlined">verified</span>
                    <div><h2>Produit 100%</h2>
                    <sup>Authentique</sup></div>
                    
                </div>
                <div class="q1">
                    <span class="material-symbols-outlined">motorcycle</span>
                    <div><h2>Livraison rapide<br></h2>
                    <sup>Payement en ligne ou physique</sup></div>
                    
                </div>
                <div class="q1">
                    <span class="material-symbols-outlined">support_agent</span>
                    <div><h2>Support client 24/07<br></h2></div>
                    
                    
                </div>
            </div>
    </div>
    
    <div class="qualit">
            <p>Pret à commander votre iphone ? </p>
            <sup>Passer votre commmande en quelques clics !</sup>
    </div>

    <div id="footer">
        <div class="sign">
            <div id="traitfoott"></div>
            <p>@ 2026 Ballo Multi-Services.Tous droits reservés</p>
            <div id="traitfoot"></div>
            
        </div>
        
        <div class="imglogo">
            <a href="facebook.com"><img src="../image/facebook.png" alt=""></a>
            <a href="twitter.com"><img src="../image/twitter.png" alt=""></a>
            <a href="vimeo.com"><img src="../image/vimeo.png" alt=""></a>
        </div>
    </div>
    
</body>
</html>