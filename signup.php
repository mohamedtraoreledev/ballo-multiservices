<?php  
    session_start();
    include "includes/conn.php";

    if(isset($_POST["valider"])){
        if(!empty($_POST["name"]) and !empty($_POST["prenom"]) and !empty($_POST["email"]) and !empty($_POST["tel"]) and !empty($_POST["indicatif"]) and !empty($_POST["mdp"])){

            $name = strip_tags($_POST["name"]);
            $prenom = strip_tags($_POST["prenom"]);
            $email= strip_tags($_POST["email"]);
            $indicatif = $_POST["indicatif"];
            $telephone = ($indicatif." ".$_POST["tel"]);
            echo $telephone;
            
            $mdp = strip_tags(sha1(($_POST["mdp"])));

        // VERIFICATION SI LES DONNÉES SAISIES EXISTES
            $recupUsers = $bd->prepare("SELECT email,telephone from client where email=? or telephone=?");
            $recupUsers->execute(array($email,$telephone));
            if($recupUsers->rowCount()>0){
                $msg_error = "Un de vos identifiant email ou mot de passe existe déjà";
            }else{
                $insertUsers = $bd->prepare("INSERT INTO client(nom,prenom,email,telephone,mdp) values(?,?,?,?,?)");
                $insertUsers->execute(array($name,$prenom,$email,$telephone,$mdp));
                if($insertUsers->rowCount()>0){
                    $recupUsers = $bd->prepare("SELECT * from client where nom=? and prenom=? and email=? and telephone=?");
                    $recupUsers->execute(array($name,$prenom,$email,$telephone));
                    $infosUsers = $recupUsers->fetch();
                    $_SESSION["auth"] = true;
                    $_SESSION["id"] = $infosUsers["id"];
                    $_SESSION["nom"] = $infosUsers["nom"];
                    $_SESSION["prenom"] = $infosUsers["prenom"];
                    $_SESSION["email"] = $infosUsers["email"];
                    $_SESSION["telephone"] = $infosUsers["telephone"];
                     if(isset($_SESSION["redirect_after_connect"])){
                        $redirectUrl = $_SESSION["redirect_after_connect"];
                        unset($_SESSION["redirect_after_connect"]);
                        header("location:".$redirectUrl);
                    }else{
                        header("location:users/index.php");
                    }
                    // $msg_succes = "inscription reussi !!";
                }
            }

        }else{
            $msg_error = "Veuillez remplir tous les champs d'informations";
        }
    }

?>


















<!-- CODE HTML DU SIGNUP -->
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>BALLO MULTISERVICES</title>
</head>
<body class="formconnins">
    <div class="logo">
            <span class="material-symbols-outlined">add_shopping_cart</span>
            <h1>BALLO <span>Multi-Services</span></h1>
    </div>
    <form action="signup.php" method="post" class="form">
        <h1 id="pagesignin">Inscription</h1>
        <div class="nompass">
            <label for="nom">Nom</label>
            <input type="text" name="name" id="nom" placeholder="Paul">
        </div>
        <div class="prenompass">
            <label for="prenom">Prenom</label>
            <input type="text" name="prenom" id="prenom" placeholder="le marchand">
        </div>

        <div class="emailpass">
            <label for="email">E-mail</label><br>
            <input type="email" name="email" id="email" placeholder="example@11gmail.com">
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
            <input type="tel" name="tel" id="tel"placeholder="76767676"pattern="^\+?[0-9\s]{8,15}$"maxlength="15"required>
        </div>
       <div class="mdppass">
            <label for="mdp">Mot de passe</label><br>
            <input type="password" name="mdp" id="mdp"placeholder="**********">
        </div>
        <span class="material-symbols-outlined" id="btnvoir"></span>
        <div class="msg">
            <p style="color:red"><?php if(isset($msg_error)){echo $msg_error;}?></p>
            <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
            <p id="msg"></p>
        </div>
        
        <div class="pass">
            <input type="submit" value="S'inscrire" name="valider">
        </div>
        <p>Vous avez déjà un compte ? <a href="signin.php">Connectez-vous</a></p>
    </form>
</body>
<script src="js.js"></script>
</html>

