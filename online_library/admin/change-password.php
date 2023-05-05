<?php
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('includes/config.php');

// Si l'utilisateur n'est pas logué, on le redirige vers la page de login (index.php)
if(strlen($_SESSION['alogin'])==0) {
    // Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : index.php
    header('location:../adminlogin.php');
    // Sinon on peut continuer
} else {

	// si le formulaire a ete envoye : $_POST['submit'] existe
	if (TRUE === isset($_POST['submit'])) {
		
		// On recupere le username de l'utilisateur dans le tabeau $_SESSION
		$userName = valid_donnees($_SESSION['alogin']);

		// On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction password_hash)
		$cleanedPassword = valid_donnees($_POST['password']);
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		if (!empty($password)
		&& strlen($cleanedPassword) <= 20
		&& !empty($userName)
		&& strlen($userName) <= 10
		&& preg_match("#^[A-Za-z0-9]+$#", $userName)) {
			// On construit la requete SQL pour recuperer l'id, le readerId et l'email du lecteur à partir des deux variables ci-dessus dans la table tblreaders
			$verif = "SELECT * FROM admin WHERE UserName=:username";
			$query = $dbh -> prepare($verif);
			$query -> bindParam(':username', $userName, PDO::PARAM_STR);
			// On execute la requete
			$query -> execute();
			// On stocke le resultat de recherche dans une variable $result
			$result = $query->fetch(PDO::FETCH_OBJ);

			// Si il y a qqchose dans result
			// et si le mot de passe saisi est correct
			if (!empty($result) && password_verify($cleanedPassword, $result->Password)) {
				
				$newPassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
				// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur

				$sql = "UPDATE admin SET Password=:password WHERE UserName=:username";
				$query = $dbh->prepare($sql);
				$query -> bindParam(':username', $userName, PDO::PARAM_STR);
				$query -> bindParam(':password', $newPassword, PDO::PARAM_STR);
				// On éxecute la requete
				$query->execute();

				// On informe l'utilisateur par une fenetre popup de la reussite 
				echo "<script> alert('Votre mot de passe a bien été mis à jour.'); </script>";

			// ou de l'echec de l'operation
			} else {
				echo "<script> alert('Mot de passe incorrect'); </script>";
			}
		} else {
			echo "<script> alert('Veuillez corriger votre saisie'); </script>";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Gestion bibliotheque en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet">
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet">
	<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
</head>

<body>
<!--MENU SECTION START-->
<?php include('includes/header.php'); ?>
<!-- MENU SECTION END-->
	<!-- On affiche le message de succes ou d'erreur  -->
	<div class="container">
		<div class="col">
			<h3>CHANGER MON MOT DE PASSE</h3>
		</div>
	</div>
		<!-- On affiche le formulaire de changement de mot de passe-->
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
			<form method="post">

				<div class="form-group">
					<label>Mot de passe actuel :</label>
					<input type="password" name="password" maxlength="20" required>
					<span class="test"></span>
				</div>
			
				<div class="form-group">
					<label>Nouveau mot de passe :</label>
					<input type="password" name="newpassword" maxlength="20" onkeyup="valid(pswdconfirm.value)" required>
				</div>

				<div class="form-group">
					<label id="mdp">Confirmez le mot de passe :</label>
					<input type="password" name="pswdconfirm" maxlength="20" onkeyup="valid(pswdconfirm.value)" required>
					<span id="pswdCheck"></span>
				</div>

				<button type="submit" name="submit" class="btn btn-info">Changer</button>
			</form>
		</div>
	</div>
<!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php'); ?>
<!-- FOOTER SECTION END-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
	function valid(pswdconfirm) {
		let newpassword = document.querySelector("input[name='newpassword']");
		let checkPassword = document.querySelector("input[name='pswdconfirm']");
		let submitButton = document.querySelector("button[name='submit']");
		let pswdMsg = document.getElementById("pswdCheck");
		
		if (newpassword.value === pswdconfirm) {
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