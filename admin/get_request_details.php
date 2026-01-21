<?php
/**
 * Get Request Details (AJAX endpoint)
 * Returns HTML for request detail modal
 */
require_once '../includes/db.php';
require_once '../includes/queries.php';
require_once 'includes/admin_auth.php';

if (!isAdminLoggedIn()) {
    echo '<p class="text-red-500">Non autorisé</p>';
    exit;
}

$requestId = (int)($_GET['id'] ?? 0);
if ($requestId <= 0) {
    echo '<p class="text-gray-500">Demande non trouvée</p>';
    exit;
}

try {
    // Get request via centralized function
    $request = getRequestById($requestId);
    
    if (!$request) {
        echo '<p class="text-gray-500">Demande non trouvée</p>';
        exit;
    }
    
    // Get request lines via centralized function
    $lines = getRequestLines($requestId);
    
} catch (PDOException $e) {
    echo '<p class="text-red-500">Erreur de connexion</p>';
    exit;
}

$statusLabels = ['EN_COURS' => 'En cours', 'TRAITEE' => 'Traitée'];
$statusColors = ['EN_COURS' => 'bg-amber-100 text-amber-700', 'TRAITEE' => 'bg-emerald-100 text-emerald-700'];
?>

<div class="space-y-6">
    <!-- Request Info -->
    <div class="flex items-start justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($request['token']) ?></h4>
            <p class="text-gray-500"><?= date('d/m/Y', strtotime($request['request_date'])) ?></p>
        </div>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium <?= $statusColors[$request['status']] ?? 'bg-gray-100 text-gray-700' ?>">
            <?= $statusLabels[$request['status']] ?? $request['status'] ?>
        </span>
    </div>
    
    <!-- Contact & Establishment -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-50 rounded-xl p-4">
            <h5 class="text-sm font-semibold text-gray-500 uppercase mb-2">Contact</h5>
            <p class="font-medium text-gray-800"><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></p>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($request['email']) ?></p>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($request['phone'] ?? 'Non renseigné') ?></p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <h5 class="text-sm font-semibold text-gray-500 uppercase mb-2">Établissement</h5>
            <p class="font-medium text-gray-800"><?= htmlspecialchars($request['establishment_name']) ?></p>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($request['establishment_address'] ?? '') ?></p>
            <p class="text-sm text-gray-600"><?= htmlspecialchars(($request['establishment_postal'] ?? '') . ' ' . ($request['establishment_city'] ?? '')) ?></p>
        </div>
    </div>
    
    <!-- Request Lines -->
    <div>
        <h5 class="text-sm font-semibold text-gray-500 uppercase mb-3">Articles demandés</h5>
        <div class="bg-gray-50 rounded-xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-4 py-2 text-xs font-semibold text-gray-500">Produit</th>
                        <th class="text-center px-4 py-2 text-xs font-semibold text-gray-500">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lines as $line): ?>
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($line['product_name']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($line['reference'] ?? '') ?></p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-canope-green/10 text-canope-green">
                                <?= $line['quantity'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if (!empty($request['comment'])): ?>
    <!-- Comment -->
    <div>
        <h5 class="text-sm font-semibold text-gray-500 uppercase mb-2">Commentaire</h5>
        <p class="text-gray-700 bg-gray-50 rounded-xl p-4"><?= nl2br(htmlspecialchars($request['comment'])) ?></p>
    </div>
    <?php endif; ?>
    
    <!-- Assigned To -->
    <div class="pt-4 border-t border-gray-100 flex items-center gap-2 text-sm text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Responsable: <span class="font-medium text-gray-700"><?= htmlspecialchars($request['responsible_name'] ?? 'Non assigné') ?></span>
    </div>
</div>
