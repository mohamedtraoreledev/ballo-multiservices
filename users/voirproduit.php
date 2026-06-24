<?php
        session_start();
        // if(!$_SESSION["auth"]){
        //     header("location:../signin.php");
        // }
        include "../includes/conn.php";

        if(!empty($_GET["idgamme"])){
            $getIdGamme =$_GET["idgamme"];
            // var_dump($_GET["idgamme"]);
            $recupIdGamme = $bd->prepare("SELECT * FROM gamme where id=?");
            $recupIdGamme->execute(array($getIdGamme));
            $Gamm = $recupIdGamme->fetch();
            if($recupIdGamme->rowCount()>0){
                $recupModel = $bd->prepare("SELECT m.id,m.id_gamme as id_gamme,nom,prix,description,stockage,col.couleur,col.image,col.stock from model as m left join couleur_models as col on m.id=col.id_model where m.id_gamme=?");
                $recupModel->execute(array($getIdGamme));
                // print_r($recupModel->errorInfo());
                if($recupModel->rowCount()==0){
                    $msg_succes = "Pas de model dans la gamme,revenez plus tard";
                }

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
                    // if(isset($id)){
                    // $recupNbCouleur = $bd->prepare("SELECT count(couleur) as nbCoul from couleur_models where id_model=?");
                    // $recupNbCouleur->execute(array($id));
                    // $nb_coul = $recupNbCouleur->fetch();
                    // $nbCouleur = $nb_coul["nbCoul"];
                    // }
                }
            }else{
                echo "Cette gamme n'existe pas encore";
            }

            if(isset($_GET["btnrech"]) and isset($_GET["idgamme"])){
                if(!empty($_GET["search"])){
                    $search = $_GET["search"];
                    $r = "%".$search."%";
                    $recupModel = $bd->prepare("SELECT m.id,m.id_gamme as id_gamme,nom,prix,description,stockage,col.couleur,col.image,col.stock from model as m join couleur_models as col on m.id=col.id_model where m.id_gamme=? AND CONCAT(m.nom, ' ', m.prix, ' ', COALESCE(col.couleur, '')) LIKE ? ");
                    $recupModel->execute(array($getIdGamme,$r));
                    if($recupModel->rowCount()==0){
                    $msg_succes = "Pas de model dans la gamme,revenez plus tard";
                }

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
                    // if(isset($id)){
                    // $recupNbCouleur = $bd->prepare("SELECT count(couleur) as nbCoul from couleur_models where id_model=?");
                    // $recupNbCouleur->execute(array($id));
                    // $nb_coul = $recupNbCouleur->fetch();
                    // $nbCouleur = $nb_coul["nbCoul"];
                    // }
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
            <a href="#Produits">Nos produits</a>
            <a href="#footer">Contacts</a>
            <a href="">Promotions</a>
            <a href="../admin/logout.php">Déconnexion</a>
        </div>
        <div class="icons">
            <form action="" method="get">
                <input type="hidden" name="idgamme" value="<?= $_GET['idgamme'] ?? '' ?>">
                <button type="submit" name="btnrech"><span class="material-symbols-outlined">search</span></button>
                <input type="search" name="search" id="search" placeholder="recherche...">
            </form>
            <a href="panier.php"><span class="material-symbols-outlined">shopping_cart </span><nav>0</nav></a>
        </div>
    </div>

    <div class="band">
        <?php if(isset($Gamm)):?>
        <p>Bienvenue ici, vous trouverez tous vos modèles d'<?=$Gamm["nom"]?><br>originaux aux meilleurs prix</p>
        <div id="trait"></div>
        <h3>Ballo Multi-Services,qualité guarantie au mali</h3>
        <?php endif;?>
    </div>
    <div class="indProduit">
            <h4 class="pofproduct">
                Nos modèles populaires
            </h4>
            <sup>Découvrez nos iphones</sup>
    </div>
        <div class="msgmodel">
            <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
        </div>
    <section id="Produits">
        <?php if(isset($produits)) : ?>
                <?php foreach ($produits as $id => $produit) :?>
                    <?php if(isset($produit["couleurs"][0])) : ?>
        <div class="allproduit">
            <div class="img">
                <img class="imageProduit" src="../imagebdd/<?=$produit["couleurs"][0]["image"]?>" alt="">
            </div>
            <div class="nomprix">
                <p><?=$produit["nom"]?></p>
                <p><?=$produit["prix"]?> FCFA</p>
            </div>
            <?php
                $traductionCouleur = [
                    "rouge" => "red",
                    "bleu" => "blue",
                    "vert" => "green",
                    "jaune" => "yellow",
                    "noir" => "black",
                    "blanc" => "white",
                    "gris" => "gray",
                    "orange" => "orange",
                    "violet" => "purple",
                    "rose" => "pink",
                    "marron" => "brown"
                ];
            
            ?>
            <div class="couleurs">
                <?php foreach($produit["couleurs"] as $c):
                    $couleurCSS = $traductionCouleur[strtolower($c["couleur"])] ?? "black";
                ?>
                <span class="couleur" data-couleur="<?=$c["couleur"]?>" data-img="../imagebdd/<?=$c["image"]?>" style="background:<?=$couleurCSS?>"></span>
                <?php endforeach;?>
            </div>
            <div class="btndetails">
                <a class="detail" href="details.php?idmodel=<?=$id?>&couleur=<?=$produit["couleurs"][0]["couleur"]?>"> Voir détails ></a>
            </div>
        </div>
    <?php endif;?>
    <?php endforeach;?>
    <?php endif;?>

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
<script src="../js.js"></script>
</html>