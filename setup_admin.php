<?php
/**
 * Script temporaire pour créer l'utilisateur admin
 * À SUPPRIMER après utilisation
 */

require_once 'includes/db.php';

// Données de l'admin
$email = 'admin.canope@gmail.com';
$password = 'Admin123!';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$lastName = 'Admin';
$firstName = 'Canopé';
$jobTitle = 'Administrateur';

try {
    // Vérifier si l'admin existe déjà
    $checkStmt = $pdo->prepare("SELECT id FROM responsible WHERE email_pro = ?");
    $checkStmt->execute([$email]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        // Mettre à jour le mot de passe
        $updateStmt = $pdo->prepare("UPDATE responsible SET password = ? WHERE email_pro = ?");
        $updateStmt->execute([$hashedPassword, $email]);
        echo "<h2 style='color: green;'>✓ Mot de passe mis à jour pour: $email</h2>";
    } else {
        // Créer l'admin
        $insertStmt = $pdo->prepare("INSERT INTO responsible (last_name, first_name, job_title, email_pro, password) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->execute([$lastName, $firstName, $jobTitle, $email, $hashedPassword]);
        echo "<h2 style='color: green;'>✓ Admin créé avec succès!</h2>";
    }

    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Mot de passe:</strong> $password</p>";
    echo "<p><strong>Hash:</strong> <code>$hashedPassword</code></p>";
    echo "<hr>";
    echo "<p style='color: red;'><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier après utilisation!</p>";
    echo "<p><a href='login.php'>→ Aller à la page de connexion</a></p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Erreur: " . htmlspecialchars($e->getMessage()) . "</h2>";
    
    // Si la colonne password n'existe pas, afficher le SQL pour l'ajouter
    if (strpos($e->getMessage(), 'password') !== false) {
        echo "<p>La colonne 'password' n'existe peut-être pas. Exécutez cette requête SQL:</p>";
        echo "<pre>ALTER TABLE `responsible` ADD COLUMN `password` VARCHAR(255) DEFAULT NULL;</pre>";
    }
}
?>
