<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../adminlogin.php');
} else {
    // Sinon

    if(isset($_GET['editer'])) {
        $catName = $_GET['editer'];

        $sql = "SELECT id, Status FROM tblcategory WHERE CategoryName=:categoryname";
        $query = $dbh->prepare($sql);

        $query->bindParam(':categoryname', $catName, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();

        $id = $result['id'];
        $status = $result['Status'];

        if (TRUE === isset($_POST['edit'])) {
            // On recupere le nom et le statut de la categorie
            $category = ucfirst($_POST['categoryName']);
            if (!$category) {
                $category = $catName;
            }

            $status = $_POST['status'];
    
            // On prepare la requete d'insertion dans la table tblcategory
            $update =  "UPDATE tblcategory
                        SET CategoryName=:categoryname, Status=:status
                        WHERE id=:id";
            $query = $dbh->prepare($update);
            $query->bindParam(':categoryname', $category, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            // On éxecute la requete
            $query->execute();
    
            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            $_SESSION["catEditMsg"] = 'Catégorie mise à jour';
            $sessionMsg = $_SESSION["catEditMsg"];

            header('location:manage-categories.php');
        }
    }}

    // Apres soumission du formulaire de categorie

    // On recupere l'identifiant, le statut, le nom

    // On prepare la requete de mise a jour

    // On prepare la requete de recherche des elements de la categorie dans tblcategory

    // On execute la requete

    // On stocke dans $_SESSION le message "Categorie mise a jour"

    // On redirige l'utilisateur vers edit-categories.php


?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Gestion de bibliothèque en ligne | Categories</title>
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
    <!-- On affiche le titre de la page "Editer la categorie-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer la categorie</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form class="col" method="post">
                <div>
                    <caption>Catégorie à éditer</caption>
                </div>
                <div>
                    <!--On affiche le nom-->
                    <div class="form-group">
                        <label>Nom de catégorie</label>
                        <input type="text" name="categoryName" placeholder="<?php echo $catName ?>" >
                    </div>

                    <!--On affiche le numero de portable- editable-->
                    <div class="form-group">
                        <label>Statut</label>
                        <input type="radio" name="status" value="1" <?php echo ($result['Status'] == "1") ? 'checked="checked"' : ''; ?>> <label for="status">Active</label>
                        <input type="radio" name="status" value="0" <?php echo ($result['Status'] == "0") ? 'checked="checked"' : ''; ?>> <label for="status">Inactive</label>
                    </div>

                    <button type="submit" name="edit" class="btn btn-info">Editer</button>
                </div>
            </form>
            </div>
        </div>

        <!-- Si la categorie est active (status == 1)-->
        <!-- On coche le bouton radio "actif"-->
        <!-- Sinon-->
        <!-- On coche le bouton radio "inactif"-->

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>