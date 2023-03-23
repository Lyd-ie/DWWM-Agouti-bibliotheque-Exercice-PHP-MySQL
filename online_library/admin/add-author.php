<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // Si l'utilisateur est déconnecté
      // L'utilisateur est renvoyé vers la page de login : adminlogin.php
      header('location:../adminlogin.php');
  } else {
      // Sinon on peut continuer. Après soumission du formulaire de creation
      if (TRUE === isset($_POST['create'])) {
          // On recupere le nom et le statut de la categorie
          $nom = ucfirst($_POST['authorName']);
  
          // On prepare la requete d'insertion dans la table tblcategory
          $sql = "INSERT INTO tblauthors (AuthorName) VALUE (:authorname)";
          $query = $dbh->prepare($sql);
          $query->bindParam(':authorname', $nom, PDO::PARAM_STR);
          // On éxecute la requete
          $query->execute();
          $result = $query->fetch();


          // On stocke dans $_SESSION le message correspondant au resultat de loperation
          $_SESSION["authCreateMsg"] = "Auteur créé avec succès";
          $sessionMsg = $_SESSION["authCreateMsg"];
          echo '<script> alert("'.$sessionMsg.'");
                        document.location.href="manage-authors.php"; </script>';
      }
} ?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
     <!-- CONTENT-WRAPPER SECTION END-->
     <div class="container">
        <div class="row">
            <div class="col">
                <h3>AJOUTER UN AUTEUR</h3>
            </div>
        </div>
        <!--On affiche le formulaire-->
        <form class="col" method="post">
            <div>
                <caption>Information auteur</caption>
            </div>
            <div>
                <!--On affiche le nom-->
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="authorName" required>
                </div>

                <button type="submit" name="create" class="btn btn-info">Créer</button>
            </div>
        </form>
    </div>
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
