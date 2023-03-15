<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');

// Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : index.php
if(strlen($_SESSION['login'])==0) {
     header('location:index.php');

} else {

	// On récupère l'identifiant du lecteur dans le tableau $_SESSION
     // print_r($_SESSION);
     $id = $_SESSION['rdid'];
	
	// On veut savoir combien de livres ce lecteur a emprunte
	// On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     $booked = "SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReaderID=:readerid";
	$query = $dbh->prepare($booked);
     $query->bindParam(':readerid', $id, PDO::PARAM_STR);
	// On execute la requete et on stocke le resultat de recherche
	$query->execute();
     // On stocke le résultat dans une variable
	$bookedResult = $query->fetch(PDO::FETCH_ASSOC);

	// On veut savoir combien de livres ce lecteur n'a pas rendu
	// On construit la requete qui permet de compter combien de livres sont associes a ce lecteur avec le ReturnStatus à 0
     $returnStatus = 0;

     $due = "SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReaderID=:readerid and ReturnStatus=:returnstatus";
     $query = $dbh->prepare($due);
     $query->bindParam(':readerid', $id, PDO::PARAM_STR);
     $query->bindParam(':returnstatus', $returnStatus, PDO::PARAM_INT);
	// On execute la requete et on stocke le resultat de recherche
	$query->execute();
     // On stocke le résultat dans une variable
	$dueResult = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="FR">
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
     <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet">
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php');?>
     <!-- On affiche le titre de la page : Tableau de bord utilisateur-->
     <div class="container">
          <div class="row">
               <div class="col">
                    <h3>TABLEAU DE BORD UTILISATEUR</h3>
               </div>
          </div>
          <!-- On affiche la carte des livres empruntes par le lecteur-->
               <div class="row">
                    <div class="col"><i class="fa fa-bars fa-4x"></i>
                    <p class="booked"></p>
                    <p>Livres empruntés</p>
               </div>

               <!-- On affiche la carte des livres non rendus le lecteur-->
               <div class="col">
                    <i class="fa fa-recycle fa-4x"></i>
                    <p class="due"></p>
                    <p>Livres non encore rendus</p>
               </div>
          </div>
     </div>

     <?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     
</body>
</html>
<?php
     echo "<script>
               document.querySelector('.booked').innerHTML = ".$bookedResult['COUNT(*)'].";
               document.querySelector('.due').innerHTML = ".$dueResult['COUNT(*)'].";
          </script>";
} ?>