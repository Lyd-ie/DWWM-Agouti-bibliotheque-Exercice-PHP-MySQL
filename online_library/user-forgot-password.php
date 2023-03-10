<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Après la soumission du formulaire de login ($_POST['change'] existe
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)

// Si le code est incorrect on informe l'utilisateur par une fenetre pop_up

// Sinon on continue
// on recupere l'email et le numero de portable saisi par l'utilisateur
// et le nouveau mot de passe que l'on encode (fonction password_hash)

// On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders

// Si le resultat de recherche n'est pas vide
// On met a jour la table tblreaders avec le nouveau mot de passe
// On informa l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
     </script>
</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->

     <div class="container">
		<div class="row">
			<div class="col">
				<h3>RECUPERATION MOT DE PASSE</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="user-forgot-password.php">
                         <div class="form-group">
                                   <label>Portable :</label>
                                   <input type="tel" name="mobilenumber" required>
                              </div>
                         
                         <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->
                         <div class="form-group">
                                   <label>Email :</label>
                                   <input type="email" name="emailid" onBlur="checkAvailability(emailid.value)" required>
                         <span id="emailCheck"></span>
                              </div>

                              <div class="form-group">
                                   <label>Mot de passe :</label>
                                   <input type="password" name="password" required>
                              </div>
                    
                         <div class="form-group">
                                   <label id="mdp">Confirmez le mot de passe :</label>
                                   <input type="password" name="pswdconfirm" required>
                         <span id="pswdCheck"></span>
                         </div>

                              <!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->
                              <div class="form-group">
                                   <label>Code de vérification</label>
                                   <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                              </div>

                              <button type="submit" name="signup" class="btn btn-info">ENREGISTRER</button>
                         </div>
				</form>
			</div>
		</div>
	</div>
     <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->


     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>