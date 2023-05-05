<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {
    // Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : index.php
    header('location:../adminlogin.php');
} else {
    // Sinon on affiche la liste des lecteurs de la table tblreaders

    if (!strlen($_SESSION['message']) == 0) {
        $message = $_SESSION['message'];
        echo "<script> window.addEventListener('load', () => {
                alert('" . $message . "');
                event.preventDefault();
                })</script>";
        $_SESSION['message'] = '';
    }

    $i = (int)0;

    $sql = "SELECT ReaderId, FullName, EmailId, MobileNumber, Status, RegDate FROM tblreaders";
    $query = $dbh->prepare($sql);
    $query->execute();

    // Lors d'un click sur un bouton "bloquer", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['bloquer']
    // et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur
    if(isset($_GET['bloquer'])) {
        $reader = valid_donnees($_GET['bloquer']);
        $status = (int)0;

        if (!empty($reader)
        && strlen($reader) == 6
        && preg_match("#^[A-Za-z0-9]+$#", $reader)) {

            $block = "UPDATE tblreaders 
                    SET Status=:status
                    WHERE ReaderId=:readerid";
            $query2 = $dbh->prepare($block);
            $query2->bindParam(':status', $status, PDO::PARAM_INT);
            $query2->bindParam(':readerid', $reader, PDO::PARAM_STR);
            $query2->execute();
            header('location:reg-readers.php');
        } else {
            $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
            header('location:reg-readers.php');
        }
    }

    // Lors d'un click sur un bouton "activer", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['activer']
    // et on met à jour le statut (1) dans la table tblreaders pour cet identifiant de lecteur
    if(isset($_GET['activer'])) {
        $reader = valid_donnees($_GET['activer']);
        $status = (int)1;

        if (!empty($reader)
        && strlen($reader) == 6
        && preg_match("#^[A-Za-z0-9]+$#", $reader)) {

            // echo $blockReader ." status = ". $status;

            $activate = "UPDATE tblreaders 
                    SET Status=:status
                    WHERE ReaderId=:readerid";
            $query3 = $dbh->prepare($activate);
            $query3->bindParam(':status', $status, PDO::PARAM_INT);
            $query3->bindParam(':readerid', $reader, PDO::PARAM_STR);
            $query3->execute();
            header('location:reg-readers.php');
        } else {
            $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
            header('location:reg-readers.php');
        }
    }

    // Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['supprimer']
    // et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur
    if(isset($_GET['supprimer'])) {
        $reader = valid_donnees($_GET['supprimer']);
        $status = (int)2;

        if (!empty($reader)
        && strlen($reader) == 6
        && preg_match("#^[A-Za-z0-9]+$#", $reader)) {

            $supp = "UPDATE tblreaders 
                    SET Status=:status
                    WHERE ReaderId=:readerid";
            $query4 = $dbh->prepare($supp);
            $query4->bindParam(':status', $status, PDO::PARAM_INT);
            $query4->bindParam(':readerid', $reader, PDO::PARAM_STR);
            $query4->execute();
            header('location:reg-readers.php');
        } else {
            $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
            header('location:reg-readers.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!--On inclue ici le menu de navigation includes/header.php-->
<?php include('includes/header.php'); ?>
<!-- Titre de la page (Gestion du Registre des lecteurs) -->
    <div class="container">
        <div class="row">
                <h3>Gestion du Registre des lecteurs</h3>
        </div>
    </div>
    <!--On insère ici le tableau des lecteurs.
    On gère l'affichage des boutons Actif/Inactif/Supprimer en fonction de la valeur du statut du lecteur -->
    <table class="container">
        <tbody>
            <tr>
                <th>#</th>
                <th>ID lecteurs</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Portable</th>
                <th>Date de reg</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            <?php
                $query->execute();
                while ($result = $query->fetch()) {
                    $i++;
                    $readerId = $result['ReaderId'];
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $readerId; ?></td>
                <td><?php echo $result['FullName']; ?></td>
                <td><?php echo $result['EmailId']; ?></td>
                <td><?php echo $result['MobileNumber']; ?></td>
                <td><?php echo $result['RegDate']; ?></td>
                <!-- Si l'utilisateur est bloqué, le statut "bloqué" et les actions "Activer" et "Supprimer" s'affichent -->
            <?php if ($result['Status'] == "0") { ?>
                <td>Bloqué(e)</td>
                <td>
                    <button class="btn btn-info" onclick="window.location.href='reg-readers.php?activer=<?php echo $readerId?>'">Activer</button>
                    <a href="reg-readers.php?supprimer=<?php echo $readerId?>"><button class="btn btn-danger" onclick="return confirm('Souhaitez-vous supprimer cet utilisateur ? Cette action est irréversible')">Supprimer</button></a>
                </td>
            <!-- Si l'utilisateur est actif, le statut "actif" et les actions "Bloquer" et "Supprimer" s'affichent -->
            <?php } else if ($result['Status'] == "1") { ?>
                <td>Actif(ve)</td>
                <td>
                    <button class="btn btn-info" onclick="window.location.href='reg-readers.php?bloquer=<?php echo $readerId?>'" style="background-color:#f2aa1a; border-color: #f2aa1a; ">Bloquer</button>
                    <a href="reg-readers.php?supprimer=<?php echo $readerId?>"><button class="btn btn-danger" onclick="return confirm('Souhaitez-vous supprimer cet utilisateur ? Cette action est irréversible')">Supprimer</button></a>
                </td>
            <!-- Si l'utilisateur est supprimé, le statut "Supprimé" s'affiche et la colonne "Action" reste vide -->
            <?php } else if ($result['Status'] == "2") { ?>
                <td style='color:grey; font-style:italic;'>Supprimé(e)</td>
                <td></td>
            <?php }} ?>    
            </tr>
        </tbody>
    </table>
<!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php'); ?>
<!-- FOOTER SECTION END-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>