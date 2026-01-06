<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'dotation_db';
$username = 'root';
$password = 'root';
$port = 8889; // Port par défaut pour MAMP MySQL

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    // Configurer PDO pour lancer des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionnel : Message de succès pour le débogage (à commenter en production)
    // echo "Connexion à la base de données réussie !";
} catch (PDOException $e) {
    // En cas d'erreur de connexion, on essaie de créer la base de données si elle n'existe pas
    // Note: Cela ne fonctionnera que si l'utilisateur a les droits suffisants et si l'erreur vient de la db non trouvée
    if (strpos($e->getMessage(), "Unknown database") !== false) {
       echo "La base de données '$dbname' n'existe pas. Veuillez l'importer via phpMyAdmin ou exécuter le script SQL fourni.";
    } else {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
