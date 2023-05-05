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
        // On recupere l'id du lecteur et l'ISBN du livre
        
        $reader = valid_donnees($_POST['readerId']);
        $ISBN = valid_donnees($_POST['ISBN']);
        $returnStatus = (int)0;

        if (!empty($reader)
        && strlen($reader) == 6
        && preg_match("#^[A-Za-z0-9]+$#", $reader)
        && !empty($ISBN)
        && strlen($ISBN) <= 13) {
  
            // On prepare la requete d'insertion dans la table tblcategory
            $sql = "INSERT INTO tblissuedbookdetails (BookId, ReaderId, ReturnStatus) VALUES (:bookid, :readerid, :returnstatus)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':bookid', $ISBN, PDO::PARAM_INT);
            $query->bindParam(':readerid', $reader, PDO::PARAM_STR);
            $query->bindParam(':returnstatus', $returnStatus, PDO::PARAM_INT);
            
            // On éxecute la requete
            // On stocke dans $_SESSION le message correspondant au resultat de l'opération
            if ($query->execute() && $query->rowCount() > 0) {
                $_SESSION["message"] = 'Livre emprunté avec succès';
                header('location:manage-issued-books.php');
            }
            else {
                $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                header('location:manage-issued-books.php');
            }
        } else {
        echo "<script>alert('Veuillez corriger votre saisie.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!--MENU SECTION START-->
<?php include('includes/header.php'); ?>
<!-- MENU SECTION END-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Sortie d'un livre</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <form class="col" method="post">
                    <div>
                        <p style="font-weight:bold;">Sortie d'un livre</p>
                    </div>
                    <div>
                        <!-- Dans le formulaire du sortie, on appelle les fonctions JS de recuperation du nom du lecteur et du titre du livre sur evenement onBlur-->
                        <div class="form-group">
                        <p>Identifiant lecteur<span style="color:red;">*</span></p>
                        <input type="text" name="readerId" placeholder="SIDxxx" maxlength="6" pattern="^[A-Za-z0-9]+$" onBlur="getReader(readerId.value)" required>
                        <p id="name"></p>
                        </div>

                        <div class="form-group">
                        <p>ISBN<span style="color:red;">*</span></p>
                        <input type="number" name="ISBN" min="1" max="9999999999999" onBlur="getBook(ISBN.value)" required>
                        <p id="title"></p>
                        </div>

                        <button type="submit" name="submit" class="btn btn-info">Créer la sortie</button>
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
<script>
    function getReader(readerId) {
        let readerName = document.getElementById("name");
        let submitButton = document.querySelector("button[name='submit']");

        fetch("get_reader.php?readerid="+readerId)
        .then(response => response.json())
        .then(data => { 
        // console.log("data = " + data);
        // console.log(data.name);
        readerName.innerHTML = data.name;

        if (data.name === "Le lecteur est non valide" || data.name === "Le lecteur est bloqué") {
            submitButton.disabled = true;
        }

        else {
            submitButton.disabled = false;
        }
        })
    }

    function getBook(ISBN) {
        let title = document.getElementById("title");
        let submitButton = document.querySelector("button[name='submit']");

        fetch("get_book.php?isbn="+ISBN)
        .then(response => response.json())
        .then(data => { 
            // console.log("data = " + data);
            // console.log(data.name);
            title.innerHTML = data.title;

            if (data.title === "L'ISBN est non valide") {
                submitButton.disabled = true;
            }

            else {
                submitButton.disabled = false;
            }
        })
    }
</script>
</body>
</html>