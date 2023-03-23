<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
     // On le redirige vers la page de login  
     header('location:../adminlogin.php');
 } else {
     // Sinon
 
     if(isset($_GET['editer'])) {
         $authName = $_GET['editer'];
 
         $sql = "SELECT id FROM tblauthors WHERE AuthorName=:authorname";
         $query = $dbh->prepare($sql);
 
         $query->bindParam(':authorname', $authName, PDO::PARAM_STR);
         $query->execute();
         $result = $query->fetch();
 
         $id = $result['id'];
 
         if (TRUE === isset($_POST['edit'])) {
             // On recupere le nom et le statut de la categorie
             $author = ucwords($_POST['authorName']);
             if (!$author) {
               $author = $authName;
             }
     
             // On prepare la requete d'insertion dans la table tblcategory
             $update =  "UPDATE tblauthors
                         SET AuthorName=:authorname
                         WHERE id=:id";
             $query = $dbh->prepare($update);
             $query->bindParam(':authorname', $author, PDO::PARAM_STR);
             $query->bindParam(':id', $id, PDO::PARAM_INT);
             // On éxecute la requete
             $query->execute();


          $_SESSION["authEditMsg"] = "Auteur édité avec succès";
          $sessionMsg = $_SESSION["authEditMsg"];
          echo '<script> alert("'.$sessionMsg.'");
                        document.location.href="manage-authors.php"; </script>';
         }
     }
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Auteurs</title>

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
<!-- MENU SECTION END-->

<div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer un.e auteur.rice</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form class="col" method="post">
                <div>
                    <caption>Auteur.rice à éditer</caption>
                </div>
                <div>
                    <!--On affiche le nom-->
                    <div class="form-group">
                        <label>Nom de l'auteur.rice</label>
                        <input type="text" name="authorName" placeholder="<?php echo $authName ?>" >
                    </div>

                    <button type="submit" name="edit" class="btn btn-info">Editer</button>
                </div>
            </form>
            </div>
        </div>

     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
