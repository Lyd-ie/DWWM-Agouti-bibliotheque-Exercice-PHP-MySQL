<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../adminlogin.php');
} else {
      // Sinon on continue. On récupère l'id du livre à éditer via un GET
  
      if(isset($_GET['editer'])) {
            $id = valid_donnees($_GET['editer']);
            
            if (!empty($id)
            && (int)$id) {
                  
                  // On prepare la requete de mise a jour
                  $issueInfo = "SELECT *
                              FROM tblissuedbookdetails
                              JOIN tblreaders
                                    ON tblissuedbookdetails.ReaderID = tblreaders.ReaderId
                              JOIN tblbooks
                                    ON tblissuedbookdetails.BookId = tblbooks.ISBNNumber
                              WHERE tblissuedbookdetails.id=:issueid";
                  $query = $dbh->prepare($issueInfo);
                  $query->bindParam(':issueid', $id, PDO::PARAM_INT);
                  $query->execute();
                  $resultInfo = $query->fetch();

                  // Si l'id est retrouvé en base de donnée, la page d'édition s'affiche
                  if (!$resultInfo) {
                        $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                        header('location:manage-issued-books.php');
                  }
            }
      }

      // Lors de la soumission du formulaire d'édition
      if (TRUE === isset($_POST['submit'])) {

            // On recupere l'id du lecteur, l'ISBN du livre et le statut de retour
            $reader = valid_donnees($_POST['readerId']);
            if (!$reader) {
                  $reader = valid_donnees($resultInfo['ReaderId']);
            }

            $ISBN = valid_donnees($_POST['ISBN']);
            if (!$ISBN) {
                  $ISBN = valid_donnees($resultInfo['ISBNNumber']);
            }

            $returnStatus = valid_donnees($_POST['returnStatus']);
            $possible = array('1', '0');

            if (!empty($reader)
            && strlen($reader) == 6
            && preg_match("#^[A-Za-z0-9]+$#", $reader)
            && !empty($ISBN)
            && strlen($ISBN) <= 13
            && $returnStatus !== FALSE
            && in_array($returnStatus, $possible)) {

                  // On prepare la requete de mise à jour
                  $sql = "UPDATE tblissuedbookdetails 
                        SET BookId=:bookid, ReaderID=:readerid, ReturnStatus=:returnstatus
                        WHERE id=:issueid";
                  $query2 = $dbh->prepare($sql);
                  $query2->bindParam(':bookid', $ISBN, PDO::PARAM_INT);
                  $query2->bindParam(':readerid', $reader, PDO::PARAM_STR);
                  $query2->bindParam(':returnstatus', $returnStatus, PDO::PARAM_INT);
                  $query2->bindParam(':issueid', $id, PDO::PARAM_INT);
                  // On éxecute la requete
                  $query2->execute();

                  if ($returnStatus == "0") {
                        
                        $cancelReturn = "UPDATE tblissuedbookdetails 
                                    SET ReturnDate=NULL
                                    WHERE id=:issueid";
                        $query3 = $dbh->prepare($cancelReturn);
                        // $query3->bindParam(':returndate', $returnDate, PDO::PARAM_INT);
                        $query3->bindParam(':issueid', $id, PDO::PARAM_INT);
                        $query3->execute();
                  }

                  // On éxecute la requete
                  // On stocke dans $_SESSION le message correspondant au resultat de loperation
                  if ($query2->rowCount() > 0) {
                        $_SESSION['message'] = "Emprunt mis à jour";
                        header('location:manage-issued-books.php');
                  }
                  else {
                        $_SESSION['message'] = "Quelque chose s\'est mal passé, veuillez réessayer ultérieurement";
                        header('location:manage-issued-books.php');
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
    <title>Gestion de bibliothèque en ligne | Sorties</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
<!--MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->

<div class="container">
      <div class="row">
                <h4 class="header-line">Editer un retour</h4>
      </div>
      <!-- On affiche le formulaire dedition-->
      <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <form class="col" method="post">
                        <div>
                              <p style="font-weight:bold;">Information emprunt :
                              <span style="font-weight:300; font-style:italic;"><?php echo $resultInfo['BookName'] ?></span><span style="font-weight:300;"> - ISBN n°<?php echo $resultInfo['ISBNNumber'] ?></span></p>
                        </div>
                        <div>
                              <div class="form-group">
                                    <p style="font-weight:bold;">Lecteur :
                                    <span style="font-weight:300;"><?php echo $resultInfo['FullName']." (".$resultInfo['ReaderId'].")" ?></span></p>
                              </div>
                              <div class="form-group">
                                    <p style="font-weight:bold">Modifier lecteur</p>
                                    <input type="text" name="readerId" placeholder="SIDxxx" maxlength="6" pattern="^[A-Za-z0-9]+$" onBlur="getReader(readerId.value)">
                                    <span id="name"></span>
                              </div>
                              <div class="form-group">
                                    <p style="font-weight:bold">Modifier livre</p>
                                    <input type="number" name="ISBN" placeholder="Entrer numéro ISBN" min="1" max="9999999999999"  onBlur="getBook(ISBN.value)">
                                    <span id="title"></span>
                              </div>
                              <div>
                                    <p style="font-weight:bold">Livre retourné : &nbsp;&nbsp;
                                    <!-- <input type="hidden" name="returnStatus" value="0"> -->
                                    <input type="radio" name="returnStatus" value="1" <?php echo ($resultInfo['ReturnStatus'] == "1") ? 'checked="checked"' : ''; ?>> <label for="status">Oui</label>
                                    <input type="radio" name="returnStatus" value="0" <?php echo ($resultInfo['ReturnStatus'] == "0") ? 'checked="checked"' : ''; ?>> <label for="status">Non</label>
                              </div>
                              <button type="submit" name="submit" class="btn btn-info">Mettre à jour</button>
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
<script>
      function getReader(readerId) {
            let readerName = document.getElementById("name");
            let submitButton = document.querySelector("button[name='submit']");

            fetch("get_reader.php?readerid="+readerId)
            .then(response => response.json())
            .then(data => { 
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