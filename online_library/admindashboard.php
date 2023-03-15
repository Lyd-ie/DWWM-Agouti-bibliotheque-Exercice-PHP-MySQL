<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');

// Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : adminlogin.php
if(strlen($_SESSION['username'])==0) {
	header('location:adminlogin.php');

} else {
     // print_r($_SESSION);
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
     <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
               <span class="navbar-toggler-icon"></span>
          </button>

          <div class="right-div">
               <a href="logout.php" class="btn btn-danger pull-right">DECONNEXION</a>
          </div>    
     </nav>
     <!-- On affiche le titre de la page : Tableau de bord administrateur-->
     <div class="container">
          <div class="row">
               <div class="col">
                    <h3>TABLEAU DE BORD ADMINISTRATEUR</h3>
               </div>
          </div>
     </div>

     <?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     <?php } ?>
</body>
</html>