<?php 
// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");

/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero ISBN du livre*/
$ISBN = valid_donnees($_GET['isbn']);

if (!empty($ISBN)
&& strlen($ISBN) <= 13) {

	// On prepare la requete de recherche du livre correspondant
	$sql = "SELECT * FROM tblbooks WHERE ISBNNumber=:isbn";
	$query = $dbh->prepare($sql);
	$query->bindParam(':isbn', $ISBN, PDO::PARAM_INT);
	// On execute la requete
	$query->execute();
	$result = $query->fetch();

	// Si un resultat est trouve
	if ($result) {		
		$bookName = valid_donnees($result['BookName']);
		// On affiche le nom du livre
		echo json_encode(["title" => "{$bookName}"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	} else {
		// Sinon on affiche que "ISBN est non valide"
		echo json_encode(["title" => "L'ISBN est non valide"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
?>
