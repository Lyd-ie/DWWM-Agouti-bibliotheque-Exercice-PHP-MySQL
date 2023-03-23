<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

// Si l'utilisateur est déconnecté
// L'utilisateur est renvoyé vers la page de login : index.php
if (strlen($_SESSION['alogin']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : adminlogin.php
    header('location:../adminlogin.php');
} else {
    $i = 0;

    
    $tblcategory = "SELECT * FROM tblcategory";
    $query = $dbh->prepare($tblcategory);
}

if(isset($_GET['supprimer'])) {
    $catSupp = $_GET['supprimer'];
    $newStatus = "0";

    $tblSupp = "SELECT * FROM tblcategory where CategoryName=:categoryname";
    $query = $dbh->prepare($tblSupp);
    $query->bindParam(':categoryname', $catSupp, PDO::PARAM_STR);
    $query->execute();
    $result2 = $query->fetch();

    $id = $result2['id'];

    $delete =  "UPDATE tblcategory
                SET Status=:status
                WHERE id=:id";
    $query = $dbh->prepare($delete);
    $query->bindParam(':status', $newStatus, PDO::PARAM_INT);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    header('location:manage-categories.php');
}

// On recupere l'identifiant de la catégorie a supprimer

// On prepare la requete de suppression

// On execute la requete

// On informe l'utilisateur du resultat de loperation

// On redirige l'utilisateur vers la page manage-categories.php

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page-->

    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Gérer les catégories</h3>
            </div>
        </div>
    </div>
    <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
    <table class="container">
        <tbody>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Statut</th>
                <th>Créée le</th>
                <th>Mise à jour le</th>
                <th>Action</th>
            </tr>
            <?php
                $query->execute();
                while ($result = $query->fetch()) {
                    $i++;

                    // var_dump($result);

                    if ($result['Status'] == "1") {
                        $result['Status'] = "<span style='color:white; background-color:#62bf52; padding:3px 6px; border-radius:5px;'>Active</span>";
                    } else {
                        $result['Status'] = "<span style='color:white; background-color:#e53947; padding:3px 6px; border-radius:5px;'>Inactive</span>";
                    }
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['CategoryName']; ?></td>
                    <td><?php echo $result['Status']; ?></td>
                    <td><?php echo $result['CreationDate']; ?></td>
                    <td><?php echo $result['UpdationDate']; ?></td>
                    <td> 
                        <a href="edit-category.php?editer=<?php echo $result['CategoryName'] ?>"> <button class="btn btn-info">Editer</button></a>
                        <a href="manage-categories.php?supprimer=<?php echo $result['CategoryName'] ?>"> <button class="btn btn-danger">Supprimer</button></a>
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

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>