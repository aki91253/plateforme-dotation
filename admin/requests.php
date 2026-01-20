<?php
/**
 * Admin Dashboard - Gestion des demandes
 * Liste et gestion de toutes les demandes de dotation
 */
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

// Vérifier que l'utilisateur est admin
requireAdmin();

// Traitement de la mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $requestId = (int) $_POST['request_id'];
    $newStatus = (int) $_POST['new_status'];
    
    try {
        $updateStmt = $pdo->prepare("UPDATE request SET status_id = ? WHERE id = ?");
        $updateStmt->execute([$newStatus, $requestId]);
        header('Location: requests.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour du statut.";
    }
}

// Récupérer les statistiques
$statsQuery = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_id = 1 THEN 1 ELSE 0 END) as en_attente,
        SUM(CASE WHEN status_id = 2 THEN 1 ELSE 0 END) as verifiees,
        SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as approuvees,
        SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as envoyees,
        SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as livrees,
        SUM(CASE WHEN status_id = 6 THEN 1 ELSE 0 END) as refusees
    FROM request
");
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

// Récupérer les statuts disponibles
$statusQuery = $pdo->query("SELECT * FROM type_status ORDER BY id");
$statuses = $statusQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les filtres
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? intval($_GET['status']) : 0;

// Construire la requête
$query = "SELECT r.*, t.libelle as status_label, 
          CONCAT(resp.first_name, ' ', resp.last_name) as responsible_name,
          (SELECT COUNT(*) FROM request_line WHERE request_id = r.id) as items_count
          FROM request r 
          LEFT JOIN type_status t ON r.status_id = t.id
          LEFT JOIN responsible resp ON r.responsible_id = resp.id
          WHERE 1=1";

$params = [];

if (!empty($searchTerm)) {
    $query .= " AND (r.token LIKE :search OR r.establishment_name LIKE :search OR r.email LIKE :search OR r.last_name LIKE :search OR r.first_name LIKE :search)";
    $params['search'] = '%' . $searchTerm . '%';
}

if ($statusFilter > 0) {
    $query .= " AND r.status_id = :status";
    $params['status'] = $statusFilter;
}

$query .= " ORDER BY r.request_date DESC, r.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Couleurs et icônes pour les statuts
$statusColors = [
    1 => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
    2 => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
    3 => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'dot' => 'bg-indigo-500'],
    4 => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'dot' => 'bg-cyan-500'],
    5 => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
    6 => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500']
];

include 'includes/admin_header.php';
?>

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <button onclick="window.history.back()" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des demandes</h1>
                    <p class="text-gray-500 text-sm">Canopé Corse</p>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Statut mis à jour avec succès
        </div>
        <?php endif; ?>
        
        <!-- Notification pour email envoyé -->
        <div id="emailNotification" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl items-center gap-2 hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span id="emailNotificationText"></span>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-gray-500 text-xs mb-1">Total</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?></p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-amber-600 text-xs mb-1">En attente</p>
                <p class="text-2xl font-bold text-amber-600"><?= $stats['en_attente'] ?></p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-blue-600 text-xs mb-1">Vérifiées</p>
                <p class="text-2xl font-bold text-blue-600"><?= $stats['verifiees'] ?></p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-indigo-600 text-xs mb-1">Approuvées</p>
                <p class="text-2xl font-bold text-indigo-600"><?= $stats['approuvees'] ?></p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-cyan-600 text-xs mb-1">Envoyées</p>
                <p class="text-2xl font-bold text-cyan-600"><?= $stats['envoyees'] ?></p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-emerald-600 text-xs mb-1">Livrées</p>
                <p class="text-2xl font-bold text-emerald-600"><?= $stats['livrees'] ?></p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-red-600 text-xs mb-1">Refusées</p>
                <p class="text-2xl font-bold text-red-600"><?= $stats['refusees'] ?></p>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <!-- Recherche -->
                <div class="flex-1 min-w-[300px]">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" 
                               placeholder="Rechercher par token, établissement, email, nom..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                    </div>
                </div>

                <!-- Filtre statut -->
                <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                    <option value="0">Tous les statuts</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= $statusFilter == $status['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="px-6 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                    Filtrer
                </button>
                
                <?php if (!empty($searchTerm) || $statusFilter > 0): ?>
                <a href="requests.php" class="px-6 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Réinitialiser
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tableau des demandes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Token</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Demandeur</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Établissement</th>
                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-700">Articles</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Statut</th>
                        <th class="text-right px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Aucune demande trouvée
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                            <?php $colors = $statusColors[$request['status_id']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500']; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-medium text-gray-900"><?= htmlspecialchars($request['token']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900"><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($request['email']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-700"><?= htmlspecialchars($request['establishment_name']) ?></p>
                                    <?php if (!empty($request['establishment_city'])): ?>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($request['establishment_postal'] . ' ' . $request['establishment_city']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                        <?= $request['items_count'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= date('d/m/Y', strtotime($request['request_date'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 <?= $colors['bg'] ?> <?= $colors['text'] ?> text-xs font-medium rounded-full">
                                        <span class="w-1.5 h-1.5 <?= $colors['dot'] ?> rounded-full"></span>
                                        <?= htmlspecialchars($request['status_label']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Voir détails -->
                                        <button onclick="viewDetails(<?= $request['id'] ?>)" 
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-600 transition-colors"
                                                title="Voir les détails">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <!-- Changer statut -->
                                        <button onclick="openStatusModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['token']) ?>', <?= $request['status_id'] ?>)" 
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-amber-100 text-gray-600 hover:text-amber-600 transition-colors"
                                                title="Modifier le statut">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                        <!-- Envoyer token par email -->
                                        <button onclick="sendTokenEmail(<?= $request['id'] ?>, '<?= htmlspecialchars($request['email']) ?>')" 
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-600 transition-colors"
                                                title="Renvoyer le token par email">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de détails de la demande -->
<div id="detailsModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Détails de la demande</h3>
            <button onclick="closeDetailsModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="detailsContent" class="p-6 overflow-y-auto max-h-[calc(90vh-100px)]">
            <div class="flex items-center justify-center py-12">
                <svg class="animate-spin h-8 w-8 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Modal de changement de statut -->
<div id="statusModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Modifier le statut</h3>
        <p class="text-gray-600 text-center mb-6">
            Demande: <span id="statusRequestToken" class="font-mono font-semibold"></span>
        </p>
        
        <form method="POST" id="statusForm">
            <input type="hidden" name="update_status" value="1">
            <input type="hidden" name="request_id" id="statusRequestId">
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                <select name="new_status" id="statusSelect" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>"><?= htmlspecialchars($status['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeStatusModal()" 
                        class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal de détails
function viewDetails(requestId) {
    document.getElementById('detailsModal').classList.remove('hidden');
    document.getElementById('detailsModal').classList.add('flex');
    
    // Charger le contenu via AJAX
    fetch(`get_request_details.php?id=${requestId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('detailsContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('detailsContent').innerHTML = '<p class="text-red-500 text-center">Erreur lors du chargement</p>';
        });
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
    document.getElementById('detailsModal').classList.remove('flex');
    // Réinitialiser le contenu
    document.getElementById('detailsContent').innerHTML = `
        <div class="flex items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;
}

// Modal de statut
function openStatusModal(requestId, token, currentStatus) {
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
    document.getElementById('statusRequestId').value = requestId;
    document.getElementById('statusRequestToken').textContent = token;
    document.getElementById('statusSelect').value = currentStatus;
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('flex');
}

// Fermer modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailsModal();
        closeStatusModal();
    }
});

// Fermer modal en cliquant à l'extérieur
document.getElementById('detailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailsModal();
});
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});

// Fonction pour envoyer le token par email
function sendTokenEmail(requestId, email) {
    if (!confirm(`Êtes-vous sûr de vouloir envoyer le token à ${email} ?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('request_id', requestId);
    
    fetch('send_token_email.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const notification = document.getElementById('emailNotification');
        const notificationText = document.getElementById('emailNotificationText');
        
        if (data.success) {
            notification.classList.remove('hidden', 'bg-red-50', 'border-red-200', 'text-red-700');
            notification.classList.add('flex', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
            notificationText.textContent = data.message;
        } else {
            notification.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
            notification.classList.add('flex', 'bg-red-50', 'border-red-200', 'text-red-700');
            notificationText.textContent = data.message;
        }
        
        // Scroll vers le haut pour voir la notification
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Masquer après 5 secondes
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.classList.remove('flex');
        }, 5000);
    })
    .catch(error => {
        alert('Erreur lors de l\'envoi de l\'email');
        console.error(error);
    });
}
</script>

<?php include 'includes/admin_footer.php'; ?>
