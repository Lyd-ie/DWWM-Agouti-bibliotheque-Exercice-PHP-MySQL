<?php
session_start();

include('includes/config.php');

// Si l'utilisateur est déconnecté
if (strlen($_SESSION['alogin']) == 0) {
    
    // L'utilisateur est renvoyé vers la page de login : adminlogin.php
    header('location:../adminlogin.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de creation
    if (TRUE === isset($_POST['submit'])) {
        // On recupere le nom et le statut de la categorie
        $category = ucfirst(valid_donnees($_POST['categoryName']));
        $status = valid_donnees($_POST['status']);
        $possible = array('1', '0');
        
        if (!empty($category)
        && strlen($category) <= 20
        && preg_match("#^[A-Za-zÀ-ÿ '-]+$#", $category)
        && $status !== FALSE
        && in_array($status ,$possible)) {

            // On prepare la requete d'insertion dans la table tblcategory
            $sql = "INSERT INTO tblcategory (CategoryName, Status) VALUES (:categoryname, :status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':categoryname', $category, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            // On éxecute la requete
            // $query->execute();
            
            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            if ($query->execute() && $query->rowCount() > 0) {
                $_SESSION["message"] = 'Catégorie "' . addslashes($category) . '" ajoutée avec succès';
                header('location:manage-categories.php');
            }
            else {
                $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                header('location:manage-categories.php');
            }
        } else {
            echo "<script>alert('Veuillez corriger votre saisie. Certains caractères spéciaux ne sont pas acceptés');</script>";
        }
    }
} ?>

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
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page-->
    <!-- On affiche le formulaire de creation-->
    <!-- Par defaut, la categorie est active-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>AJOUTER UNE CATEGORIE</h3>
            </div>
        </div>
        <!--On affiche le formulaire-->
        <form class="col" method="post">
            <div>
                <p>Information catégorie</p>
            </div>
            <div>
                <!--On affiche le nom-->
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="categoryName" maxlength="20" pattern="^[A-Za-zÀ-ÿ '-]+$" required>
                </div>

                <!--On affiche le numero de portable- editable-->
                <div class="form-group">
                    <label>Statut</label>
                    <input type="radio" name="status" value="1" required> <label>Active</label>
                    <input type="radio" name="status" value="0" required> <label>Inactive</label>
                </div>

                <button type="submit" name="submit" class="btn btn-info">Créer</button>
            </div>
        </form>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>