<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // Si l'utilisateur est déconnecté
      // L'utilisateur est renvoyé vers la page de login : adminlogin.php
      header('location:../adminlogin.php');
} else {

      // Affiche une popup avec le message d'ajout de catégorie, si celui-ci a été créé
      if (!strlen($_SESSION['message']) == 0) {
      $message = $_SESSION['message'];
      echo "<script> window.addEventListener('load', () => {
            alert('" . $message . "');
            event.preventDefault();
            })</script>";
      $_SESSION['message'] = '';
      }

      // Définit l'indice # du tableau généré
      $i = (int)0;

      // Requête préparée afin d'afficher les différentes sorties, voir son exécution plus bas
      $sql = "SELECT *
              FROM tblissuedbookdetails
              JOIN tblreaders
              ON tblissuedbookdetails.ReaderID = tblreaders.ReaderId
              JOIN tblbooks
              ON tblissuedbookdetails.BookId = tblbooks.ISBNNumber";
      $query = $dbh->prepare($sql);
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!--MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- CONTENT-WRAPPER SECTION END-->
      <div class="container">
            <div class="row">
                  <h3>Gestion des sorties</h3>
            </div>
      </div>
      <!-- On affiche la liste des sorties contenues dans $results sous la forme d'un tableau -->
      <table class="container">
            <tbody>
                  <tr>
                        <th>#</th>
                        <th>Lecteur</th>
                        <th>Titre</th>
                        <th>ISBN</th>
                        <th>Sortie faite le</th>
                        <th>Retour le</th>
                        <th>Action</th>
                  </tr>
                  <?php
                        $query->execute();
                        while ($result = $query->fetch()) {
                              $i++;
                              // var_dump($result);
                              $returnDate = $result['ReturnDate'];
                              if (!$returnDate) {
                              $returnDate = "<span style='color:red'>Non retourné</span>";
                        }
                  ?>
                  <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $result['FullName']; ?></td>
                        <td><?php echo $result['BookName']; ?></td>
                        <td><?php echo $result['ISBNNumber']; ?></td>
                        <td><?php echo $result['IssuesDate']; ?></td>
                        <td><?php echo $returnDate; ?></td>
                        <td><button class="btn btn-info" onclick="window.location.href='edit-issue-book.php?editer=<?php echo $result['0'] ?>'">Editer</button></td>
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