<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../adminlogin.php');
} else {
      // Sinon on continue. On récupère l'id du livre à éditer via un GET
  
      if(isset($_GET['editer'])) {
            $id = valid_donnees($_GET['editer']);
            if (!empty($id)
            && (int)$id) {
                  // On prepare la requete de recherche des elements de la categorie dans tblauthors
                  $bookInfo = "SELECT *
                              FROM tblbooks
                              JOIN tblcategory
                              ON tblbooks.CatId = tblcategory.id
                              JOIN tblauthors
                              ON tblbooks.AuthorId = tblauthors.id
                              WHERE tblbooks.id=:id";
                  $query = $dbh->prepare($bookInfo);
                  $query->bindParam(':id', $id, PDO::PARAM_INT);
                  $query->execute();
                  $resultBook = $query->fetch();
                  // var_dump($resultBook);

                  // Si l'id est retrouvé en base de donnée, la page d'édition s'affiche
                  if (!$resultBook) {
                        $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                        header('location:manage-books.php');
                  } else {
                        $status = (int)1;

                        // Requête pour afficher le select "Catégorie"
                        $selectCat = "SELECT id, CategoryName FROM tblcategory WHERE Status=:status";
                        $query2 = $dbh->prepare($selectCat);
                        $query2->bindParam(':status', $status, PDO::PARAM_INT);
                        
                        // Requête pour afficher le select "Auteurs"
                        $selectAut = "SELECT id, AuthorName FROM tblauthors WHERE Status=:status";
                        $query3 = $dbh->prepare($selectAut);
                        $query3->bindParam(':status', $status, PDO::PARAM_INT);
                  }
            }
      }
  
      if (TRUE === isset($_POST['submit'])) {
            // On recupere le nom et le statut de la categorie
            $id = valid_donnees($resultBook['0']);
            $title = ucfirst(valid_donnees($_POST['title']));
            if (!$title) {
                  $title = valid_donnees($resultBook['BookName']);
            }
            $category = valid_donnees($_POST['categoryName']);
            $author = valid_donnees($_POST['authorName']);
            $ISBN = valid_donnees($_POST['ISBN']);
            if (!$ISBN) {
                  $ISBN = $resultBook['ISBNNumber'];
            }
            $prix = valid_donnees($_POST['price']);
            if (!$prix ) {
                  $prix  = valid_donnees($resultBook['BookPrice']);
            }

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
                  $update =  "UPDATE tblbooks
                              SET BookName=:bookname, CatId=:catid, AuthorId=:authorid, ISBNNumber=:isbnnumber, BookPrice=:bookprice
                              WHERE id=:id";
                  $query4 = $dbh->prepare($update);
                  $query4->bindParam(':bookname', $title, PDO::PARAM_STR);
                  $query4->bindParam(':catid', $category, PDO::PARAM_INT);
                  $query4->bindParam(':authorid', $author, PDO::PARAM_INT);
                  $query4->bindParam(':isbnnumber', $ISBN, PDO::PARAM_INT);
                  $query4->bindParam(':bookprice', $prix, PDO::PARAM_INT);
                  $query4->bindParam(':id', $resultBook['0'], PDO::PARAM_INT);
                  $query4->execute();

                  // On éxecute la requete
                  // On stocke dans $_SESSION le message correspondant au resultat de loperation
                  if ($query4->rowCount() > 0) {
                        $_SESSION['message'] = "Livre édité avec succès";
                        header('location:manage-books.php');
                  }
                  else {
                        $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                        header('location:manage-books.php');
                  }
            }
      }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>Gestion de bibliothèque en ligne | Livres</title>
      <!-- BOOTSTRAP CORE STYLE  -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
      <!-- FONT AWESOME STYLE  -->
      <link href="assets/css/font-awesome.css" rel="stylesheet">
      <!-- CUSTOM STYLE  -->
      <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!--MENU SECTION START-->
<?php include('includes/header.php'); ?>
<!-- MENU SECTION END-->
<!-- On affiche le titre de la page "Editer la categorie-->
<div class="container">
      <div class="row">
            <div class="col-md-12">
                  <h4 class="header-line">Editer le livre</h4>
            </div>
      </div>
        <!-- On affiche le formulaire dedition-->
      <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <form class="col" method="post">
                        <div>
                              <p style="font-weight:bold;">Informations livre</p>
                        </div>
                        <div>
                              <div class="form-group">
                                    <p>Titre</p>
                                    <input type="text" name="title" maxlength="30" pattern="^[A-Za-zÀ-ÿ0-9 '-]+$" placeholder="<?php echo $resultBook['BookName'] ?>">
                              </div>

                              <div class="form-group">
                                    <p>Catégorie</p>
                                    <select name="categoryName">
                                    <option value="<?php echo $resultBook['CatId'] ?>" selected hidden><?php echo $resultBook['CategoryName'] ?></option>
                                    <?php 
                                          $query2->execute();
                                          while ($resultCat = $query2->fetch()) {
                                    ?>
                                          <option value="<?php echo $resultCat['id'] ?>"><?php echo $resultCat['CategoryName'] ?></option>
                                    <?php
                                          }
                                    ?>
                                    </select>
                              </div>

                              <div class="form-group">
                                    <p>Auteur</p>
                                    <select name="authorName">
                                    <option value="<?php echo $resultBook['AuthorId'] ?>" selected hidden><?php echo $resultBook['AuthorName'] ?></option>
                                    <?php $query3->execute();
                                          while ($resultAut = $query3->fetch()) { ?>
                                          <option value="<?php echo $resultAut['id'] ?>"><?php echo $resultAut['AuthorName'] ?></option>
                                    <?php
                                          }
                                    ?>
                                    </select>
                              </div>

                              <div class="form-group">
                                    <p>ISBN</p>
                                    <input type="number" name="ISBN" min="1" max="9999999999999" placeholder="<?php echo $resultBook['ISBNNumber'] ?>">
                                    <label>Le numero ISBN doit être unique</label>
                              </div>

                              <div class="form-group">
                                    <p>Prix (€)</p>
                                    <input type="number" name="price" min="1" max="999" placeholder="<?php echo $resultBook['BookPrice'] ?>">
                              </div>

                              <button type="submit" name="submit" class="btn btn-info">Mettre à jour</button>
                        </div>
                  </form>
            </div>
      </div>
</div>
<!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php'); ?>
<!-- FOOTER SECTION END-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>