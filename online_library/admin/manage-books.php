<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // Si l'utilisateur est déconnecté
      // L'utilisateur est renvoyé vers la page de login : adminlogin.php
      header('location:../adminlogin.php');
} else {

      if (!strlen($_SESSION['message']) == 0) {
            $message = $_SESSION['message'];
            echo "<script> window.addEventListener('load', () => {
                    alert('" . $message . "');
                    event.preventDefault();
                    })</script>";
            $_SESSION['message'] = '';
      }

      $i = (int)0;

      $tblcategory = "SELECT * FROM tblbooks";
      $query = $dbh->prepare($tblcategory);
  
      if(isset($_GET['supprimer'])) {
            $id = valid_donnees($_GET['supprimer']);
            
            if (!empty($id)
            && strlen($id) <= 4
            && (int)$id) {
                  $delete = "DELETE FROM tblbooks WHERE id=:id";
                  $query = $dbh->prepare($delete);
                  $query->bindParam(':id', $id, PDO::PARAM_INT);
                  
                  if ($query->execute() && $query->rowCount() > 0) {
                        $_SESSION['message'] = "Livre supprimé avec succès";
                        header('location:manage-books.php');
                  }
                  else {
                        $_SESSION['message'] = "Quelque chose s\'est mal passé";
                        header('location:manage-books.php');
                  }
            } else {
                  $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                  header('location:manage-books.php');
            }
      }
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Gestion de bibliothèque en ligne | Gestion livres</title>
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
      <div class="container">
            <div class="row">
                  <h3>Gérer les livres</h3>
            </div>
      </div>
      <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
      <table class="container">
            <tbody>
                  <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Auteur</th>
                        <th>ISBN</th>
                        <th>Prix</th>
                        <th>Action</th>
                  </tr>

                  <?php
                  $query->execute();
                  while ($result = $query->fetch()) {
                        $i++;
                        $bookName = $result['BookName'];
                        $catId = $result['CatId'];
                        $authorId = $result['AuthorId'];

                        $category = "SELECT CategoryName FROM tblcategory where id=:id";
                        $query2 = $dbh->prepare($category);
                        $query2->bindParam(':id', $catId, PDO::PARAM_INT);
                        $query2->execute();
                        $result2 = $query2->fetch();

                        $author = "SELECT AuthorName FROM tblauthors where id=:id";
                        $query3 = $dbh->prepare($author);
                        $query3->bindParam(':id', $authorId, PDO::PARAM_INT);
                        $query3->execute();
                        $result3 = $query3->fetch();
                  ?>
                  <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $result['BookName']; ?></td>
                        <td><?php echo $result2['CategoryName']; ?></td>
                        <td><?php echo $result3['AuthorName']; ?></td>
                        <td><?php echo $result['ISBNNumber']; ?></td>
                        <td><?php echo $result['BookPrice']." €"; ?></td>
                        <td> 
                              <button class="btn btn-info" onclick="window.location.href='edit-book.php?editer=<?php echo $result['id'] ?>'">Editer</button>
                              <a href="manage-books.php?supprimer=<?php echo $result['id'] ?>"> <button class="btn btn-danger" onclick="return confirm('Souhaitez-vous supprimer ce livre de la bibliothèque ? Cette action est irréversible')">Supprimer</button></a>
                        </td>
                  </tr>                
                  <?php } ?>
            </tbody>
      </table>
<?php include('includes/footer.php');?>
<!-- FOOTER SECTION END-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>