<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('includes/config.php');

// On invalide le cache de session
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
	$_SESSION['login'] = '';
}

// Après la soumission du formulaire de login ($_POST['login'] existe)
if (TRUE === isset($_POST['login'])) {
	
	// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
	// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php
	if ($_POST['vercode'] != $_SESSION['vercode']) {

		// Le code est incorrect on informe l'utilisateur par une fenetre pop_up
		echo "<script>alert('Code de vérification incorrect')</script>";

	} else {
		// Le code est correct, on peut continuer
		// On recupere le mail de l'utilisateur saisi dans le formulaire
		$mail = $_POST['emailid'];

		// On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction password_hash)
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		
		// On construit la requete pour recuperer l'id, le readerId et l'email du lecteur dans la table tblreaders
		$sql = "SELECT EmailId, Password, ReaderId, Status FROM tblreaders  WHERE EmailId = :email";
		$query = $dbh -> prepare($sql);
		$query -> bindParam(':email', $mail, PDO::PARAM_STR);
		// On execute la requete
		$query -> execute();
		// On stocke le resultat de recherche dans une variable $result
		$result = $query->fetch(PDO::FETCH_OBJ);

		// Si il y a qqchose dans $result et si le mot de passe saisi est correct
		if (!empty($result) && password_verify($_POST['password'], $result->Password)) {

			// On stocke l'identifiant du lecteur (ReaderId dans $_SESSION)
			$_SESSION['rdid'] = $result->ReaderId;

			// Si le statut du lecteur est actif (egal a 1)
			if ($result->Status == 1) {
				
				// On stocke l'email du lecteur dans $_SESSION['login']
				$_SESSION['login'] = $_POST['emailid'];

				// l'utilisateur est redirige vers dashboard.php
				header('location:dashboard.php');
			} else {

				// Sinon le compte du lecteur a ete bloque. On informe l'utilisateur par un popup
				echo "<script>alert('Votre compte à été bloqué')</script>";
			}
		} else {
			echo "<script>alert('Utilisateur inconnu')</script>";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Gestion de bibliotheque en ligne</title>
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

	<!-- On insere le titre de la page (LOGIN UTILISATEUR) -->
	<div class="container">
		<div class="row">
			<div class="col">
				<h3>LOGIN LECTEUR</h3>
			</div>
		</div>
		<!--On insere le formulaire de login-->
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="index.php">
					<div class="form-group">
						<label>Entrez votre email</label>
						<input type="text" name="emailid" required>
					</div>

					<div class="form-group">
						<label>Entrez votre mot de passe</label>
						<input type="password" name="password" required>
						<p>
							<a href="user-forgot-password.php">Mot de passe oublié ?</a>
						</p>
					</div>
					<!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->
					<div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php" alt="captcha">
					</div>

					<button type="submit" name="login" class="btn btn-info">LOGIN</button>&nbsp;&nbsp;&nbsp;<a href="signup.php">Je n'ai pas de compte</a>
				</form>
			</div>
		</div>
	</div>

	<?php include('includes/footer.php'); ?>
	<!-- FOOTER SECTION END-->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>