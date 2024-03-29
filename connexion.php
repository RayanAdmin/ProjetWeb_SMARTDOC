<?php
//Permet de garder les variables de la session
session_start();
//Connexion base de données
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', ''); 

//Récuperer les variables du formulaire formconnexion
if(isset($_POST['formconnexion'])) {

   if (isset($_POST['pseudoconnect'])) { 
      $pseudoconnect = htmlspecialchars($_POST['pseudoconnect']); 
    }
   
   if (isset($_POST['mdpconnect'])) { 
      $mdpconnect = sha1($_POST['mdpconnect']);
    }


//Vérificatoin de l'existance de la personne et de ces informations de connexion
   if(!empty($pseudoconnect) AND !empty($mdpconnect)) {
      $requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ? AND motdepasse = ?");
      $requser->execute(array($pseudoconnect, $mdpconnect));
      $userexist = $requser->rowCount();
      if($userexist == 1) {
         $userinfo = $requser->fetch();
         $_SESSION['id'] = $userinfo['id'];
         $_SESSION['pseudo'] = $userinfo['pseudo'];
         $_SESSION['mail'] = $userinfo['mail'];
         $_SESSION['droit'] = $userinfo['droit'];
         header("Location: profil.php?id=".$_SESSION['id']);
      } else {
         
              $requserverif = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
              $requserverif->execute(array($pseudoconnect));
              $userexistverif = $requserverif->rowCount(); 

              if($userexistverif == 1) {

                 $erreur = "Mauvais mot de passe !";

              } else {
                 $erreur = "L'utilisateurs avec ce pseudo n'existe pas !";
              }

        

      }
   } else {

      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Connexion SmartDoc</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cabin:700">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/scroll.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/navigation.css">
</head>

<body id="page-top">


<nav class="navbar navbar-light navbar-expand-md shadow-lg navigation-clean-button" style="background-color: #313437;">
    <div class="container"><a class="navbar-brand" href="index.php" style="color: #ffffff;">SmartDoc</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav mr-auto">
                     

                     <li class="nav-item">
                            <a class="nav-link" href="inscription.php" style="color: white !important;">Création compte</a>
                          </li>


                     <li class="nav-item">
                            <a class="nav-link" href="contact.php" style="color: white !important;">Contacter les adminitrateurs</a>
                          </li>

        


                <?php
                //Rajout de la barre d'administration si la personne un administrateur
                if(isset($_SESSION['droit'])) {
                    if (strcasecmp($_SESSION['droit'], 'admin') == 0){


                        echo '<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="color: white !important;">
                                        Administration
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="utilisateurs_admin.php">Afficher tous les utilisateurs</a>
                                        <a class="dropdown-item" href="affich_docs.php">Afficher les documents des utilisateurs</a>
                                        <a class="dropdown-item" href="modif_utlisateurs_admin.php">Modifier / Supprimer un utilisateur</a>
                                        <a class="dropdown-item" href="create_utilisateurs.php">Créer un utilisateur</a>
                                        <a class="dropdown-item" href="stat_admin.php">Statistiques des utilisateurs</a>
                                    </div>
                                </li>';


                    }
                }
                ?>

                <?php
                //Rajout du bouton de connexion ou déconnexion en fonction de la connexion ou non de l'utilisateur
                if(isset($_SESSION['id'])) {

                    echo '</ul><span class="navbar-text actions"> <a class="btn btn-light action-button" role="button" href="deconnexion.php">Vous êtes connectez : Déconnexion</a></span>';


                } else {


                    echo '</ul><span class="navbar-text actions"> <a class="btn btn-light action-button" role="button" href="connexion.php">Connectez-vous</a></span>';
                }
                ?>
        </div>
</nav>




<div class="login">
        <form method="POST" style="margin-top:-100px" action=""> 
            <h1>SmartDoc</h1>
            <img src="assets/img/logo.png" alt="logo"><br/>
            <h2 class="sr-only">Connexion</h2>

            <div class="form-group">
            
              <input class="form-control" name="pseudoconnect" placeholder="Pseudo">
            
            </div>
            
            <div class="form-group">
            
              <input class="form-control" type="password" name="mdpconnect" placeholder="Mot de passe">
            
            </div>
            
            <div class="form-group">
            
                <button class="btn btn-primary btn-block" type="submit" name="formconnexion">Connexion</button>
            
            </div>
            
            <?php
              if(isset($erreur)) {
              echo '<font color="red">'.$erreur."</font>";
              }
            ?>
            <a href="contact.php" class="new_member">Demander un accès</a> 
			      <a href="inscription.php" class="new_member">Crée un compte</a>

        </form>

    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
