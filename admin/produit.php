<?php
        session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }
        include "../includes/conn.php";

        // RECUP DES NOMS DES GAMMES DANS BD
        $recupGamme = $bd->query("SELECT * FROM gamme"); 
        // fin

        // INSERTION  DES DONNÉES  DU PRODUIT  DANS LA TABLE MODEL ET MODEL COULEUR
        if(isset($_POST["valider"])){
            // INSERTION DU  MODEL
            if(!empty($_POST["model"]) and !empty($_POST["prix"]) 
                and !empty($_POST["stockage"]) and !empty($_POST["descript"])
                and !empty($_POST["couleur"]) and !empty($_POST["stock"])
                and !empty($_FILES["image"]) and !empty($_POST["gamme_id"])){
                        $nom_model = $_POST["model"];
                        $prix = $_POST["prix"];
                        $stockage = $_POST["stockage"];
                        $description = $_POST["descript"];
                        $gamme_id = $_POST["gamme_id"];
                        $insertProduit = $bd->prepare("INSERT INTO model(nom,prix,description,id_gamme,stockage) values(?,?,?,?,?)");
                        $insertProduit->execute(array($nom_model,$prix,$description,$gamme_id,$stockage));
                        $id_model = $bd->lastInsertId();
                        echo "model inserer";
            // COMME Y'A PLUSIEURS COULEURS IMAGE ET STOCK SUIVANT LA COULEUR POUR UN MODEL ON BOUCLE SUR LA COULEUR ET ON INSERE POUR CHAQUE CAS
                    for($i=0;$i<count($_POST["couleur"]);$i++){ //POUR CHAQUE COULEUR DONNÉE
                        $couleur = $_POST["couleur"][$i];
                        $stock = $_POST["stock"][$i];
                        $image_nom = $_FILES["image"]["name"][$i];
                        $tmp_name = $_FILES["image"]["tmp_name"][$i];
                        $time = time();
                        $newname_image = $time.$image_nom;
                        $uploadDossier = move_uploaded_file($tmp_name,"../imagebdd/".$newname_image);
                    if($uploadDossier){
                        // $couleur = $_POST["couleur"];
                        // $stock = $_POST["stock"];
                        $insertCouleurModel = $bd->prepare("INSERT INTO couleur_models(id_model,couleur,image,stock) values(?,?,?,?)");
                        $insertCouleurModel->execute(array($id_model,$couleur,$newname_image,$stock));
                        echo "Couleur insérer";
                    }else{
                        $msg_error = "image non insérer";
                    }
                    }
                
                }else{
                    $msg_error = "Veuillez remplir tous les champs d'informations";
                }
        }

        // RECUPERATION DES PRODUITS (la gamme) 
        $recupProduit = $bd->query("SELECT * FROM gamme");
        if($recupProduit->rowCount()==0){
            $msg_succes = "Pas de gamme insérer";
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

            <div class="formajoutproduit">
                <span class="material-symbols-outlined" id="closebtn">close</span>
                <form action="" method="post" enctype="multipart/form-data" class="ajoutproduit">
                    <legend>Ajouter un produit</legend>
                    <h2>Veuillez spécifiez la gamme et le nom du modèle</h2><br>
                    <label for="marque">Gamme</label><br>
                    <div class="select">
                            <select name="gamme_id" id="">
                    <?php while($gamme = $recupGamme->fetch()){
                        ?>
                                <option value="<?=$gamme['id']?>"><?=$gamme['nom']?></option>
                            
                        <?php
                    } ?>
                        </select>
                    </div>
                    <!-- <input type="text" name="gamme" id="gamme" placeholder="Iphone 17"><br> -->

                    <!-- <label for="image">Image de la gamme</label><br>
                    <input type="file" name="imagegamme" id="image"><br> -->
                    
                    <label for="model">Nom du model de la gamme</label><br>
                    <input type="text" name="model" id="model" placeholder="Iphone 17 pro-max"><br>

                    <label for="prix">Prix</label><br>
                    <input type="number" name="prix" id="prix" placeholder="250000FCFA"><br>

                    <label for="stockage">Stockage</label><br>
                    <input type="text" name="stockage" id="stockage" placeholder="250 GO"><br>

                    <label for="descript">Ce que les utilisateurs doivent savoir sur le model</label><br>
                    <textarea name="descript" id="descript" cols="50" rows="10"placeholder="Ecrivez-ici..."></textarea>

                    <h2>Donnez les couleurs, l'image et le stock</h2><br>
                    <div class="varianteconteneur">
                        <div class="variante">
                            <label for="couleur">Couleur</label><br>
                            <input type="text" name="couleur[]" id="couleur" placeholder="rouge"><br>

                            <label for="stock">Stock disponible</label><br>
                            <input type="text" name="stock[]" id="stock" placeholder="7"><br>

                            <label for="image">Image du modèl</label><br>
                            <input type="file" name="image[]" id="image"><br>
                        </div>
                    </div>
                    <div class="msg">
                        <p style="color:red" id="msgajoutproduit"><?php if(isset($msg_error)){echo $msg_error;}?></p>
                        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                        <!-- <p id="msg"></p> -->
                    </div>

                    <button onclick="ajouterCouleur()" type="button">Ajouter une autre couleur</button>

                    <input type="submit" value="publier" id="submit" name="valider">

                    
                </form>
            </div>
            
            <div class="espaceajoutproduit">
                <span class="material-symbols-outlined">add_2</span>
                <h2>Ajouter un produit</h2>
            </div>

            <div class="espaceproduit">


                <div class="produit-items">
                    <?php while ($gamme = $recupProduit->fetch()){
                        ?>
                        <div class="produit">
                            <div class="allproduitadmin">
                                <div class="img">
                                    <img src="../image/<?=$gamme["imagegamme"]?>" alt="">
                                </div>
                                <div class="nomprix">
                                    <p><?=$gamme["nom"]?></p>
                                </div>
                                <div class="btndetails">
                                    <a href="pagemodel.php?idgamme=<?=$gamme["id"]?>" class="voirproduitadmin">Voir les modèles</a>
                                </div>
                                <div class="btn">
                                </div>
                            </div>

                        </div>
                            
                        
                        <?php
                    }?>
                    

                </div>

                
                    

            </div>

            

        </div>

    </section>
        
</body>
<script src="../js.js"></script>
</html>