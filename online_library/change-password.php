<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('includes/config.php');

// Si l'utilisateur n'est pas logué, on le redirige vers la page de login (index.php)
if(strlen($_SESSION['login'])==0) {
    header('location:index.php');

    // Sinon on peut continuer
} else {

	// print_r($_SESSION['login']);
	// si le formulaire a ete envoye : $_POST['change'] existe
	if (TRUE === isset($_POST['change'])) {
		
		// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
		$mail = $_SESSION['login'];

		// On construit la requete SQL pour recuperer l'id, le readerId et l'email du lecteur à partir des deux variables ci-dessus dans la table tblreaders
		$verif = "SELECT * FROM tblreaders WHERE EmailId=:email";
		$query = $dbh -> prepare($verif);
		$query -> bindParam(':email', $mail, PDO::PARAM_STR);
		// On execute la requete
		$query -> execute();
		// On stocke le resultat de recherche dans une variable $result
		$result = $query->fetch(PDO::FETCH_OBJ);

		// Si il y a qqchose dans result
		// et si le mot de passe saisi est correct
		if (!empty($result) && password_verify($_POST['password'], $result->Password)) {
			
			$newPassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
			// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur

			$sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:email";
            $query = $dbh->prepare($sql);
			$query -> bindParam(':email', $mail, PDO::PARAM_STR);
			$query -> bindParam(':password', $newPassword, PDO::PARAM_STR);
            // On éxecute la requete
            $query->execute();

            // On informe l'utilisateur par une fenetre popup de la reussite 
			echo "<script> alert('Votre mot de passe a bien été mis à jour.'); </script>";

		// ou de l'echec de l'operation
		} else {
			echo "<script> alert('Mot de passe incorrect'); </script>";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet">
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet">
	<!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->
</head>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<div class="container">
		<div class="row">
			<div class="col">
				<h3>CHANGER MON MOT DE PASSE</h3>
			</div>
		</div>
            <!--On affiche le formulaire de creation de compte-->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                    <form method="post">

                        <div class="form-group">
                            <label>Mot de passe actuel :</label>
                            <input type="password" name="password" required>
							<span class="test"></span>
                        </div>
                    
						<div class="form-group">
                            <label>Nouveau mot de passe :</label>
                            <input type="password" name="newpassword" required>
                        </div>

                        <div class="form-group">
                            <label id="mdp">Confirmez le mot de passe :</label>
                            <input type="password" name="pswdconfirm" onBlur="valid(pswdconfirm.value)" required>
                            <span id="pswdCheck"></span>
                        </div>

                        <button type="submit" name="change" class="btn btn-info">Changer</button>
                    </form>
                </div>
            </div>
        </div>

	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script>
		function valid(pswdconfirm) {
			let newpassword = document.querySelector("input[name='newpassword']");
			let checkPassword = document.querySelector("input[name='pswdconfirm']");
			let submitButton = document.querySelector("button[name='change']");
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