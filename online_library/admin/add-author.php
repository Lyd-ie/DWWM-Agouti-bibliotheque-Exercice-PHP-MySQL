<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // Si l'utilisateur est déconnecté
      // L'utilisateur est renvoyé vers la page de login : adminlogin.php
      header('location:../adminlogin.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de creation
    if (TRUE === isset($_POST['submit'])) {
        // On recupere le nom et le statut de l'auteur
        $nom = ucwords(valid_donnees($_POST['authorName']));
        $status = valid_donnees($_POST['status']);
        $possible = array('1', '0');

        if (!empty($nom)
        && strlen($nom) <= 30
        && preg_match("#^[A-Za-zÀ-ÿ '-]+$#", $nom)
        && $status !== FALSE
        && in_array($status ,$possible)) {

            // On prepare la requete d'insertion dans la table tblauthors
            $sql = "INSERT INTO tblauthors (AuthorName, Status) VALUE (:authorname, :status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':authorname', $nom, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            // On éxecute la requete
            // $query->execute();

            // On stocke dans $_SESSION le message correspondant au resultat de l'opération
            if ($query->execute() && $query->rowCount() > 0) {
                $_SESSION["message"] = 'Auteur "' . addslashes($nom) . '" ajouté avec succès';
                header('location:manage-authors.php');
            }
            else {
                $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                header('location:manage-authors.php');
            }
        } else {
            echo "<script>alert('Veuillez corriger votre saisie. Certains caractères spéciaux ne sont pas acceptés');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
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
                <p>Information auteur</p>
            </div>
            <div>
                <!--On affiche le nom-->
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="authorName" maxlength="30" pattern="^[A-Za-zÀ-ÿ '-]+$" required>
                </div>

                <!--On affiche les options de statut-->
                <div class="form-group">
                    <label>Statut</label>
                    <input type="radio" name="status" value="1" required> <label>Actif</label>
                    <input type="radio" name="status" value="0" required> <label>Inactif</label>
                </div>

                <button type="submit" name="submit" class="btn btn-info">Créer</button>
            </div>
        </form>
    </div>
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>