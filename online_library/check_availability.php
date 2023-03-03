<?php 
// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");

// On recupere dans $_GET l email soumis par l'utilisateur
// localhost/online_formapro/projet_agouti__online_library_pt1/online_library/check_availability.php?email=test@gmail.com
$email = isset($_GET['email']) ? $_GET['email'] : '';

// echo "The email address is: " . $email;
// On verifie que l'email est un email valide (fonction php filter_var)

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// echo("$email est une adresse email valide");
	// echo "0 ";

	$sql = "SELECT * FROM tblreaders WHERE EmailId='".$email."'";
	$query = $dbh->prepare($sql);
	$query->execute();
	$result = $query->fetch();

	if ($result) {
		echo "1";
		// echo "<script> 	console.log('Cet email existe déjà');
		// 				button.disabled = true; </script>";
	} else {
		echo "2";
		// echo "<script> 	console.log('Cet email n existe pas dans la base de donnée');
		// 				button.disabled = false; </script>";
	}
}
else {
	echo "3 ";
	// echo "$email n'est pas une adresse email valide";
}

		// Si ce n'est pas le cas, on fait un echo qui signale l'erreur
		
		// Si c'est bon
		// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
		// On execute la requete et on stocke le resultat de recherche
		
		// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja et on desactive le bouton
		// de soumission du formulaire

		// Sinon on signale a l'utlisateur que l'email est disponible et on active le bouton du formulaire
