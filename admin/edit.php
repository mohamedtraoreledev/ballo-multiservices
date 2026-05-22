<?php
        session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }
        include "../includes/conn.php";

        // RECUP DES NOMS DES GAMMES DANS BD


        if(isset($_GET["idmodel"]) && isset($_GET["couleur"]) && isset($_GET["idgamme"])){
            $getidGamme = $_GET["idgamme"];
            $getIdModel = $_GET["idmodel"];
            $getCouleur = $_GET["couleur"];

            $recupGamme = $bd->prepare("SELECT * FROM gamme where id=?");
            $recupGamme->execute(array($getidGamme)) ;
            $recupGamme=$recupGamme->fetch();

            $selectModel = $bd->prepare("SELECT * FROM couleur_models where id_model=? and couleur=?");
            $selectModel->execute(array($getIdModel,$getCouleur));
                $selectInfosModel = $bd->prepare("SELECT * from model as m join couleur_models as col on m.id = col.id_model where col.id_model=? and col.couleur=?");
                $selectInfosModel->execute(array($getIdModel,$getCouleur));
                if($selectInfosModel->rowCount()>0){
                    $infosModel = $selectInfosModel->fetch();
                    if(isset($_POST["valider"])){
                        // MODIFICATION DU  MODEL
                        if(!empty($_POST["model"]) or !empty($_POST["prix"]) 
                            or !empty($_POST["stockage"]) or !empty($_POST["descript"])
                            or !empty($_POST["couleur"]) or !empty($_POST["stock"])
                            or  !empty($_FILES["image"]) or !empty($_POST["gamme_id"])){
                                    $nom_model = $_POST["model"];
                                    $prix = $_POST["prix"];
                                    $stockage = $_POST["stockage"];
                                    $description = $_POST["descript"];
                                    $gamme_id = $_POST["gamme_id"];
                                    $updateProduit = $bd->prepare("UPDATE model set nom=?,prix=?,description=?,id_gamme=?,stockage=? where id=?");
                                    $updateProduit->execute(array($nom_model,$prix,$description,$gamme_id,$stockage,$getIdModel));
                                    // $id_model = $bd->lastInsertId();
                        // MODIFICATION DE LA COULEUR
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
                                    $insertCouleurModel = $bd->prepare("UPDATE couleur_models set couleur=?,image=?,stock=? where id_model=? and couleur=?");
                                    $insertCouleurModel->execute(array($couleur,$newname_image,$stock,$getIdModel,$getCouleur));
                                    if($insertCouleurModel->rowCount()>0){
                                        $selectIdCouleur = $bd->prepare("SELECT id from couleur_models where couleur=? and id_model=?");
                                        $selectIdCouleur->execute(array($couleur,$getIdModel));
                                        $couleur_id = $selectIdCouleur->fetch();

                                       

                                        $selectQuantiteCommande = $bd->prepare("SELECT id_commande,quantite from commande_details where id_couleur=?");
                                        $selectQuantiteCommande->execute(array($couleur_id["id"]));
                                        while($quantiteCommande=$selectQuantiteCommande->fetch()){
                                            $stockCouleur = $bd->prepare("SELECT stock from couleur_models where id=?");
                                            $stockCouleur->execute(array($couleur_id["id"]));
                                            $stCouleur = $stockCouleur->fetch();
                                             
                                            if($quantiteCommande["quantite"]<=$stCouleur["stock"]){
                                                $statu="Stock disponible";
                                                $updateEtat = $bd->prepare("UPDATE commande_details set etat=? where id_commande=? and id_couleur=?");
                                                $updateEtat->execute(array(1,$quantiteCommande["id_commande"],$couleur_id["id"]));
                                                $updateCommande = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                                                $updateCommande->execute(array($statu,$quantiteCommande["id_commande"]));
                                            }
                                        }
                                    }

                                    
                                }
                                if(!$uploadDossier){
                                    // $couleur = $_POST["couleur"];
                                    // $stock = $_POST["stock"];
                                    $insertCouleurModel = $bd->prepare("UPDATE couleur_models set couleur=?,stock=? where id_model=? and couleur=?");
                                    $insertCouleurModel->execute(array($couleur,$stock,$getIdModel,$getCouleur));
                                        $selectIdCouleur = $bd->prepare("SELECT id from couleur_models where couleur=? and id_model=?");
                                        $selectIdCouleur->execute(array($couleur,$getIdModel));
                                        $couleur_id = $selectIdCouleur->fetch();

                                       

                                        $selectQuantiteCommande = $bd->prepare("SELECT id_commande,quantite from commande_details where id_couleur=?");
                                        $selectQuantiteCommande->execute(array($couleur_id["id"]));
                                        while($quantiteCommande=$selectQuantiteCommande->fetch()){
                                            $stockCouleur = $bd->prepare("SELECT stock from couleur_models where id=?");
                                            $stockCouleur->execute(array($couleur_id["id"]));
                                            $stCouleur = $stockCouleur->fetch();
                                             
                                            if($quantiteCommande["quantite"]<=$stCouleur["stock"]){
                                                $statu="Stock disponible";
                                                $updateEtat = $bd->prepare("UPDATE commande_details set etat=? where id_commande=? and id_couleur=?");
                                                $updateEtat->execute(array(1,$quantiteCommande["id_commande"],$couleur_id["id"]));
                                                $updateCommande = $bd->prepare("UPDATE commande set statu_commande=? where id=?");
                                                $updateCommande->execute(array($statu,$quantiteCommande["id_commande"]));
                                            }
                                        }
                                    
                                }
                                }
                                header("location:pagemodel.php?idCouleur=".$couleur."&idmodel=".$getIdModel."&idgamme=".$getidGamme);
                                exit();
                            
                            }else{
                                $msg_error = "Veuillez remplir au moins un champs de modifcation";
                            }
                    }
                }else{
                    echo"infos non";
                }
            }
        

                $selectNbCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
                $selectNbCommande->execute(array(0));
                $nb_commande = $selectNbCommande->fetch();
        
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
    
            <div class="formajoutproduitmodif">
                <!-- <span class="material-symbols-outlined" id="closebtn">close</span> -->
                <form action="" method="post" enctype="multipart/form-data" class="ajoutproduit">
                    <legend>Modifier le produit</legend>
                    <h2>Veuillez spécifiez la gamme et le nom du modèle</h2><br>
                    <label for="marque">Gamme</label><br>
                     <div class="select">
                            <select name="gamme_id" id="">
                   
                                <?php if(isset($recupGamme)):?>
                                <option value="<?=$recupGamme['id']?>"><?=$recupGamme['nom']?></option>
                                <?php endif;?>
                      
                    
                        </select>
                    </div> 
               
                    <!-- <input type="text" name="gamme" id="gamme" placeholder="Iphone 17"><br> -->

                    <!-- <label for="image">Image de la gamme</label><br>
                    <input type="file" name="imagegamme" id="image"><br> -->
                    
                    <label for="model">Nom du model de la gamme</label><br>
                    <input type="text" name="model" id="model" placeholder="Iphone 17 pro-max" value="<?php if(isset($infosModel)):?><?=$infosModel["nom"]?>"<?php endif;?>><br>
                    <label for="prix">Prix</label><br>
                    <input type="number" name="prix" id="prix" placeholder="250000FCFA"value="<?php if(isset($infosModel)):?><?=$infosModel["prix"]?>"<?php endif;?>><br>

                    <label for="stockage">Stockage</label><br>
                    <input type="text" name="stockage" id="stockage" placeholder="250 GO"value="<?php if(isset($infosModel)):?><?=$infosModel["stockage"]?>"<?php endif;?>><br>

                    <label for="descript">Ce que les utilisateurs doivent savoir sur le model</label><br>
                    <textarea name="descript" id="descript"><?php if(isset($infosModel)):?><?=$infosModel["description"]?><?php endif;?></textarea>

                    <h2>Donnez les variantes de couleur du model ou donnez la couleur et l'image</h2><br>
                    <div class="varianteconteneur">
                        <div class="variante">
                            <label for="couleur">Couleur</label><br>
                            <input type="text" name="couleur[]" id="couleur" placeholder="rouge"value="<?php if(isset($infosModel)):?><?=$infosModel["couleur"]?>"<?php endif;?>><br>

                            <label for="stock">Stock disponible</label><br>
                            <input type="text" name="stock[]" id="stock" placeholder="7"value="<?php if(isset($infosModel)):?><?=$infosModel["stock"]?><?php endif;?>"><br>

                            <label for="image">Image du modèl</label><br>
                            <input type="file" name="image[]" id="image"value="<?php if(isset($infosModel)):?><?=$infosModel["image"]?><?php endif;?>"><br>
                        </div>
                    </div>
                    <div class="msg">
                        <p style="color:red"><?php if(isset($msg_error)){echo $msg_error;}?></p>
                        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                        <!-- <p id="msg"></p> -->
                    </div>

                    <!-- <button onclick="ajouterCouleur()" type="button">Ajouter une autre couleur</button> -->

                    <input type="submit" value="Modifier" id="submit" name="valider">

                    
                </form>
            </div>
        </div>
    </section>
</body>
<script src="../js.js"></script>
</html>

     