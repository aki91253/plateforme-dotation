<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'canope-reseau';
$username = 'user_canope';
$password = 'Fghijkl1234*';
$port = 3306; // Port standard MySQL

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    // Configurer PDO pour lancer des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Message d'erreur si la base de données n'existe pas
    if (strpos($e->getMessage(), "Unknown database") !== false) {
       echo "La base de données '$dbname' n'existe pas. Veuillez l'importer via phpMyAdmin ou exécuter le script SQL fourni.";
    } else {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
