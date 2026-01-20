<?php
/**
 * Admin - Envoyer le token par email
 * Permet de renvoyer le token de suivi au demandeur
 */
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

// ====================================================
// MODE DÉVELOPPEMENT - Mettre à false en production
// En mode dev, les emails sont sauvegardés dans un fichier
// au lieu d'être envoyés
// ====================================================
define('DEV_MODE', true);

// Vérifier que l'utilisateur est admin
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$requestId = (int) ($_POST['request_id'] ?? 0);

if ($requestId <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de demande invalide']);
    exit;
}

try {
    // Récupérer les informations de la demande
    $stmt = $pdo->prepare("SELECT token, email, first_name, last_name, establishment_name FROM request WHERE id = ?");
    $stmt->execute([$requestId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Demande non trouvée']);
        exit;
    }
    
    $to = $request['email'];
    $subject = "Réseau Canopé Corse - Votre numéro de suivi de demande";
    
    // Corps du message en HTML
    $message = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Votre numéro de suivi</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #0B162C, #3A6B56); padding: 30px; text-align: center; border-radius: 12px 12px 0 0;'>
                <h1 style='color: white; margin: 0;'>Réseau Canopé Corse</h1>
            </div>
            <div style='background: #f9f9f9; padding: 30px; border-radius: 0 0 12px 12px;'>
                <p>Bonjour <strong>{$request['first_name']} {$request['last_name']}</strong>,</p>
                
                <p>Vous avez effectué une demande de dotation pour l'établissement <strong>{$request['establishment_name']}</strong>.</p>
                
                <p>Voici votre numéro de suivi pour suivre l'état de votre demande :</p>
                
                <div style='background: #0B162C; color: white; padding: 20px; text-align: center; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;'>Token de suivi</p>
                    <p style='margin: 10px 0 0 0; font-size: 18px; font-weight: bold; font-family: monospace; letter-spacing: 2px;'>{$request['token']}</p>
                </div>
                
                <p>Conservez précieusement ce numéro. Il vous permettra de consulter l'avancement de votre demande à tout moment.</p>
                
                <p style='color: #666; font-size: 14px; margin-top: 30px;'>
                    Cordialement,<br>
                    L'équipe du Réseau Canopé Corse
                </p>
            </div>
            <div style='text-align: center; padding: 20px; color: #999; font-size: 12px;'>
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Headers pour email HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Réseau Canopé Corse <noreply@canope-corse.fr>\r\n";
    $headers .= "Reply-To: noreply@canope-corse.fr\r\n";
    
    // Mode développement: sauvegarder l'email dans un fichier
    if (DEV_MODE) {
        $logDir = __DIR__ . '/email_logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d_H-i-s');
        $filename = $logDir . "/email_{$timestamp}_{$requestId}.html";
        
        $logContent = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Email Preview - {$timestamp}</title>
    <style>
        .email-info { background: #f0f0f0; padding: 15px; margin-bottom: 20px; border-radius: 8px; font-family: monospace; }
        .email-info p { margin: 5px 0; }
        .email-content { border: 2px dashed #ccc; padding: 20px; }
    </style>
</head>
<body>
    <h1>📧 Prévisualisation Email (Mode Développement)</h1>
    <div class='email-info'>
        <p><strong>Date:</strong> " . date('d/m/Y H:i:s') . "</p>
        <p><strong>À:</strong> {$to}</p>
        <p><strong>Sujet:</strong> {$subject}</p>
        <p><strong>Request ID:</strong> {$requestId}</p>
    </div>
    <h2>Contenu de l'email:</h2>
    <div class='email-content'>
        {$message}
    </div>
</body>
</html>";
        
        if (file_put_contents($filename, $logContent)) {
            echo json_encode([
                'success' => true, 
                'message' => "📧 [DEV] Email sauvegardé! Ouvrir: admin/email_logs/email_{$timestamp}_{$requestId}.html"
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Erreur lors de la sauvegarde du fichier email"
            ]);
        }
    } else {
        // Mode production: envoyer vraiment l'email
        if (mail($to, $subject, $message, $headers)) {
            echo json_encode([
                'success' => true, 
                'message' => "Email envoyé avec succès à {$to}"
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Erreur lors de l'envoi de l'email. Vérifiez la configuration du serveur mail."
            ]);
        }
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>
