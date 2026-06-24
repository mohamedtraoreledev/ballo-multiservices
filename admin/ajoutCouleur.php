<?php
        session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }
        include "../includes/conn.php";

        // RECUP DES NOMS DES GAMMES DANS BD


        if(isset($_GET["idmodel"])&& isset($_GET["idgamme"])){
            $getidGamme = $_GET["idgamme"];
            $getIdModel = $_GET["idmodel"];

            $recupGamme = $bd->prepare("SELECT * FROM gamme where id=?");
            $recupGamme->execute(array($getidGamme)) ;
            $recupG=$recupGamme->fetch();

            if(isset($_POST["valider"])){
            // INSERTION DU  MODEL
            if(!empty($_POST["couleur"]) and !empty($_POST["stock"])
                and !empty($_FILES["image"]) and !empty($_POST["gamme_id"])){
                    $gamme_id = $_POST["gamme_id"];
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
                        $insertCouleurModel->execute(array($getIdModel,$couleur,$newname_image,$stock));
                        echo "Couleur insérer";
                    }else{
                        $msg_error = "image non insérer";
                    }
                    }
                
                }else{
                    $msg_error = "Veuillez remplir tous les champs d'informations";
                }
                header("location:pagemodel.php?idmodel=".$getIdModel."&idgamme=".$gamme_id);
                exit();

                
        }else{
            $msg_error = "image non insérer";
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
    
    
        <!-- <?php include "../includes/navbar.php"?> -->

        <div class="Contentadmin">

            <div class="nommenu">
                <h1><?php
            echo ucfirst(str_replace(".php","",basename($_SERVER["PHP_SELF"])));
        ?></h1>
            </div>
    
            <div class="formajoutproduitmodif">
                <!-- <span class="material-symbols-outlined" id="closebtn">close</span> -->
                <form action="" method="post" enctype="multipart/form-data" class="ajoutproduit">
                    <legend>ajouter une couleur</legend>
                    <h2>Veuillez spécifiez la gamme et le nom du modèle</h2><br>
                    <label for="marque">Gamme</label><br>
                     <div class="select">
                            <select name="gamme_id" id="">
                   
                                <?php if(isset($recupG)):?>
                                <option value="<?=$recupG['id']?>"><?=$recupG['nom']?></option>
                                <?php endif;?>
                      
                    
                        </select>
                    </div> 
               
                    <!-- <input type="text" name="gamme" id="gamme" placeholder="Iphone 17"><br> -->

                    <!-- <label for="image">Image de la gamme</label><br>
                    <input type="file" name="imagegamme" id="image"><br> -->
                    
                    <h2>Donnez les variantes de couleur du model ou donnez la couleur et l'image</h2><br>
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

                    <input type="submit" value="Ajouter" id="submit" name="valider">

                    
                </form>
            </div>
        </div>
    </section>
</body>
<script src="../js.js"></script>
</html>

     