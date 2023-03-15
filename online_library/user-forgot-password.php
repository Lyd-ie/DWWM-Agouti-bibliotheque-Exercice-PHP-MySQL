<?php
     // On récupère la session courante
     session_start();

     // On inclue le fichier de configuration et de connexion à la base de données
     include('includes/config.php');

     // Après la soumission du formulaire de login ($_POST['change'] existe)
     if (TRUE === isset($_POST['change'])) {

          // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
          // $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php
          if ($_POST['vercode'] != $_SESSION['vercode']) {

               // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
               echo "<script>alert('Code de vérification incorrect')</script>";

          } else {
               // on recupere l'email et le numero de portable saisi par l'utilisateur
               $tel = $_POST['mobilenumber'];
               $email = $_POST['emailid'];
               // et le nouveau mot de passe que l'on encode (fonction password_hash)
               $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

               $search = "SELECT * FROM tblreaders WHERE EmailId=:emailid and MobileNumber=:mobilenumber";
               $query = $dbh->prepare($search);
               $query->bindParam(':emailid', $email, PDO::PARAM_STR);
               $query->bindParam(':mobilenumber', $tel, PDO::PARAM_INT);
               // On execute la requete et on stocke le resultat de recherche
               $query->execute();
               $result = $query->fetch(PDO::FETCH_ASSOC);

               // Si le resultat n'est pas vide
               if ($result) {

                    // On met a jour la table tblreaders avec le nouveau mot de passe
                    $sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:emailid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':password', $password, PDO::PARAM_STR);
                    $query->bindParam(':emailid', $email, PDO::PARAM_STR);
                    // On éxecute la requete
                    $query->execute();

                    // On informe l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
                    echo "<script> 
                              alert('Votre mot de passe a bien été mis à jour.');
                              location.href = 'http://localhost/online_formapro/projet_agouti__online_library_pt1/online_library/index.php';
                         </script>";
               
               // Si le resultat est vide, on informe l'utilisateur de l'échec
               } else {
                    echo "<script> 
                              alert('Erreur : Email ou téléphone inconnus');
                         </script>";
               }
          }
     }
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet">
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet">
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
                              <label>Email : </label>
                              <input type="email" name="emailid" required>
                         </div>

                         <div class="form-group">
                              <label>Portable : </label>
                              <input type="tel" name="mobilenumber" required>
                         </div>

                         <div class="form-group">
                              <label>Nouveau mot de passe : </label>
                              <input type="password" name="password" required>
                         </div>
                         <!-- On appelle la fonction valid() dans la balise <input> du mot de passe -->
                         <div class="form-group">
                              <label id="mdp">Confirmez le mot de passe : </label>
                              <input type="password" name="pswdconfirm" onBlur="valid()" required>
                              <span id="pswdCheck"></span>
                         </div>
                         <!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->
                         <div class="form-group">
                              <label>Code de vérification</label>
                              <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;
                              <img src="captcha.php" alt="captcha">
                         </div>

                         <button type="submit" name="change" class="btn btn-info">ENREGISTRER</button>
				</form>
			</div>
		</div>
	</div>

     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     <script>
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
          function valid() {
               let password = document.querySelector("input[name='password']");
               let checkPassword = document.querySelector("input[name='pswdconfirm']");
               let submitButton = document.querySelector("button[name='change']");
               let pswdMsg = document.getElementById("pswdCheck");

               if (password.value == checkPassword.value) {
                    submitButton.disabled = false;
                    pswdMsg.innerHTML = "&nbsp;Mots de passe identiques";
                    pswdMsg.style.fontWeight = "800";
                    pswdMsg.style.color = "green";
               }
               else {
                    submitButton.disabled = true;
                    pswdMsg.innerHTML = "&nbsp;Mots de passe différents";
                    pswdMsg.style.fontWeight = "800";
                    pswdMsg.style.color = "red";
               }
          }
     </script>
</body>
</html>