<?php
/**
 * Admin - Gestion des demandes
 * Vue et gestion des demandes des utilisateurs
 */
require_once 'includes/admin_auth.php';
requireAdmin();

// connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=canope-reseau;charset=utf8mb4', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données');
}

$message = '';
$messageType = '';

// mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = (int)($_POST['request_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_status' && $requestId > 0) {
        $newStatusId = (int)($_POST['status_id'] ?? 0);
        $responsibleId = (int)($_POST['responsible_id'] ?? 0);
        
        if ($newStatusId >= 1 && $newStatusId <= 6) {
            try {
                $stmt = $pdo->prepare('UPDATE request SET status_id = ?, responsible_id = ? WHERE id = ?');
                $stmt->execute([$newStatusId, $responsibleId ?: null, $requestId]);
                $message = 'Statut mis à jour avec succès.';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Erreur lors de la mise à jour.';
                $messageType = 'error';
            }
        }
    }
}

// Get valeurs du filtre
$statusFilter = $_GET['status'] ?? '';
$searchQuery = $_GET['search'] ?? '';

// construction de la requête
$whereConditions = [];
$params = [];

if ($statusFilter) {
    $whereConditions[] = 'r.status_id = ?';
    $params[] = (int)$statusFilter;
}

if ($searchQuery) {
    $whereConditions[] = '(r.request_number LIKE ? OR r.establishment_name LIKE ? OR r.last_name LIKE ? OR r.first_name LIKE ?)';
    $searchTerm = "%$searchQuery%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

$whereClause = $whereConditions ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// récupération des demandes
$query = "
    SELECT r.*, t.libelle as status_label,
           CONCAT(resp.first_name, ' ', resp.last_name) as responsible_name,
           (SELECT COUNT(*) FROM request_line rl WHERE rl.request_id = r.id) as item_count
    FROM request r 
    LEFT JOIN responsible resp ON resp.id = r.responsible_id
    LEFT JOIN type_status t ON t.id = r.status_id
    $whereClause
    ORDER BY r.request_date DESC, r.id DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// récupération des responsables
$responsibles = $pdo->query('SELECT id, first_name, last_name, job_title FROM responsible ORDER BY last_name')->fetchAll(PDO::FETCH_ASSOC);

// récupération des statistiques
$totalRequests = $pdo->query('SELECT COUNT(*) FROM request')->fetchColumn();
$pendingRequests = $pdo->query("SELECT COUNT(*) FROM request WHERE status_id = 1")->fetchColumn();
$completedRequests = $pdo->query("SELECT COUNT(*) FROM request WHERE status_id >= 4")->fetchColumn();

include 'includes/admin_header.php';
?>

<!-- Header de la page -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Gestion des demandes</h2>
        <p class="text-gray-500 mt-1">Traitez et suivez les demandes de dotation</p>
    </div>
</div>
<!-- Message de confirmation -->
<?php if ($message): ?>
<div class="mb-6 px-4 py-3 rounded-xl flex items-center gap-3 <?= $messageType === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <?php if ($messageType === 'success'): ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <?php else: ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <?php endif; ?>
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<!-- Cartes de statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $totalRequests ?></p>
            <p class="text-sm text-gray-500">Total demandes</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $pendingRequests ?></p>
            <p class="text-sm text-gray-500">En cours</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $completedRequests ?></p>
            <p class="text-sm text-gray-500">Traitées</p>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" 
                       placeholder="Rechercher par n° demande, établissement, nom..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
            </div>
        </div>
        <div class="flex gap-2">
            <select name="status" class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
                <option value="">Tous les statuts</option>
                <option value="1" <?= $statusFilter === '1' ? 'selected' : '' ?>>En attente</option>
                <option value="2" <?= $statusFilter === '2' ? 'selected' : '' ?>>Vérifiée</option>
                <option value="3" <?= $statusFilter === '3' ? 'selected' : '' ?>>Approuvée</option>
                <option value="4" <?= $statusFilter === '4' ? 'selected' : '' ?>>Envoyée</option>
                <option value="5" <?= $statusFilter === '5' ? 'selected' : '' ?>>Livrée</option>
                <option value="6" <?= $statusFilter === '6' ? 'selected' : '' ?>>Refusée</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-canope-green hover:bg-canope-olive text-white rounded-lg transition-colors">
                Filtrer
            </button>
            <?php if ($statusFilter || $searchQuery): ?>
            <a href="requests.php" class="px-4 py-2.5 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Réinitialiser
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tableau des demandes -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Demande</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Établissement</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Articles</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Responsable</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
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
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($request['token']) ?></p>
                            <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($request['request_date'])) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm text-gray-800"><?= htmlspecialchars($request['establishment_name']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($request['establishment_city'] ?? '') ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm text-gray-800"><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($request['email']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            <?= $request['item_count'] ?> article<?= $request['item_count'] > 1 ? 's' : '' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $statusColors = [
                            1 => 'bg-amber-100 text-amber-700',
                            2 => 'bg-blue-100 text-blue-700',
                            3 => 'bg-indigo-100 text-indigo-700',
                            4 => 'bg-cyan-100 text-cyan-700',
                            5 => 'bg-emerald-100 text-emerald-700',
                            6 => 'bg-red-100 text-red-700'
                        ];
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusColors[$request['status_id']] ?? 'bg-gray-100 text-gray-700' ?>">
                            <?= htmlspecialchars($request['status_label'] ?? 'Inconnu') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600"><?= htmlspecialchars($request['responsible_name'] ?? 'Non assigné') ?></span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openDetailModal(<?= $request['id'] ?>)" 
                                    class="p-2 text-gray-500 hover:text-canope-green hover:bg-canope-green/10 rounded-lg transition-colors"
                                    title="Voir détails">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button onclick="openStatusModal(<?= htmlspecialchars(json_encode($request)) ?>)" 
                                    class="p-2 text-gray-500 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Modifier statut">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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

<!-- Modal de mise à jour du statut -->
<div id="statusModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Modifier le statut</h3>
            <button onclick="closeStatusModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="request_id" id="status_request_id">
            
            <div class="text-center mb-4">
                <p class="text-sm text-gray-500">Demande</p>
                <p class="text-lg font-semibold text-gray-800" id="status_request_number"></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status_id" id="status_select" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
                    <option value="1">En attente</option>
                    <option value="2">Vérifiée</option>
                    <option value="3">Approuvée</option>
                    <option value="4">Envoyée</option>
                    <option value="5">Livrée</option>
                    <option value="6">Refusée</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Responsable assigné</label>
                <select name="responsible_id" id="status_responsible_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-canope-green focus:border-transparent">
                    <option value="">Non assigné</option>
                    <?php foreach ($responsibles as $resp): ?>
                    <option value="<?= $resp['id'] ?>"><?= htmlspecialchars($resp['first_name'] . ' ' . $resp['last_name']) ?> - <?= htmlspecialchars($resp['job_title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeStatusModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-canope-green text-white rounded-lg hover:bg-canope-olive transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal des détails de la demande -->
<div id="detailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Détails de la demande</h3>
            <button onclick="closeDetailModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6" id="detailContent">
            <div class="flex items-center justify-center py-8">
                <svg class="animate-spin h-8 w-8 text-canope-green" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
    function openStatusModal(request) {
        document.getElementById('status_request_id').value = request.id;
        document.getElementById('status_request_number').textContent = request.token;
        document.getElementById('status_select').value = request.status_id;
        document.getElementById('status_responsible_id').value = request.responsible_id || '';
        
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('statusModal').classList.add('flex');
    }
    
    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
        document.getElementById('statusModal').classList.remove('flex');
    }
    
    function openDetailModal(requestId) {
        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
        
        // Load request details via AJAX (simplified - just show stored data)
        fetch(`get_request_details.php?id=${requestId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('detailContent').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('detailContent').innerHTML = '<p class="text-center text-gray-500">Erreur lors du chargement des détails.</p>';
            });
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }
    
    // Close modals on outside click
    ['statusModal', 'detailModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
            }
        });
    });
</script>

<?php include 'includes/admin_footer.php'; ?>
