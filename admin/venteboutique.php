<?php
        session_start();
         if (!isset($_SESSION["authAdmin"])) {
            header("Location:../signin.php");
            exit();
        }
        include "../includes/conn.php";

         $selectvpCommande = $bd->prepare("SELECT COUNT(*) as nbcommande from commande where vp=?");
        $selectvpCommande->execute(array(0));
        $nb_vp = $selectvpCommande->fetch();


                $selectNbVente = $bd->prepare("SELECT COUNT(*) as nbvente from commande where etat3=?");
                $selectNbVente->execute(array(4));
                $nb_vente= $selectNbVente->fetch();

                $updateV = $bd->prepare("UPDATE commande set etat3=? where etat=?");
                $updateV->execute([4,3]);


                $selectProduit = $bd->query("SELECT nom,prix,stockage,m.id,couleur,stock,col.id as id_couleur FROM model as m join couleur_models as col on m.id=col.id_model");

                if(isset($_POST["valider"])){
                    if(!empty($_POST["id_produit"])
                        and !empty($_POST["nomclient"]) 
                        and !empty($_POST["prenomclient"]) 
                        and !empty($_POST["quantite"]) 
                        and !empty($_POST["prixvente"])
                        and !empty($_POST["tel"])
                        and !empty($_POST["indicatif"])){  

                        $produits = $_POST["id_produit"];
                        $nomclient = $_POST["nomclient"];
                        $prenomclient = $_POST["prenomclient"];
                        $quantite =($_POST["quantite"]);
                        $prixvente = $_POST["prixvente"];
                        $indicatif = $_POST["indicatif"];
                        $telephone = $_POST["tel"];
                        
                        $tel = ($indicatif." ".$_POST["tel"]);
                        $montant_total = 0;
                        var_dump($_POST);

                        foreach($produits as $index=>$id_couleur){
                            $qte = $quantite[$index];
                            $valide = true;
                            
                            $selectCouleur = $bd->prepare("SELECT * FROM couleur_models where id=?");
                            $selectCouleur->execute([$id_couleur]);
                            $prod = $selectCouleur->fetch();
                            $selectPrix = $bd->prepare("SELECT * FROM model where id=?");
                            $selectPrix->execute([$prod["id_model"]]);
                            $model = $selectPrix->fetch();
                            if($qte>$prod["stock"]){
                                $valide = false;
                                $msg_error = "Stock insuffisant";
                            }
                            $montant_total+= $qte* intval($model["prix"]);
                        }
                        // $selectCouleur = $bd->prepare("SELECT*FROM couleur_models where id=?");
                        // $selectCouleur->execute(array($_POST["id_produit"]));
                        // $selectmodel = $bd->prepare("SELECT id_model FROM couleur_models where id=?");
                        // $selectmodel->execute(array($_POST["id_produit"]));
                        // $idmodel = $selectmodel->fetch();
                        // if($selectCouleur->rowCount()>0){
                        //     $Sc=$selectCouleur->fetch();
                        //         if($quantite>$Sc["stock"]||$quantite<0){
                        //         $msg_error = "Stock insuffisant";
                        //     }else{
                        if($valide==true){
                            $insertVente = $bd->prepare("INSERT INTO vente(prix_total,telephone,nomclient,prenomclient) values(?,?,?,?)");
                                $insertVente->execute(array($montant_total,$tel,$nomclient,$prenomclient));

                                // $insertClient = $bd->prepare("INSERT INTO client(nom,prenom)values(?,?)");
                                // $insertClient->execute(array($nomclient,$prenomclient));

                                // $updateStock = $bd->prepare("UPDATE couleur_models set stock=stock-? where id=?");
                                // $updateStock->execute(array($quantite,$_POST["id_produit"]));
                                $id_vente = $bd->lastInsertId();

                                foreach($produits as $index=>$id_couleur){
                                    $qte = $quantite[$index];
                                    $selectCouleur = $bd->prepare("SELECT * FROM couleur_models where id=?");
                                    $selectCouleur->execute([$id_couleur]);
                                    $prod = $selectCouleur->fetch();
                                    $selectPrix = $bd->prepare("SELECT * FROM model where id=?");
                                    $selectPrix->execute([$prod["id_model"]]);
                                    $model = $selectPrix->fetch();

                                    $insertDv = $bd->prepare("INSERT INTO detail_vente(id_vente,id_model,id_couleur,quantite,prix_unitaire)values(?,?,?,?,?)");
                                    $insertDv->execute(array($id_vente,$prod["id_model"],$id_couleur,$qte,$model["prix"]));

                                    $updateStock = $bd->prepare("UPDATE couleur_models set stock=stock-? where id=?");
                                    $updateStock->execute(array($qte,$id_couleur));
                                    $msg_succes = "Vente effectuée";

                                }
                                
                        }
                                
                        //     }
                        // }
                    }else{
                        $msg_error = "Veuillez remplir les champs de saisi";
                    }
                }
?>







<!DOCTYPE html>
<html lang="en">
<?php include "../includes/header.php"?>
<body>
    
    <section  class="pageadmin">
    
        <?php include "../includes/navbar.php"?>

        <div class="Contentadmin">

            <div class="nommenu">
                <h1>
                    <?php
                        echo ucfirst(str_replace(".php","",basename($_SERVER["PHP_SELF"])));
                    ?>
                </h1>
            </div>

            <div class="venteespace">
                 <form action="" method="post" class="form">
                    <h1 id="pagesignin">Insérer la vente ici</h1>
                    
                    <div class="mdppassconn">
                        <label for="mdpvente">Nom du client</label><br>
                        <input type="text" name="nomclient" id=""placeholder="Jean" autocomplete="off">
                    </div>
                    <div class="mdppassconn">
                        <label for="mdpvente">Prenom du client</label><br>
                        <input type="text" name="prenomclient" id="" placeholder="Paul" autocomplete="off">
                    </div>
                    <div class="telpass">
                    <label for="pays">Pays</label>

                    <select name="indicatif" id="pays">

                    <option value="+223">🇲🇱 +223 Mali</option>
                    <option value="+221">🇸🇳 +221 Sénégal</option>
                    <option value="+225">🇨🇮 +225 Côte d’Ivoire</option>
                    <option value="+226">🇧🇫 +226 Burkina Faso</option>
                    <option value="+227">🇳🇪 +227 Niger</option>
                    <option value="+228">🇹🇬 +228 Togo</option>
                    <option value="+229">🇧🇯 +229 Bénin</option>
                    <option value="+224">🇬🇳 +224 Guinée</option>
                    <option value="+220">🇬🇲 +220 Gambie</option>
                    <option value="+222">🇲🇷 +222 Mauritanie</option>
                    <option value="+234">🇳🇬 +234 Nigeria</option>
                    <option value="+237">🇨🇲 +237 Cameroun</option>
                    <option value="+243">🇨🇩 +243 RDC</option>
                    <option value="+212">🇲🇦 +212 Maroc</option>
                    <option value="+213">🇩🇿 +213 Algérie</option>
                    <option value="+216">🇹🇳 +216 Tunisie</option>
                    <option value="+20">🇪🇬 +20 Égypte</option>

                    <option value="+33">🇫🇷 +33 France</option>
                    <option value="+32">🇧🇪 +32 Belgique</option>
                    <option value="+41">🇨🇭 +41 Suisse</option>
                    <option value="+1">🇺🇸 +1 USA / Canada</option>
                    <option value="+44">🇬🇧 +44 Royaume-Uni</option>
                    <option value="+49">🇩🇪 +49 Allemagne</option>
                    <option value="+39">🇮🇹 +39 Italie</option>
                    <option value="+34">🇪🇸 +34 Espagne</option>

                </select>
                <label for="mdp">Numéro de téléphone</label><br>
                <input type="tel" name="tel" id="tel"placeholder="76767676"pattern="^\+?[0-9\s]{8,15}$"maxlength="15">
                <hr>
                <div id="produits">

                    <div class="ligne">

                        <div class="emailpass">
                            <label for="email">Produit</label><br>
                            <select name="id_produit[]" id="id_article">
                                <?php foreach($selectProduit as $prod=>$value):?>
                                <option data-prix="<?=$value["prix"]?>" value="<?=$value["id_couleur"]?>"><?=$value["nom"]." ".$value["couleur"]." ".$value["stockage"]." - ".$value["stock"]." Disponible - ".$value["prix"]." FCFA"?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="mdppassconn">
                            <label for="mdp">Quantite du produit acheté</label><br>
                            <input onkeyup="setPrix()" type="number" name="quantite[]" id="quantite"placeholder="1" autocomplete="off">
                        </div>
                        <div class="mdppassconn">
                            <label for="mdp">Prix de la vente</label><br>
                            <input  type="number" name="prixvente" id="prix" placeholder="">
                        </div>

                    </div>

                </div>      
        </div>
                    
                    <span class="material-symbols-outlined" id="btnvoir"></span>

                    <div class="msg">
                        <p style="color:red"><?php if(isset($msg_error)){echo $msg_error;}?></p>
                        <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                        <p id="msg"></p>
                    </div>
                    
                    <button type="button" style="border:3px solid;background-color:green" onclick="AjouterProduit()">
                        Ajouter un produit
                    </button>
                    
                    <div class="pass">
                        <input type="submit" value="Enregister" id="submitconnvente" name="valider">
                    </div>
                    <!-- <p>Vous n'avez pas de compte ? <a href="signup.php">Inscrivez-vous</a></p> -->
                    

                </form>
                <div class="affichervente">
                        
                        <?php $allvente = $bd->prepare("SELECT nomclient,prenomclient,date_vente,prix_total,id as id_vente,telephone FROM vente where etat=? ORDER by id desc");
                              $allvente->execute([1]);
                              if($allvente->rowCount()>0){
                                    ?>
                                    
                                    
                       
                    <?php while($vente = $allvente->fetch()){
                        ?>
                            <div class="vente-card">

                                <p><strong>Nom :</strong> <?=$vente["nomclient"]?></p>

                                <p><strong>Prénom :</strong> <?=$vente["prenomclient"]?></p>

                                <p><strong>Téléphone :</strong> <?=$vente["telephone"]?></p>

                                <p><strong>Date de la vente :</strong> <?=$vente["date_vente"]?></p>

                                <p><strong>Total Vente :</strong> <?=$vente["prix_total"]?> FCFA</p>

                                <a href="detailvente.php?idvente=<?=$vente["id_vente"]?>">
                                    <button style="color:white;border:1px solid white;padding:10px;border-radius:5px;background-color:blue">Détails de la vente</button>
                                </a>

                                <a href="../action/reçuventebtq.php?idvente=<?=$vente["id_vente"]?>">
                                    <button style="color:white;border:1px solid white;padding:10px;border-radius:5px;background-color:blue">Livrer reçu</button>
                                </a>

                            </div>
                        <?php
                    }?>
                                    
                                    
                                    <?php
                              }else{
                                 $msg_succes = "Pas de vente pour le moment";
                                ?>
                                    <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
                                    
                                <?php
                                
                              }
                        ?>

                    
                    
                </div>
            </div>
        </div>


</body>
<script>
        
    function setPrix(){
        var article = document.querySelector("#id_article");
        var quantite = document.querySelector("#quantite");
        var prix = document.querySelector("#prix");
        var prixUnitaire = article.options[article.selectedIndex].getAttribute("data-prix");
        console.log(prixUnitaire);
        prix.value = Number(quantite.value)*Number(prixUnitaire);
    }





setInterval(function(){

    $.get("../messageInstantane/commandenb.php", function(data){
        $(".nbcommande").text(data);
    });

    $.get("../messageInstantane/nbvente.php", function(data){
        $(".nbvente").text(data);
    });

}, 5000);

function AjouterProduit(){
    let bloc = document.querySelector("#produits");
    let ligne = document.querySelector(".ligne");
    let clone = ligne.cloneNode(true);
    clone.querySelector("input").value="";
    bloc.appendChild(clone);
}
    


    
    
</script>


<script src="../js.js"></script>
</html>