<?php
// On démarre (ou on récupère) la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
  // Si l'utilisateur est déconnecté
  // L'utilisateur est renvoyé vers la page de login : adminlogin.php
  header('location:../adminlogin.php');
} else {
  // sinon on récupère les informations à afficher depuis la base de données
  $adminId = $_SESSION['alogin'];

  // On récupère le nombre de livres depuis la table tblbooks 
	$sql = $dbh->prepare("SELECT COUNT(*) FROM tblbooks");
	// On execute la requete et on stocke le resultat de recherche
	$sql->execute();
     // On stocke le résultat dans une variable
	$result = $sql->fetch();

  $issued = 0;

  // On récupère le nombre de livres en prêt depuis la table tblissuedbookdetails
  $sql1 = $dbh->prepare("SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReturnStatus=:issued");
  $sql1 -> bindParam(':issued', $issued, PDO::PARAM_STR);
	// On execute la requete et on stocke le resultat de recherche
	$sql1->execute();
     // On stocke le résultat dans une variable
	$result1 = $sql1->fetch();

  // On récupère le nombre de livres retournés  depuis la table tblissuedbookdetails
  // Ce sont les livres dont le statut est à 1
  $returned = 1;

  // On récupère le nombre de livres en prêt depuis la table tblissuedbookdetails
  $sql2 = $dbh->prepare("SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReturnStatus=:returned");
  $sql2 -> bindParam(':returned', $returned, PDO::PARAM_STR);
	// On execute la requete et on stocke le resultat de recherche
	$sql2->execute();
     // On stocke le résultat dans une variable
	$result2 = $sql2->fetch();

  // On récupère le nombre de lecteurs dans la table tblreaders
  $sql3 = $dbh->prepare("SELECT COUNT(*) FROM tblreaders");
	// On execute la requete et on stocke le resultat de recherche
	$sql3->execute();
     // On stocke le résultat dans une variable
	$result3 = $sql3->fetch();

  // On récupère le nombre d'auteurs dans la table tblauthors
  $sql4 = $dbh->prepare("SELECT COUNT(*) FROM tblauthors");
	// On execute la requete et on stocke le resultat de recherche
	$sql4->execute();
     // On stocke le résultat dans une variable
	$result4 = $sql4->fetch();

  // On récupère le nombre de catégories dans la table tblcategory
  $sql5 = $dbh->prepare("SELECT COUNT(*) FROM tblcategory");
	// On execute la requete et on stocke le resultat de recherche
	$sql5->execute();
     // On stocke le résultat dans une variable
	$result5 = $sql5->fetch();
?>

<!DOCTYPE html>
<html lang="FR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Gestion de bibliothèque en ligne | Tab bord administration</title>
  <!-- BOOTSTRAP CORE STYLE  -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- FONT AWESOME STYLE  -->
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <!-- CUSTOM STYLE  -->
  <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
  <!--On inclue ici le menu de navigation includes/header.php-->
  <?php include('includes/header.php'); ?>
  <!-- On affiche le titre de la page : TABLEAU DE BORD ADMINISTRATION-->
  <div class="container">
    <div class="row">
      <div class="col">
        <h3>TABLEAU DE BORD ADMINISTRATION</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3 col-md-3">
        <!-- On affiche la carte Nombre de livres -->
        <div class="alert alert-succes text-center">
          <span class="fa fa-book fa-5x">
            <h3><?php echo $result[0]; ?></h3>
          </span>
          Nombre de livre
        </div>
      </div>
      <div class="col-sm-3 col-md-3">
        <!-- On affiche la carte Livres en pr�t -->
        <div class="alert alert-succes text-center">
          <span class="fa fa-book fa-5x">
            <h3><?php echo $result1[0]; ?></h3>
          </span>
          Livres en pret
        </div>
      </div>
      <div class="col-sm-3 col-md-3">
        <!-- On affiche la carte Livres retourn�s -->
        <div class="alert alert-succes text-center">
          <span class="fa fa-bars fa-5x">
            <h3><?php echo $result2[0]; ?></h3>
          </span>
          Livres retournés
        </div>
      </div>
      <div class="col-sm-3 col-md-3">
        <!-- On affiche la carte Lecteurs -->
        <div class="alert alert-succes text-center">
          <span class="fa fa-recycle fa-5x">
            <h3><?php echo $result3[0]; ?></h3>
          </span>
          Lecteurs
        </div>
      </div>
      <div class="col-sm-3 col-md-3">
        <!-- On affiche la carte Auteurs -->
        <div class="alert alert-succes text-center">
          <span class="fa fa-users fa-5x">
            <h3><?php echo $result4[0]; ?></h3>
          </span>
          Auteurs
        </div>
      </div>
      <div class="col-sm-3 col-md-3">
        <!-- On affiche la carte Cat�gories -->
        <div class="alert alert-succes text-center">
          <span class="fa fa-file-archive-o fa-5x">
            <h3><?php echo $result5[0]; ?></h3>
          </span>
          Catégories
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
  <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php'); ?>
  <!-- FOOTER SECTION END-->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>