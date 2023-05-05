<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../adminlogin.php');
} else {

    // Sinon on continue. On récupère l'id de la catégorie à éditer via un GET
    if(isset($_GET['editer'])) {
        $id = valid_donnees($_GET['editer']);

        if (!empty($id)
        && strlen($id) <= 4
        && (int)$id) {

            // On prepare la requete de recherche des elements de la categorie dans tblcategory
            $sql = "SELECT CategoryName, Status FROM tblcategory WHERE id=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();

            $catName = $result['CategoryName'];

            if (!$result) {
                $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                header('location:manage-categories.php');
            }
        } else {
            $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
            header('location:manage-categories.php');
        }
    }

    // Apres soumission du formulaire de categorie
    if (TRUE === isset($_POST['submit'])) {
        // On recupere le nom et le statut de la categorie
        $category = ucfirst(valid_donnees($_POST['categoryName']));
        if (!$category) {
            $category = valid_donnees($catName);
        }

        $status = valid_donnees($_POST['status']);
        $possible = array('1', '0');

        if ($status !== FALSE
        && !empty($category)
        && strlen($category) <= 20
        && preg_match("#^[A-Za-zÀ-ÿ '-]+$#", $category)
        && in_array($status, $possible)) {

            // On prepare la requete de mise a jour
            $update =  "UPDATE tblcategory
                        SET CategoryName=:categoryname, Status=:status
                        WHERE id=:id";
            $query2 = $dbh->prepare($update);
            $query2->bindParam(':categoryname', $category, PDO::PARAM_STR);
            $query2->bindParam(':status', $status, PDO::PARAM_INT);
            $query2->bindParam(':id', $id, PDO::PARAM_INT);
            // On éxecute la requete
            $query2->execute();

            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            if ($query2->rowCount() > 0) {
                $_SESSION['message'] = "Catégorie mise à jour";
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
}
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
                        <p>Catégorie à éditer</p>
                    </div>
                    <div>
                        <!--On affiche le nom-->
                        <div class="form-group">
                            <label>Nom de catégorie</label>
                            <input type="text" name="categoryName" maxlength="20" pattern="^[A-Za-zÀ-ÿ '-]+$" placeholder="<?php echo $catName ?>" >
                        </div>

                        <div class="form-group">
                            <label>Statut</label>
                            <!-- Si la categorie est active (status == 1) on coche le bouton radio "actif"-->
                            <input type="radio" name="status" value="1" <?php echo ($result['Status'] == (int)1) ? 'checked="checked"' : ''; ?>>
                            <label>Active</label>
                             <!-- Sinon on coche le bouton radio "inactif"-->
                            <input type="radio" name="status" value="0" <?php echo ($result['Status'] == (int)0) ? 'checked="checked"' : ''; ?>>
                            <label>Inactive</label>
                        </div>

                        <button type="submit" name="submit" class="btn btn-info">Editer</button>
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