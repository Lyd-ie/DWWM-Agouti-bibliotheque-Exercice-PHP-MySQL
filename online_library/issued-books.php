<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {
    // Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : index.php
    header('location:index.php');
    // Sinon on peut continuer
} else {

    $sessionId = $_SESSION['rdid'];
    $i = 0;

    $joinedTables = "SELECT *
                     FROM tblissuedbookdetails
                     INNER JOIN tblbooks
                     ON tblissuedbookdetails.BookId = tblbooks.ISBNNumber
                     WHERE ReaderId=:readerid";
    $query = $dbh->prepare($joinedTables);
    $query->bindParam(':readerid', $sessionId, PDO::PARAM_STR);
    $query->execute();
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
      <!--On insere ici le menu de navigation T-->
<?php include('includes/header.php');?>
	<!-- On affiche le titre de la page : LIVRES EMPRUNTES -->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>LIVRES EMPRUNTES</h3>
            </div>
        </div>
    </div>
    <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
    <table class="container">
        <tbody>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>ISBN</th>
                <th>Date de sortie</th>
                <th>Date de retour</th>
            </tr>
            <?php
                while ($result = $query->fetch()) {
                    $i++;
                    $returnDate = $result['ReturnDate'];
                    if (!$returnDate) {
                        $returnDate = 'Non retourné';
                    }
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['BookName']; ?></td>
                    <td><?php echo $result['BookId']; ?></td>
                    <td><?php echo $result['IssuesDate']; ?></td>
                    <td class='return'> <?php echo $returnDate; ?></td>
                </tr>                
            <?php
                }}
            ?>
        </tbody>
    </table>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        const returnDiv = document.querySelector('.return');
        if (returnDiv.innerHTML = 'Non retourné') {
            returnDiv.style.color = 'red';
        }
    </script> 
</body>
</html>