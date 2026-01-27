<?php
/**
 * Admin Management - List all administrators
 * Only accessible by superadmins
 */
require_once 'includes/admin_auth.php';
require_once '../includes/db.php';
require_once '../includes/queries.php';

// Require superadmin access
requireSuperAdmin();

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $currentAdmin = getCurrentAdmin();
    
    // Prevent self-deletion
    if ($deleteId === $currentAdmin['id']) {
        $error = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        try {
            deleteAdmin($deleteId);
            $success = "Administrateur supprimé avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de la suppression: " . $e->getMessage();
        }
    }
}

// Fetch all admins with their roles via centralized function
$admins = getAllAdmins();

$currentAdmin = getCurrentAdmin();

include 'includes/admin_header.php';
?>

<script>
    document.getElementById('page-title').textContent = 'Gestion des Administrateurs';
</script>

<?php if (isset($success)): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- Header Actions -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <p class="text-gray-500">Gérez les comptes administrateurs de la plateforme</p>
    </div>
    <a href="admin_create.php" class="inline-flex items-center gap-2 bg-canope-dark text-white px-5 py-2.5 rounded-xl font-medium hover:bg-canope-slate transition-colors shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Nouvel Admin
    </a>
</div>

<!-- Admins Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Administrateur</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Poste</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($admins as $admin): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-canope-dark/10 rounded-full flex items-center justify-center">
                                <span class="text-canope-dark font-semibold text-sm">
                                    <?= strtoupper(substr($admin['first_name'], 0, 1) . substr($admin['last_name'], 0, 1)) ?>
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></p>
                                <p class="text-xs text-gray-400">ID: <?= $admin['id'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($admin['email_pro']) ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($admin['job_title'] ?? '-') ?></td>
                    <td class="px-6 py-4">
                        <?php if ($admin['role_id'] == 2): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            Superadmin
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Admin
                        </span>
                        <?php endif; ?>
                    </td>
                    
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Bouton Modifier -->
                            <a href="admin_edit.php?id=<?= $admin['id'] ?>" 
                            class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded-lg transition-colors" 
                            title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a> 
                        <?php if ($admin['id'] !== $currentAdmin['id']): ?>
                        <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">
                            <input type="hidden" name="delete_id" value="<?= $admin['id'] ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-gray-300 p-2 inline-block" title="Vous ne pouvez pas supprimer votre propre compte">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
