<?php 
// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");

// On recupere dans $_GET l email soumis par l'utilisateur
$email = $_GET['email'];

// On verifie que l'email est un email valide (fonction php filter_var)
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// Si c'est bon
	// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
	$sql = "SELECT * FROM tblreaders WHERE EmailId='".$email."'";
	$query = $dbh->prepare($sql);
	// On execute la requete et on stocke le resultat de recherche
	$query->execute();
	$result = $query->fetch();

	if ($result) {
		// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja
		echo "2"; // existe déjà
	} else {
		// Sinon on signale a l'utlisateur que l'email est disponible
		echo "3"; // n'existe pas encore
	}
}
else {
	// Si l'email n'est pas valide, on fait un echo qui signale l'erreur
	echo "1"; // non valide
}
?>