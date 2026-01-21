<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

requireAdmin();

try {
    // Nom du fichier
    $fileName = 'canope_backup_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Headers pour le téléchargement
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Récupérer toutes les tables
    $tables = [];
    $result = $pdo->query('SHOW TABLES');
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    $output = "-- Sauvegarde de la base de données Canopé\n";
    $output .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    // Pour chaque table
    foreach ($tables as $table) {
        // Structure de la table
        $output .= "-- --------------------------------------------------------\n";
        $output .= "-- Structure de la table `$table`\n";
        $output .= "-- --------------------------------------------------------\n\n";
        
        $output .= "DROP TABLE IF EXISTS `$table`;\n";
        
        $createTableStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
        $output .= $createTableStmt[1] . ";\n\n";
        
        // Données de la table
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $output .= "-- Données de la table `$table`\n\n";
            
            foreach ($rows as $row) {
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }
                
                $output .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            
            $output .= "\n";
        }
    }
    
    $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    // Afficher le SQL
    echo $output;
    
} catch (Exception $e) {
    // Nettoyer les headers déjà envoyés
    if (!headers_sent()) {
        header('Location: database_backup.php?error=' . urlencode($e->getMessage()));
    } else {
        echo "Erreur: " . $e->getMessage();
    }
    exit;
}