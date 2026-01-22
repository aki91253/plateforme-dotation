<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

requireAdmin();

$error = '';
$success = '';

// Vérifier l'état actuel de la maintenance
$maintenanceFile = '../maintenance.lock';
$isMaintenanceActive = file_exists($maintenanceFile);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'activate') {
        // Activer le mode maintenance
        $maintenanceData = [
            'activated_at' => date('Y-m-d H:i:s'),
            'activated_by' => $_SESSION['admin_email'],
            'message' => trim($_POST['message'] ?? 'Une maintenance est en cours.')
        ];
        
        if (file_put_contents($maintenanceFile, json_encode($maintenanceData)) !== false) {
            $success = 'Mode maintenance activé avec succès !';
            $isMaintenanceActive = true;
        } else {
            $error = 'Erreur lors de l\'activation du mode maintenance.';
        }
    } elseif ($action === 'deactivate') {
        // Désactiver le mode maintenance
        if (unlink($maintenanceFile)) {
            $success = 'Mode maintenance désactivé avec succès !';
            $isMaintenanceActive = false;
        } else {
            $error = 'Erreur lors de la désactivation du mode maintenance.';
        }
    }
}

// Récupérer les infos de maintenance si active
$maintenanceInfo = null;
if ($isMaintenanceActive && file_exists($maintenanceFile)) {
    $maintenanceInfo = json_decode(file_get_contents($maintenanceFile), true);
}

include 'includes/admin_header.php';
?>

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center gap-4 mb-6">
            <a href="index.php" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mode Maintenance</h1>
                <p class="text-gray-500 text-sm">Gérer l'accès au site public</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <!-- État actuel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 <?= $isMaintenanceActive ? 'bg-orange-100' : 'bg-green-100' ?> rounded-xl flex items-center justify-center">
                        <?php if ($isMaintenanceActive): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            <?= $isMaintenanceActive ? 'Mode Maintenance ACTIVÉ' : 'Site Accessible' ?>
                        </h2>
                        <p class="text-sm text-gray-500">
                            <?= $isMaintenanceActive ? 'Le site public est actuellement inaccessible' : 'Le site public est accessible aux utilisateurs' ?>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold <?= $isMaintenanceActive ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' ?>">
                        <?= $isMaintenanceActive ? '🔒 Bloqué' : '✅ En ligne' ?>
                    </span>
                </div>
            </div>

            <?php if ($isMaintenanceActive && $maintenanceInfo): ?>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Activé le :</span>
                            <span class="font-medium text-gray-900 ml-2"><?= date('d/m/Y à H:i', strtotime($maintenanceInfo['activated_at'])) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Par :</span>
                            <span class="font-medium text-gray-900 ml-2"><?= htmlspecialchars($maintenanceInfo['activated_by']) ?></span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-gray-500 text-sm">Message affiché :</span>
                        <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg"><?= htmlspecialchars($maintenanceInfo['message']) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <?php if (!$isMaintenanceActive): ?>
            <!-- Activer le mode maintenance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Activer le mode maintenance</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="activate">
                    
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message à afficher aux utilisateurs
                        </label>
                        <textarea id="message" name="message" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none resize-none"
                                  placeholder="Une maintenance est en cours. Le site sera de nouveau accessible prochainement.">Une maintenance est en cours. Le site sera de nouveau accessible prochainement.</textarea>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                        <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-orange-800 mb-1">Attention</p>
                                <p class="text-xs text-orange-700">L'activation du mode maintenance bloquera l'accès au site pour tous les utilisateurs. Seuls les administrateurs pourront accéder au back-office.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all shadow-lg">
                        🔒 Activer le mode maintenance
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Désactiver le mode maintenance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Désactiver le mode maintenance</h3>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <div class="flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-green-700">La désactivation rendra le site immédiatement accessible à tous les utilisateurs.</p>
                        </div>
                    </div>
                </div>

                <form method="POST">
                    <input type="hidden" name="action" value="deactivate">
                    <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all shadow-lg">
                        ✅ Désactiver le mode maintenance
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Lien vers la page de maintenance -->
        <?php if ($isMaintenanceActive): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <p class="text-sm text-blue-700 mb-2">Prévisualiser la page de maintenance :</p>
                <a href="../maintenance_page.php" target="_blank" class="text-blue-600 hover:text-blue-700 font-medium text-sm underline">
                    Voir la page de maintenance →
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>