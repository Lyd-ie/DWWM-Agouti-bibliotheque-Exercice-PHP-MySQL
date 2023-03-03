<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['login']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas

// Le code est correct, on peut continuer
// On recupere le nom de l'utilisateur saisi dans le formulaire

// On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction md5)

// On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
// depuis la table admin

// Si le resultat de recherche n'est pas vide 
// On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
// On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)

// sinon le login est refuse. On le signal par une popup

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <!--On affiche le titre de la page-->

        <!--On affiche le formulaire de login-->

        <!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>