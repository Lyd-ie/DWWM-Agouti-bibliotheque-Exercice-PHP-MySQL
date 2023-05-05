<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../adminlogin.php');
} else {
    // Sinon on continue. On récupère l'id de l'auteur à éditer via un GET
    if(isset($_GET['editer'])) {
        $id = valid_donnees($_GET['editer']);

        if (!empty($id)
        && strlen($id) <= 4
        && (int)$id) {

            // On prepare la requete de recherche des elements de la categorie dans tblauthors
            $sql = "SELECT AuthorName, Status FROM tblauthors WHERE id=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();

            // Si l'id est retrouvé en base de donnée, la page d'édition s'affiche
            if (!$result) {
                $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                header('location:manage-authors.php');
            }
        }
    }

    if (TRUE === isset($_POST['submit'])) {
        // On recupere le nom et le statut de la categorie
        $author = ucwords(valid_donnees($_POST['name']));
        if (!$author) {
            $author = valid_donnees($result['AuthorName']);
        }

        $status = valid_donnees($_POST['status']);
        $possible = array('1', '0');

        if (!empty($author)
        && strlen($author) <= 20
        && preg_match("#^[A-Za-zÀ-ÿ '-]+$#", $author)
        && $status !== FALSE
        && in_array($status, $possible)) {

            // On prepare la requete d'insertion dans la table tblauthors
            $update =  "UPDATE tblauthors
                        SET AuthorName=:authorname, Status=:status
                        WHERE id=:id";
            $query2 = $dbh->prepare($update);
            $query2->bindParam(':authorname', $author, PDO::PARAM_STR);
            $query2->bindParam(':status', $status, PDO::PARAM_INT);
            $query2->bindParam(':id', $id, PDO::PARAM_INT);

            // On éxecute la requete
            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            if ($query2->execute() && $query2->rowCount() > 0) {
                $_SESSION['message'] = "Auteur édité avec succès";
                header('location:manage-authors.php');
            }
            else {
                $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                header('location:manage-authors.php');
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

    <title>Gestion de bibliothèque en ligne | Auteurs</title>

    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer un auteur</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <form class="col" method="post">
                    <div>
                        <p>Auteur à éditer</p>
                    </div>
                    <div>
                        <!--On affiche le nom-->
                        <div class="form-group">
                            <label>Nom de l'auteur</label>
                            <input type="text" name="name" maxlength="20" pattern="^[A-Za-zÀ-ÿ '-]+$" placeholder="<?php echo $result['AuthorName'] ?>" >
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
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>