<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
  // Si l'utilisateur est déconnecté
  // L'utilisateur est renvoyé vers la page de login : adminlogin.php
  header('location:../adminlogin.php');
} else {

  // Le statut est défini en "actif"
  $status = (int)1;

  // Requête pour afficher les catégories actives dans le form (cf plus bas)
  $selectCat = "SELECT id, CategoryName FROM tblcategory WHERE Status=:status";
  $query = $dbh->prepare($selectCat);
  $query->bindParam(':status', $status, PDO::PARAM_INT);

  // Requête pour afficher les auteurs actifs dans le form (cf plus bas)
  $selectAut = "SELECT id, AuthorName FROM tblauthors";
  $query2 = $dbh->prepare($selectAut);

  // Après soumission du formulaire de creation
  if (TRUE === isset($_POST['create'])) {
    // On recupere les données postées et on fait les vérifications de sécurité
    $title = ucwords(valid_donnees($_POST['title']));
    $category = valid_donnees($_POST['categoryName']);
    $author = valid_donnees($_POST['authorName']);
    $ISBN = valid_donnees($_POST['ISBN']);
    $prix = valid_donnees($_POST['price']);

    if (!empty($title)
    && strlen($title) <= 30
    && preg_match("#^[A-Za-zÀ-ÿ0-9 '-]+$#", $title)
    && !empty($category)
    && !empty($author)
    && !empty($ISBN)
    && strlen($ISBN) <= 13
    && !empty($prix)
    && strlen($prix) <= 3) {

      // On prepare la requete d'insertion dans la table tblbooks
      $sql = "INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice)
              VALUES (:bookname, :catid, :authorid, :isbnnumber, :bookprice)";
      $query3 = $dbh->prepare($sql);
      $query3->bindParam(':bookname', $title, PDO::PARAM_STR);
      $query3->bindParam(':catid', $category, PDO::PARAM_INT);
      $query3->bindParam(':authorid', $author, PDO::PARAM_INT);
      $query3->bindParam(':isbnnumber', $ISBN, PDO::PARAM_INT);
      $query3->bindParam(':bookprice', $prix, PDO::PARAM_INT);
      // On éxecute la requete

      // On stocke dans $_SESSION le message correspondant au resultat de l'opération
      if ($query3->execute() && $query3->rowCount() > 0) {
        $_SESSION["message"] = 'Livre "' . valid_donnees(addslashes($title)) . '" ajouté avec succès';
        header('location:manage-books.php');
      }
      else {
          $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
          header('location:manage-books.php');
      }
    } else {
      echo "<script>alert('Veuillez corriger votre saisie.');</script>";
    }
  }
} ?>

<!DOCTYPE html>
<html lang="FR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
  <!-- BOOTSTRAP CORE STYLE  -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- FONT AWESOME STYLE  -->
  <link href="assets/css/font-awesome.css" rel="stylesheet">
  <!-- CUSTOM STYLE  -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!-- MENU SECTION START -->
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
              <input type="text" name="title" maxlength="30" pattern="^[A-Za-zÀ-ÿ0-9 '-]+$" required>
            </div>

            <div class="form-group">
              <p>Catégorie<span style="color:red;">*</span></p>
              <select name="categoryName" required>
                <option value="" selected disabled>Choisir une catégorie</option>
                <?php 
                  $query->execute();
                  while ($resultCat = $query->fetch()) {
                ?>
                  <option value="<?php echo $resultCat['id'] ?>"><?php echo $resultCat['CategoryName'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <p>Auteur<span style="color:red;">*</span></p>
              <select name="authorName" required>
                <option value="" selected disabled>Choisir un auteur</option>
                <?php $query2->execute();
                  while ($resultAut = $query2->fetch()) { ?>
                  <option value="<?php echo $resultAut['id'] ?>"><?php echo $resultAut['AuthorName'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <p>ISBN<span style="color:red;">*</span></p>
              <input type="number" name="ISBN" min="1" max="9999999999999" required>
              <label>Le numero ISBN doit être unique</label>
            </div>

            <div class="form-group">
              <p>Prix (€)<span style="color:red;">*</span></p>
              <input type="number" name="price" min="1" max="999" required>
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