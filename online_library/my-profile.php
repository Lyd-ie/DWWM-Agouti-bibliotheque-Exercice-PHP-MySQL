<?php 
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {
	// Si l'utilisateur est déconnecté, l'utilisateur est renvoyé vers la page de login : index.php
  header('location:index.php');
} else {
	// On récupère l'identifiant du lecteur dans le tableau $_SESSION
    // print_r($_SESSION); 
    $sessionId = $_SESSION['rdid'];
    

	// On prepare la requete permettant d'obtenir 
    $currentUser = "SELECT * FROM tblreaders WHERE ReaderID=:readerid";
	$query = $dbh->prepare($currentUser);
    $query->bindParam(':readerid', $sessionId, PDO::PARAM_STR);
	// On execute la requete et on stocke le resultat de recherche
	$query->execute();
     // On stocke le résultat dans une variable
	$result = $query->fetch(PDO::FETCH_ASSOC);
    // print_r($result);

    $readerId = $result['ReaderId'];
    $regDate = $result['RegDate'];
    $updateDate = $result['UpdateDate'];
    $status = $result['Status'];

    $fullName = $result['FullName'];
    $tel = $result['MobileNumber'];
    $mail = $result['EmailId'];

    // Sinon on peut continuer. Apres soumission du formulaire de profil
    if (TRUE === isset($_POST['update'])) {

        $newName = $_POST['fullname']; // On récupère le nom saisi par le lecteur
        if (!$newName) {
            $newName = $fullName;
        }
        $newTel = $_POST['mobilenumber']; // On récupère le numéro de portable
        if (!$newTel) {
            $newTel = $tel;
        }
        $newMail = $_POST['emailid']; // On récupère l'email
        if (!$newMail) {
            $newMail = $mail;
        }

        // On update la table tblreaders avec ces valeurs
        $sql = "UPDATE tblreaders
        SET FullName=:fullname, MobileNumber=:mobilenumber, EmailId=:emailid
        WHERE ReaderId=:readerid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fullname', $newName, PDO::PARAM_STR);
        $query->bindParam(':emailid', $newMail, PDO::PARAM_STR);
        $query->bindParam(':mobilenumber', $newTel, PDO::PARAM_INT);
        $query->bindParam(':readerid', $readerId, PDO::PARAM_STR);
        // On éxecute la requete
        $query->execute();

        // On informe l'utilisateur du resultat de l'operation
        echo "<script> 
                alert('Votre profil a bien été mis à jour.');
                location.href = 'http://localhost/online_formapro/projet_agouti__online_library_pt1/online_library/my-profile.php';     
              </script>";
    }
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php');?>
    <!--On affiche le titre de la page : EDITION DU PROFIL-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>MON COMPTE</h3>
            </div>
        </div>
        <!--On affiche le formulaire-->
        <form class="col" method="post">
            <!--On affiche l'identifiant - non editable-->
            <p>Identifiant : <span><?php echo $readerId ?></span></p>

            <!--On affiche la date d'enregistrement - non editable-->
            <p>Date d'enregistrement : <span><?php echo $regDate ?></span></p>

            <!--On affiche la date de derniere mise a jour - non editable-->
            <p>Dernière mise à jour : <span><?php echo $updateDate ?></span></p>

            <!--On affiche la statut du lecteur - non editable-->
            <p>Statut : <span class="userStatus"><?php echo $status ?></span></p>

            <!--On affiche le nom complet - editable-->
            <div class="form-group">
                <label>Nom complet :</label>
                <input class="fullname" type="text" name="fullname" placeholder='<?php echo $fullName ?>'>
            </div>

            <!--On affiche le numero de portable- editable-->
            <div class="form-group">
                <label>Portable :</label>
                <input class="mobilenumber" type="tel" name="mobilenumber" placeholder='<?php echo $tel ?>'>
            </div>

            <!--On affiche l'email- editable-->
            <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->
            <div class="form-group">
                <label>Email :</label>
                <input class="emailid" type="email" name="emailid" onBlur="checkAvailability(emailid.value)" placeholder='<?php echo $mail ?>'>
                <span id="emailCheck"></span>
            </div>

            <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
        </form>
    </div>
    <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel AJAX vers check_availability.php
        function checkAvailability(emailid) {

            if (emailid) {
                let emailCheck = document.getElementById("emailCheck");
                let submitButton = document.querySelector("button[name='update']");

                fetch("check_availability.php?email="+emailid)
                .then(response => response.json())
                .then(data => { // console.log("data = " + data);
                
                    switch(data) {
                        case 1 : //email non valide
                            emailCheck.innerHTML = "email non valide, veuillez corriger votre saisie";
                            emailCheck.style.fontWeight = "800";
                            emailCheck.style.color = "red";
                            submitButton.disabled = true;
                            break;
                        case 2 : // email existe déjà dans la base de donnée
                            emailCheck.innerHTML = "";
                            submitButton.disabled = false;
                            break;
                        case 3 : // email n'existe pas encore dans la base de donnée
                            emailCheck.innerHTML = "";
                            submitButton.disabled = false;
                            break;
                        default :
                            break;
                    }
                })
            }
        }
    </script>
    <?php
    echo "<script>
            let status = document.querySelector('.userStatus');

            if (status.innerHTML === '1') {
                document.querySelector('.userStatus').innerHTML = 'Actif';
                document.querySelector('.userStatus').style.color = 'green';
            }
            else {
                document.querySelector('.userStatus').innerHTML = 'Inactif';
                document.querySelector('.userStatus').style.color = 'red';
            }
        </script>";
    } ?>
</body>
</html>