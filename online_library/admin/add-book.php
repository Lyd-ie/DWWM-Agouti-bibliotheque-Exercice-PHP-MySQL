<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
  // Si l'utilisateur est déconnecté
  // L'utilisateur est renvoyé vers la page de login : adminlogin.php
  header('location:../adminlogin.php');
} else {

  $status = "1";

  $selectCat = "SELECT id, CategoryName FROM tblcategory WHERE Status=:status";
  $query = $dbh->prepare($selectCat);
  $query->bindParam(':status', $status, PDO::PARAM_INT);
  
  $selectAut = "SELECT id, AuthorName FROM tblauthors";
  $query2 = $dbh->prepare($selectAut);

  // Sinon on peut continuer. Après soumission du formulaire de creation

  if (TRUE === isset($_POST['create'])) {
      // On recupere le nom et le statut de la categorie
      
      $title = ucfirst($_POST['title']);
      $category = $_POST['categoryName'];
      $author = $_POST['authorName'];
      $ISBN = $_POST['ISBN'];
      $prix = $_POST['price'];

      // On prepare la requete d'insertion dans la table tblcategory
      $sql = "INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice) VALUES (:bookname, :catid, :authorid, :isbnnumber, :bookprice)";
      $query3 = $dbh->prepare($sql);
      $query3->bindParam(':bookname', $title, PDO::PARAM_STR);
      $query3->bindParam(':catid', $category, PDO::PARAM_INT);
      $query3->bindParam(':authorid', $author, PDO::PARAM_INT);
      $query3->bindParam(':isbnnumber', $ISBN, PDO::PARAM_INT);
      $query3->bindParam(':bookprice', $prix, PDO::PARAM_INT);
      // On éxecute la requete
      $query3->execute();

      // On stocke dans $_SESSION le message correspondant au resultat de loperation
      $_SESSION["bookCreateMsg"] = 'Livre ajouté avec succès';
      echo '<script>  alert("Livre ajouté avec succès")
                      document.location.href="manage-books.php"; </script>';
  }
  } ?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
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
        <div class="col">
            <h3>AJOUTER UN LIVRE</h3>
        </div>
    </div>
    <!--On affiche le formulaire-->
    <form class="col" method="post">
        <div>
            <p style="font-weight:bold;">Information livre</p>
        </div>
        <div>
            
            <div class="form-group">
                <p>Titre<span style="color:red;">*</span></p>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
              <p>Catégorie<span style="color:red;">*</span></p>
              <select name="categoryName" required>
                <option selected disabled>Choisir une catégorie</option>
                <?php 
                  $query->execute();
                  while ($resultCat = $query->fetch()) {
                ?>
                  <option value="<?php echo $resultCat['id'] ?>"><?php echo $resultCat['CategoryName'] ?></option>
                <?php
                  }
                ?>
              </select>
            </div>

            <div class="form-group">
              <p>Auteur<span style="color:red;">*</span></p>
              <select name="authorName" required>
                <option selected disabled>Choisir un auteur</option>
                <?php $query2->execute();
                  while ($resultAut = $query2->fetch()) { ?>
                  <option value="<?php echo $resultAut['id'] ?>"><?php echo $resultAut['AuthorName'] ?></option>
                <?php
                  }
                ?>
              </select>
            </div>

            <div class="form-group">
                <p>ISBN<span style="color:red;">*</span></p>
                <input type="number" name="ISBN" required>
                <label>Le numero ISBN doit être unique</label>
            </div>

            <div class="form-group">
                <p>Prix (€)<span style="color:red;">*</span></p>
                <input type="number" name="price" required>
            </div>

            <button type="submit" name="create" class="btn btn-info">Ajouter</button>
        </div>
    </form>
  </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
