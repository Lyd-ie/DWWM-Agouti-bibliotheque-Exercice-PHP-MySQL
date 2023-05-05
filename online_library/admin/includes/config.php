<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','library');
// Establish database connection.
try
{
    // Connexion a la base
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
}
catch (PDOException $e)
{
    exit("Error: " . $e->getMessage());
}

// Fonction de nettoyage/validation des données
function valid_donnees($donnees) {
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    // $donnees = !empty($donnees);
    return $donnees;
}

// Empêche les erreurs de s'afficher à l'écran, mais toujours dans error_log
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
?>