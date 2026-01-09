<?php
require_once 'includes/db.php';

$demande = null;
$erreur = '';
$searched = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['id'])) {
    $searched = true;
    $searchId = isset($_POST['search_id']) ? trim($_POST['search_id']) : (isset($_GET['id']) ? trim($_GET['id']) : '');
    
    if (!empty($searchId)) {
        try {
            $query = $pdo->prepare("
                SELECT 
                    last_namename as demandeur_nom,
                    email as demandeur_email,
                    phone as demandeur_phone,
                    establishment_name as demandeur_institution
                FROM request
                WHERE id = :id OR token = :token
                LIMIT 1
            ");
            $query->execute(['id' => $searchId, 'token' => $searchId]);
            $demande = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!$demande) {
                $erreur = "Demande non trouvée. Vérifiez votre identifiant ou token.";
            }
        } catch (Exception $e) {
            $erreur = "Erreur lors de la recherche.";
        }
    } else {
        $erreur = "Veuillez entrer un identifiant ou un token.";
    }
}

$historique = [];
if ($demande) {
    $histQuery = $pdo->prepare("
        SELECT * FROM request 
        WHERE id = :id 
        ORDER BY request_date DESC
    ");
    $histQuery->execute(['request_id' => $demande['id']]);
    $historique = $histQuery->fetchAll(PDO::FETCH_ASSOC);
    
    $produits = [];
    $prodQuery = $pdo->prepare("
        SELECT 
            re.*,
            p.name as product_name,
            p.reference
        FROM request re
        LEFT JOIN product p ON re.product_id = p.id
        WHERE re.id = :id
    ");
    $prodQuery->execute(['request_id' => $demande['id']]);
    $produits = $prodQuery->fetchAll(PDO::FETCH_ASSOC);
    $demande['produits'] = $produits;
}

include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-5 py-12">
    <h1 class="text-4xl font-normal mb-12 text-gray-900">Suivre ma demande</h1>
    
    <!-- Formulaire de recherche -->
    <div class="mb-12">
        <form method="POST" class="flex gap-3">
            <input 
                type="text" 
                name="search_id" 
                value="<?php echo htmlspecialchars($_POST['search_id'] ?? $_GET['id'] ?? ''); ?>"
                placeholder="Entrez votre identifiant ou token de demande"
                class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-canope-green"
            />
            <button 
                type="submit"
                class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center gap-2"
            >
                Rechercher 
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H5"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Message d'erreur -->
    <?php if ($erreur): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8 text-red-700">
            <?php echo htmlspecialchars($erreur); ?>
        </div>
    <?php endif; ?>

    <!-- Résultats de la demande -->
    <?php if ($demande && $searched): ?>
        <!-- Statut actuel -->
        <div class="bg-amber-50 border-l-4 border-amber-400 p-6 mb-8 rounded-r-lg">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium text-gray-600">Statut actuel</span>
            </div>
            <div class="text-lg font-semibold text-amber-700">
                <?php 
                    $statuts = [
                        'pending' => 'En attente',
                        'verified' => 'Vérifiée',
                        'approved' => 'Approuvée',
                        'sent' => 'Envoyée',
                        'delivered' => 'Livrée',
                        'rejected' => 'Rejetée'
                    ];
                    echo $statuts[$demande['status']] ?? htmlspecialchars($demande['status']);
                ?>
            </div>
        </div>

        <!-- Demandeur -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Demandeur</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center gap-2 text-gray-600 mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_nom'] ?? 'Non spécifié'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_email'] ?? 'Non spécifié'); ?></span>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center gap-2 text-gray-600 mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.8c.17.995.348 1.374 1.53 2.556a1 1 0 001.415 0c1.182-1.182 1.36-1.561 1.53-2.556a1 1 0 01.986-.8h2.153a1 1 0 011 1v2.5a.5.5 0 01-.5.5H2.5a.5.5 0 01-.5-.5V3z"></path>
                            <path d="M14 4a1 1 0 011 1v2.5a.5.5 0 01-.5.5h-.5a.5.5 0 01-.5-.5V5a1 1 0 011-1h.5z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_phone'] ?? 'Non spécifié'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_institution'] ?? 'Non spécifié'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dotations demandées -->
        <?php if (!empty($demande['produits'])): ?>
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Dotations demandées</h3>
            
            <div class="space-y-4">
                <?php foreach ($demande['produits'] as $produit): ?>
                    <div class="flex justify-between items-center py-4 border-b border-gray-100 last:border-b-0">
                        <div>
                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($produit['product_name'] ?? 'Produit supprimé'); ?></p>
                            <?php if (!empty($produit['reference'])): ?>
                                <p class="text-sm text-gray-500">Réf: <?php echo htmlspecialchars($produit['reference']); ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="text-lg font-semibold text-canope-green">x<?php echo (int)$produit['quantity']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Historique -->
        <?php if (!empty($historique)): ?>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Historique</h3>
            
            <div class="space-y-6">
                <?php foreach ($historique as $event): ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2 4 4 0 00-4 4v10a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-4-4 1 1 0 000 2 2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 pt-1">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium mb-2
                                <?php 
                                    $statusColor = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'verified' => 'bg-blue-100 text-blue-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'sent' => 'bg-purple-100 text-purple-700',
                                        'delivered' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700'
                                    ];
                                    echo $statusColor[$event['status']] ?? 'bg-gray-100 text-gray-700';
                                ?>
                            ">
                                <?php echo $statuts[$event['status']] ?? htmlspecialchars($event['status']); ?>
                            </span>
                            <?php if (!empty($event['notes'])): ?>
                                <p class="text-gray-700 mt-2"><?php echo htmlspecialchars($event['notes']); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo date('d F Y à H:i', strtotime($event['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    <?php elseif (!$searched): ?>
        <!-- Message initial -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-12 text-center border border-blue-100">
            <svg class="w-16 h-16 mx-auto text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Entrez votre identifiant de demande</h2>
            <p class="text-gray-600">Vous trouverez votre identifiant ou token dans l'email de confirmation de votre demande.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
