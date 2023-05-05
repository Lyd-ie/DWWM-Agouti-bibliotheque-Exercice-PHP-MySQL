<?php 
// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");

/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero l'identifiant du lecteur SID---*/
$readerId = $_GET['readerid'];

if (!empty($readerId)
&& strlen($readerId) == 6
&& preg_match("#^[A-Za-z0-9]+$#", $readerId)) {

	// On prepare la requete de recherche du lecteur correspondant
	$sql = "SELECT * FROM tblreaders WHERE ReaderId=:readerid";
	$query = $dbh->prepare($sql);
	$query->bindParam(':readerid', $readerId, PDO::PARAM_STR);
	// On execute la requete
	$query->execute();
	$result = $query->fetch();

	// Si un resultat est trouvé
	if ($result) {		
		// Si le lecteur est bloque	on affiche lecteur bloqué
		if ($result['Status']==0) {
			echo json_encode(["name" => "Le lecteur est bloqué"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		} else if ($result['Status']==1) {
			// Sinon on affiche le nom du lecteur
			$fullName = valid_donnees($result['FullName']);
			echo json_encode(["name" => "{$result['FullName']}"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		} else if ($result['Status']==2) {
			// Sinon on affiche le nom du lecteur
			$fullName = valid_donnees($result['FullName']);
			echo json_encode(["name" => "Le lecteur est non valide"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
	} else {
		// Si le lecteur n existe pas on affiche que "Le lecteur est non valide"
		echo json_encode(["name" => "Le lecteur est non valide"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
?>