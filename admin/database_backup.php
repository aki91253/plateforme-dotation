<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

requireAdmin();

$error = '';
$success = '';

// Traitement de l'import
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
    if ($_FILES['sql_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['sql_file'];
        
        // Vérifier l'extension
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($extension !== 'sql') {
            $error = 'Le fichier doit être au format .sql';
        } else {
            try {
                // Lire le contenu du fichier SQL
                $sqlContent = file_get_contents($file['tmp_name']);
                
                // Créer une connexion avec root pour avoir tous les privilèges
                $importPdo = new PDO('mysql:host=localhost;dbname=canope-reseau;charset=utf8mb4', 'root', 'root');
                $importPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Désactiver les clés étrangères
                $importPdo->exec('SET FOREIGN_KEY_CHECKS = 0');
                $importPdo->exec('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
                
                // Supprimer les commentaires et diviser en requêtes
                $sqlContent = preg_replace('/^--.*$/m', '', $sqlContent); // Supprimer commentaires --
                $sqlContent = preg_replace('/\/\*.*?\*\//s', '', $sqlContent); // Supprimer commentaires /* */
                
                // Diviser par point-virgule (mais pas ceux entre quotes)
                $queries = [];
                $currentQuery = '';
                $inString = false;
                $stringChar = '';
                
                for ($i = 0; $i < strlen($sqlContent); $i++) {
                    $char = $sqlContent[$i];
                    
                    // Gérer les chaînes de caractères
                    if (($char === '"' || $char === "'") && ($i === 0 || $sqlContent[$i-1] !== '\\')) {
                        if (!$inString) {
                            $inString = true;
                            $stringChar = $char;
                        } elseif ($char === $stringChar) {
                            $inString = false;
                        }
                    }
                    
                    // Si on trouve un ; hors d'une chaîne
                    if ($char === ';' && !$inString) {
                        $currentQuery .= $char;
                        $queries[] = trim($currentQuery);
                        $currentQuery = '';
                    } else {
                        $currentQuery .= $char;
                    }
                }
                
                // Ajouter la dernière requête si elle existe
                if (trim($currentQuery) !== '') {
                    $queries[] = trim($currentQuery);
                }
                
                // Exécuter chaque requête
                $executed = 0;
                $errors = [];
                
                foreach ($queries as $query) {
                    $query = trim($query);
                    
                    // Ignorer les requêtes vides
                    if (empty($query) || $query === ';') {
                        continue;
                    }
                    
                    try {
                        $importPdo->exec($query);
                        $executed++;
                    } catch (PDOException $e) {
                        // Ignorer certaines erreurs non critiques
                        $errorMsg = $e->getMessage();
                        
                        // Ignorer "table doesn't exist" lors des DROP
                        if (strpos($errorMsg, "doesn't exist") !== false) {
                            continue;
                        }
                        
                        // Ignorer "table already exists"
                        if (strpos($errorMsg, "already exists") !== false) {
                            continue;
                        }
                        
                        // Ignorer "Duplicate entry"
                        if (strpos($errorMsg, "Duplicate entry") !== false) {
                            continue;
                        }
                        
                        // Sinon, enregistrer l'erreur
                        $errors[] = "Erreur sur requête : " . substr($query, 0, 100) . "... → " . $errorMsg;
                    }
                }
                
                // Réactiver les clés étrangères
                $importPdo->exec('SET FOREIGN_KEY_CHECKS = 1');
                
                if (empty($errors)) {
                    $success = "Base de données importée avec succès ! ($executed requêtes exécutées)";
                } else {
                    $success = "Import terminé avec $executed requêtes exécutées. " . count($errors) . " erreurs ignorées.";
                    // Optionnel : afficher les erreurs pour debug
                    // $error = implode("<br>", array_slice($errors, 0, 5)); // Afficher les 5 premières erreurs
                }
                
            } catch (PDOException $e) {
                $error = 'Erreur lors de l\'import : ' . $e->getMessage();
            } catch (Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
            }
        }
    } else {
        $error = 'Erreur lors de l\'upload du fichier.';
    }
}
// Statistiques de la base de données
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $totalTables = count($tables);
    
    $totalRows = 0;
    $tableStats = [];
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        $totalRows += $count;
        $tableStats[$table] = $count;
    }
    
    // Taille de la base de données
    $dbName = 'canope-reseau';
    $sizeQuery = $pdo->query("
        SELECT SUM(data_length + index_length) / 1024 / 1024 AS size_mb 
        FROM information_schema.TABLES 
        WHERE table_schema = '$dbName'
    ");
    $dbSize = round($sizeQuery->fetchColumn(), 2);
    
} catch (PDOException $e) {
    $error = 'Erreur lors de la récupération des statistiques : ' . $e->getMessage();
}

include 'includes/admin_header.php';
?>

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center gap-4 mb-6">
            <a href="index.php" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sauvegarde de la base de données</h1>
                <p class="text-gray-500 text-sm">Export et import de la base de données</p>
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

        <!-- Statistiques de la BDD -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900"><?= $totalTables ?></p>
                        <p class="text-gray-500 text-sm mt-1">Tables</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900"><?= number_format($totalRows) ?></p>
                        <p class="text-gray-500 text-sm mt-1">Enregistrements</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900"><?= $dbSize ?> MB</p>
                        <p class="text-gray-500 text-sm mt-1">Taille totale</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Export -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold">Exporter la base de données</h2>
                            <p class="text-blue-100 text-sm">Télécharger un fichier SQL</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Créez une sauvegarde complète de votre base de données au format SQL.</p>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Le fichier contiendra :</p>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Structure des tables
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Toutes les données
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Relations et contraintes
                            </li>
                        </ul>
                    </div>

                    <a href="export_database.php" class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg text-center">
                        <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Télécharger la sauvegarde
                        </span>
                    </a>
                </div>
            </div>

            <!-- Import -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold">Importer une base de données</h2>
                            <p class="text-orange-100 text-sm">Restaurer depuis un fichier SQL</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-red-800 mb-1">⚠️ Attention</p>
                                <p class="text-xs text-red-700">Cette action remplacera toutes les données actuelles. Créez une sauvegarde avant d'importer.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-500 transition-colors cursor-pointer mb-4" onclick="document.getElementById('sql_file').click()">
                            <input type="file" id="sql_file" name="sql_file" accept=".sql" class="hidden" onchange="updateFileName(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 font-medium mb-1">Cliquez pour sélectionner un fichier SQL</p>
                            <p id="file-name" class="text-xs text-gray-500">Aucun fichier sélectionné</p>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Importer et restaurer
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Détails des tables -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Détails des tables</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Table</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Enregistrements</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($tableStats as $table => $count): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-800 font-mono"><?= htmlspecialchars($table) ?></td>
                                <td class="px-6 py-3 text-sm text-gray-600 text-right"><?= number_format($count) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || 'Aucun fichier sélectionné';
    document.getElementById('file-name').textContent = fileName;
}
</script>

<?php include 'includes/admin_footer.php'; ?>