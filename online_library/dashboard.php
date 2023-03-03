<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');


if(strlen($_SESSION['login'])==0) {
	// Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : index.php
  header('location:index.php');
} else {
	// On récupère l'identifiant du lecteur dans le tableau $_SESSION
	
	// On veut savoir combien de livres ce lecteur a emprunte
	// On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
	
	// On stocke le résultat dans une variable
	
	// On veut savoir combien de livres ce lecteur n'a pas rendu
	// On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus à 0 
	
	// On stocke le résultat dans une variable

     print_r($_SESSION);

?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
<?php include('includes/header.php');?>
<!-- On affiche le titre de la page : Tableau de bord utilisateur-->
 
   <!-- On affiche la carte des livres emprunt�s par le lecteur-->
        
   <!-- On affiche la carte des livres non rendus le lecteur-->

<?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
