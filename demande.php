<?php
require_once 'includes/db.php';
require_once 'includes/queries.php';

$demande = null;
$erreur = '';
$searched = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['token'])) {
    $searched = true;
    $searchToken = isset($_POST['search_token']) ? trim($_POST['search_token']) : (isset($_GET['token']) ? trim($_GET['token']) : '');
    
    if (!empty($searchToken)) {
        try {
            $demande = getRequestByToken($searchToken);
            
            if (!$demande) {
                $erreur = "Demande non trouvée. Vérifiez votre token.";
            }
        } catch (Exception $e) {
            $erreur = $e->getMessage();
        }
    } else {
        $erreur = "Veuillez entrer un token.";
    }
}

$historique = [];
if ($demande) {
    $historique = getRequestStatusHistory($demande['token']);
    
    $produits = getRequestProductsByRequestId($demande['id']);
    $demande['produits'] = $produits;
}

include 'includes/header.php';
?>

<div class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg stroke="#FFFFFF" class="w-16 h-16 mx-auto ml-1.5 -mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"">
							<path d="M18.125,15.804l-4.038-4.037c0.675-1.079,1.012-2.308,1.01-3.534C15.089,4.62,12.199,1.75,8.584,1.75C4.815,1.75,1.982,4.726,2,8.286c0.021,3.577,2.908,6.549,6.578,6.549c1.241,0,2.417-0.347,3.44-0.985l4.032,4.026c0.167,0.166,0.43,0.166,0.596,0l1.479-1.478C18.292,16.234,18.292,15.968,18.125,15.804 M8.578,13.99c-3.198,0-5.716-2.593-5.733-5.71c-0.017-3.084,2.438-5.686,5.74-5.686c3.197,0,5.625,2.493,5.64,5.624C14.242,11.548,11.621,13.99,8.578,13.99 M16.349,16.981l-3.637-3.635c0.131-0.11,0.721-0.695,0.876-0.884l3.642,3.639L16.349,16.981z"></path>
						</svg>
            </div>
            <h1 class="text-3xl font-semibold text-white">Suivre ma demande</h1>
        </div>
        <p class="text-white/80 text-sm ml-13">Entrez votre token reçu par mail dans la barre de recherche pour suivre votre demande </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-5 py-12"> 
    <!-- Formulaire de recherche -->
    <div class="mb-12">
        <form method="POST" class="flex gap-3">
            <input 
                type="text" 
                name="search_token" 
                value="<?php echo htmlspecialchars($_POST['search_token'] ?? $_GET['token'] ?? ''); ?>"
                placeholder="Entrez votre token de demande"
                class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-canope-green"
            />
            <!-- From Uiverse.io by Javierrocadev --> 
                <button onclick="openEmailModal()"
                class="bg-blue-900 text-white px-4 py-2 rounded-full transition duration-200 ease-in-out hover:bg-canope-slate active:bg-canope-dark focus:outline-none h-13 w-60  p-2 flex justify-center items-center"
                >
            
                <span class="z-10">Rechercher</span>
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
                        <svg fill="#3B556D" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_nom'] ?? 'Non spécifié'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg fill="#3B556D" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_email'] ?? 'Non spécifié'); ?></span>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center gap-2 text-gray-600 mb-3">
                        <svg fill="#3B556D" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
							<path d="M13.372,1.781H6.628c-0.696,0-1.265,0.569-1.265,1.265v13.91c0,0.695,0.569,1.265,1.265,1.265h6.744c0.695,0,1.265-0.569,1.265-1.265V3.045C14.637,2.35,14.067,1.781,13.372,1.781 M13.794,16.955c0,0.228-0.194,0.421-0.422,0.421H6.628c-0.228,0-0.421-0.193-0.421-0.421v-0.843h7.587V16.955z M13.794,15.269H6.207V4.731h7.587V15.269z M13.794,3.888H6.207V3.045c0-0.228,0.194-0.421,0.421-0.421h6.744c0.228,0,0.422,0.194,0.422,0.421V3.888z"></path>
						</svg>
                        <span><?php echo htmlspecialchars($demande['demandeur_phone'] ?? 'Non spécifié'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg fill="#3B556D" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
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
                        <span fill="#3B556D" class="text-lg font-semibold ">x<?php echo (int)$demande['quantity']; ?></span>
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
                                <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M10.25,2.375c-4.212,0-7.625,3.413-7.625,7.625s3.413,7.625,7.625,7.625s7.625-3.413,7.625-7.625S14.462,2.375,10.25,2.375M10.651,16.811v-0.403c0-0.221-0.181-0.401-0.401-0.401s-0.401,0.181-0.401,0.401v0.403c-3.443-0.201-6.208-2.966-6.409-6.409h0.404c0.22,0,0.401-0.181,0.401-0.401S4.063,9.599,3.843,9.599H3.439C3.64,6.155,6.405,3.391,9.849,3.19v0.403c0,0.22,0.181,0.401,0.401,0.401s0.401-0.181,0.401-0.401V3.19c3.443,0.201,6.208,2.965,6.409,6.409h-0.404c-0.22,0-0.4,0.181-0.4,0.401s0.181,0.401,0.4,0.401h0.404C16.859,13.845,14.095,16.609,10.651,16.811 M12.662,12.412c-0.156,0.156-0.409,0.159-0.568,0l-2.127-2.129C9.986,10.302,9.849,10.192,9.849,10V5.184c0-0.221,0.181-0.401,0.401-0.401s0.401,0.181,0.401,0.401v4.651l2.011,2.008C12.818,12.001,12.818,12.256,12.662,12.412"></path>
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
                                    echo $statusColor[$event['libelle']] ?? 'bg-gray-100 text-gray-700';
                                ?>
                            ">
                                <?php echo $statuts[$event['libelle']] ?? htmlspecialchars($event['libelle']); ?>
                            </span>
                            <?php if (!empty($event['notes'])): ?>
                                <p class="text-gray-700 mt-2"><?php echo htmlspecialchars($event['notes']); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo date('d F Y à H:i', strtotime($event['changed_at'])); ?>
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
