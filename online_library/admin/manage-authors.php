<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // Si l'utilisateur est déconnecté
      // L'utilisateur est renvoyé vers la page de login : adminlogin.php
      header('location:../adminlogin.php');
  } else {
      $i = 0;
  
      
      $tblcategory = "SELECT * FROM tblauthors";
      $query = $dbh->prepare($tblcategory);
  }
      if(isset($_GET['supprimer'])) {
          $catSupp = $_GET['supprimer'];
          $delete = "DELETE FROM tblauthors WHERE AuthorName=:authorname";
          $query = $dbh->prepare($delete);
          $query->bindParam(':authorname', $catSupp, PDO::PARAM_STR);
          $query->execute();
          header('location:manage-authors.php');
      }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des auteurs</title>
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

     <!-- CONTENT-WRAPPER SECTION END-->
     <div class="container">
        <div class="row">
            <div class="col">
                <h3>Gérer les auteurs</h3>
            </div>
        </div>
    </div>
    <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
    <table class="container">
        <tbody>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Créée le</th>
                <th>Mise à jour le</th>
                <th>Action</th>
            </tr>
            <?php
                $query->execute();
                while ($result = $query->fetch()) {
                    $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['AuthorName']; ?></td>
                    <td><?php echo $result['creationDate']; ?></td>
                    <td><?php echo $result['UpdationDate']; ?></td>
                    <td> 
                        <a href="edit-author.php?editer=<?php echo $result['AuthorName'] ?>"> <button class="btn btn-info">Editer</button></a>
                        <a href="manage-authors.php?supprimer=<?php echo $result['AuthorName'] ?>"> <button class="btn btn-danger">Supprimer</button></a>
                    </td>
                </tr>                
            <?php
                }
            ?>
        </tbody>
    </table>

    <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->

    <!-- On affiche le formulaire de gestion des categories-->

    </div>
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
