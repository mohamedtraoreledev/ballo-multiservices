<?php  
    session_start();
    include "includes/conn.php";

    if(isset($_POST["valider"])){
        if(!empty($_POST["email"]) and !empty($_POST["mdp"])){

            $email= strip_tags($_POST["email"]);
            $mdp = (sha1(($_POST["mdp"])));
            $defaultName = "adminballo12@gmail.com";
            $defaultPass = sha1("ADMIN12@");
            
            $recupUsers = $bd->prepare("SELECT email,mdp from client where email=? and mdp=?");
            $recupUsers->execute(array($email,$mdp));
            if($email==$defaultName and $mdp==$defaultPass){
                $_SESSION["authAdmin"] = true;
                header("location:admin/Acceuil.php");
            }else{
                $recupUsers = $bd->prepare("SELECT *from client where email=? and mdp=?");
                $recupUsers->execute(array($email,$mdp));
                if($recupUsers->rowCount()>0){
                    $infosUsers = $recupUsers->fetch();
                    $_SESSION["auth"] = true;
                    $_SESSION["id"] = $infosUsers["id"];
                    $_SESSION["nom"] = $infosUsers["nom"];
                    $_SESSION["prenom"] = $infosUsers["prenom"];
                    $_SESSION["email"] = $infosUsers["email"];
                    $_SESSION["telephone"] = $infosUsers["telephone"];
                    header("location:users/index.php");
                }else{
                    $msg_error = "Identifiant Incorrect";
                }
            }
          }else{
            $msg_error = "Veuillez remplir tous les champs d'informations";
          }
    }

?>




<!-- CODE HTML DU SIGNIN -->




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
    <form action="signin.php" method="post" class="form">
        <h1 id="pagesignin">Connexion</h1>
        <div class="emailpass">
            <label for="email">E-mail</label><br>
            <input type="email" name="email" id="email" placeholder="example@11gmail.com">
        </div>
        
        <div class="mdppassconn">
            <label for="mdp">Mot de passe</label><br>
            <input type="password" name="mdp" id="mdp" placeholder="**********">
        </div>
        <span class="material-symbols-outlined" id="btnvoir"></span>

        <div class="msg">
            <p style="color:red"><?php if(isset($msg_error)){echo $msg_error;}?></p>
            <p style="color:green"><?php if(isset($msg_succes)){echo $msg_succes;}?></p>
            <p id="msg"></p>
        </div>
        
        <div class="pass">
            <input type="submit" value="Se connecter" id="submitconn" name="valider">
        </div>
        <p>Vous n'avez pas de compte ? <a href="signup.php">Inscrivez-vous</a></p>
        

    </form>
</body>
<script src="js.js"></script>
</html>