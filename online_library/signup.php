<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Après la soummission du formulaire de compte
if (TRUE === isset($_POST['signup'])) {

// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php
    if ($_POST['vercode'] != $_SESSION['vercode']) {

        // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
        echo "<script>alert('Code de vérification incorrect')</script>";

    } else {
        //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur créé.
        $readerId = file('readerid.txt');
        $readerId[0]++; // On incrémente de 1 la valeur lue
        $readerIdTxt = fopen('readerid.txt', "w"); // On ouvre le fichier readerid.txt en écriture
        fwrite($readerIdTxt, $readerId[0]); // On écrit dans ce fichier la nouvelle valeur
        fclose($readerIdTxt); // On referme le fichier
        
        $name = $_POST['fullname']; // On récupère le nom saisi par le lecteur
        $tel = $_POST['mobilenumber']; // On récupère le numéro de portable
        $mail = $_POST['emailid']; // On récupère l'email
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // On récupère le mot de passe
        $status = 1; // On fixe le statut du lecteur à 1 par défaut (actif)
        
        // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders
        $sql = "INSERT INTO tblreaders
            (ReaderId, FullName, EmailId, MobileNumber, Password, Status)
            VALUES
            (:readerid, :fullname, :emailid, :mobilenumber, :password, :status)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':readerid', $readerId[0], PDO::PARAM_STR);
                $query->bindParam(':fullname', $name, PDO::PARAM_STR);
                $query->bindParam(':emailid', $mail, PDO::PARAM_STR);
                $query->bindParam(':mobilenumber', $tel, PDO::PARAM_INT);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_INT);
        // On éxecute la requete
                $query->execute();

        // On récupère le dernier id inséré en bd (fonction lastInsertId)
        $lastId = $dbh->lastInsertId();
        // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $hit[0])
        if ($lastId) {
            echo    "<script> 
                        alert('Votre compte a bien été créé. Votre identifiant lecteur est '+'" .$readerId[0]. "');
                        location.href = 'http://localhost/online_formapro/projet_agouti__online_library_pt1/online_library/index.php';
                    </script>";
        }
        else {
            echo "<script> alert('Une erreur est survenue. Veuillez recommencer votre inscription') </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>CREER UN COMPTE</h3>
            </div>
        </div>
        <!--On affiche le formulaire de creation de compte-->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form method="post" action="signup.php" onSubmit="return valid()">
                    <div class="form-group">
                        <label>Entrez votre nom complet</label>
                        <input type="text" name="fullname" required>
                    </div>

                    <div class="form-group">
                        <label>Portable :</label>
                        <input type="tel" name="mobilenumber" required>
                    </div>
                    
                    <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->
                    <div class="form-group">
                        <label>Email :</label>
                        <input type="email" name="emailid" onBlur="checkAvailability(emailid.value)" required>
                        <span id="emailCheck"></span>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe :</label>
                        <input type="password" name="password" required>
                    </div>
                
                    <div class="form-group">
                        <label id="mdp">Confirmez le mot de passe :</label>
                        <input type="password" name="pswdconfirm" onBlur="valid()" required>
                        <span id="pswdCheck"></span>
                    </div>

                    <!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->
                    <div class="form-group">
                        <label>Code de vérification</label>
                        <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;
                        <img src="captcha.php" alt="captcha">
                    </div>

                    <button type="submit" name="signup" class="btn btn-info">ENREGISTRER</button>
                </form>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <!-- <script> console.log(readerId[0]); </script> -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>

        // On cree une fonction valid() sans paramètre qui désactive le bouton "enregistrer" si les mots de passe saisis dans le formulaire sont différents
        function valid() {
            let password = document.querySelector("input[name='password']");
            let checkPassword = document.querySelector("input[name='pswdconfirm']");
            let submitButton = document.querySelector("button[name='signup']");
            let pswdMsg = document.getElementById("pswdCheck");

            if (password.value == checkPassword.value) {
                submitButton.disabled = false;
                pswdMsg.innerHTML = "&nbsp;Mots de passe identiques";
                pswdMsg.style.fontWeight = "800";
                pswdMsg.style.color = "green";
            }
            else {
                submitButton.disabled = true;
                pswdMsg.innerHTML = "&nbsp;Mots de passe différents";
                pswdMsg.style.fontWeight = "800";
                pswdMsg.style.color = "red";
            }
        }

        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel AJAX vers check_availability.php
        function checkAvailability(emailid) {
            let emailCheck = document.getElementById("emailCheck");
            let submitButton = document.querySelector("button[name='signup']");

            fetch("check_availability.php?email="+emailid)
            .then(response => response.json())
            .then(data => { console.log("data = " + data);
            
                switch(data) {
                    case 1 : //email non valide
                        emailCheck.innerHTML = "Email non valide, veuillez corriger votre saisie";
                        emailCheck.style.fontWeight = "800";
                        emailCheck.style.color = "red";
                        submitButton.disabled = true;
                        break;
                    case 2 : // email existe déjà dans la base de donnée
                        emailCheck.innerHTML = "Cet email possède déjà un compte";
                        emailCheck.style.fontWeight = "800";
                        emailCheck.style.color = "red";
                        submitButton.disabled = true;
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
    </script> 
</body>
</html>