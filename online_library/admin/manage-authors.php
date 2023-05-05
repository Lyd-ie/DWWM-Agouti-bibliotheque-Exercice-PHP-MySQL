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
    
    // Récupération des données de tblauthors pour affichage (cf plus bas)
    $tblcategory = "SELECT * FROM tblauthors";
    $query = $dbh->prepare($tblcategory);

    if(isset($_GET['supprimer'])) {

        $id = valid_donnees($_GET['supprimer']);
        
        if (!empty($id)
        && strlen($id) <= 4
        && (int)$id) {
            // Mettra le statut en inactif
            $newStatus = (int)0;
            
            // On prépare la requete de mise à jour du statut de 1 à 0 dans la base de donnée
            $delete =  "UPDATE tblauthors
                        SET Status=:status
                        WHERE id=:id";
            $query = $dbh->prepare($delete);
            $query->bindParam(':status', $newStatus, PDO::PARAM_INT);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            // On execute la requete
            
            // On informe l'utilisateur du resultat de l'operation
            // On recharge la page manage-authors.php
            if ($query->execute() && $query->rowCount() > 0) {
                $_SESSION['message'] = "Auteur désactivé";
                header('location:manage-authors.php');
            }
            else {
                $_SESSION['message'] = "Quelque chose s\'est mal passé";
                header('location:manage-authors.php');
            }
        }
    }
} ?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Gestion de bibliothèque en ligne | Gestion des auteurs</title>
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
    <!-- MENU SECTION END-->
    <!-- CONTENT-WRAPPER SECTION END-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Gérer les auteurs</h3>
            </div>
        </div>
    </div>
    <!-- On affiche le formulaire de gestion des categories-->
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
            <?php $query->execute();
                  while ($result = $query->fetch()) {
                    $i++;
                    // Change le résultat de $result['Status'] en "Actif" ou "Inactif"
                    if ($result['Status'] == (int)1) {
                        $result['Status'] = "<span style='color:white; background-color:#62bf52; padding:3px 6px; border-radius:5px;'>&nbsp;Actif&nbsp;</span>";
                    } else {
                        $result['Status'] = "<span style='color:white; background-color:#e53947; padding:3px 6px; border-radius:5px;'>Inactif</span>";
                    } ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $result['AuthorName']; ?></td>
                <td><?php echo $result['Status']; ?></td>
                <td><?php echo $result['creationDate']; ?></td>
                <td><?php echo $result['UpdationDate']; ?></td>
                <td> 
                    <button class="btn btn-info" onclick="window.location.href='edit-author.php?editer=<?php echo $result['id'] ?>'">Editer</button>
                    <button class="btn btn-danger" onclick="window.location.href='manage-authors.php?supprimer=<?php echo $result['id'] ?>'">Supprimer</button>
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