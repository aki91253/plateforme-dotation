<?php
/**
 * Admin Management - Edit administrator
 * Only accessible by superadmins
 */
require_once 'includes/admin_auth.php';
require_once '../includes/db.php';
require_once '../includes/queries.php';

// Require superadmin access
requireSuperAdmin();

$currentAdmin = getCurrentAdmin();
$adminId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($adminId === 0) {
    header('Location: admins.php');
    exit;
}

// Fetch admin to edit
$admin = getAdminById($adminId);

if (!$admin) {
    header('Location: admins.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email_pro'] ?? '');
    $jobTitle = trim($_POST['job_title'] ?? '');
    $roleId = (int)($_POST['role_id'] ?? 1);
    $newPassword = trim($_POST['password'] ?? '');
    
    $errors = [];
    
    // Validation
    if (empty($firstName)) $errors[] = "Le prénom est requis.";
    if (empty($lastName)) $errors[] = "Le nom est requis.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email professionnel invalide.";
    }
    if (!in_array($roleId, [1, 2])) $errors[] = "Rôle invalide.";
    
    // Check if email is already used by another admin
    if ($email !== $admin['email_pro']) {
        $existingAdmin = getAdminByEmail($email);
        if ($existingAdmin) {
            $errors[] = "Cet email est déjà utilisé par un autre administrateur.";
        }
    }
    
    // Prevent removing own superadmin role
    if ($adminId === $currentAdmin['id'] && $currentAdmin['role_id'] == 2 && $roleId != 2) {
        $errors[] = "Vous ne pouvez pas retirer votre propre rôle de superadmin.";
    }
    
    if (empty($errors)) {
        try {
            // Update admin
            $stmt = $pdo->prepare("
                UPDATE responsible 
                SET first_name = ?, last_name = ?, email_pro = ?, job_title = ?, role_id = ?
                WHERE id = ?
            ");
            $stmt->execute([$firstName, $lastName, $email, $jobTitle, $roleId, $adminId]);
            
            // Update password if provided
            if (!empty($newPassword)) {
                if (strlen($newPassword) < 8) {
                    $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
                } else {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE responsible SET password = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, $adminId]);
                }
            }
            
            if (empty($errors)) {
                $_SESSION['success'] = "Administrateur modifié avec succès.";
                header('Location: admins.php');
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la modification: " . $e->getMessage();
        }
    }
}

include 'includes/admin_header.php';
?>

<script>
    document.getElementById('page-title').textContent = 'Modifier un Administrateur';
</script>

<!-- Back Button -->
<div class="mb-6">
    <a href="admins.php" class="inline-flex items-center gap-2 text-gray-600 hover:text-canope-dark transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Retour à la liste
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
    <ul class="list-disc list-inside">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- Edit Form -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
    <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
        <div class="w-12 h-12 bg-canope-dark/10 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-canope-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Modifier l'administrateur</h2>
            <p class="text-sm text-gray-500">ID: <?= $admin['id'] ?></p>
        </div>
    </div>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Prénom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Prénom <span class="text-red-500">*</span>
                </label>
                <input type="text" name="first_name" required
                       value="<?= htmlspecialchars($admin['first_name']) ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-dark focus:border-transparent transition-all">
            </div>

            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom <span class="text-red-500">*</span>
                </label>
                <input type="text" name="last_name" required
                       value="<?= htmlspecialchars($admin['last_name']) ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-dark focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Email professionnel <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email_pro" required
                   value="<?= htmlspecialchars($admin['email_pro']) ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-dark focus:border-transparent transition-all">
        </div>

        <!-- Poste -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Poste / Fonction
            </label>
            <input type="text" name="job_title"
                   value="<?= htmlspecialchars($admin['job_title'] ?? '') ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-dark focus:border-transparent transition-all"
                   placeholder="Ex: Responsable des expositions">
        </div>

        <!-- Rôle -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Rôle <span class="text-red-500">*</span>
            </label>
            <select name="role_id" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-dark focus:border-transparent transition-all"
                    <?= ($adminId === $currentAdmin['id'] && $currentAdmin['role_id'] == 2) ? 'disabled' : '' ?>>
                <option value="1" <?= $admin['role_id'] == 1 ? 'selected' : '' ?>>Administrateur</option>
                <option value="2" <?= $admin['role_id'] == 2 ? 'selected' : '' ?>>Superadministrateur</option>
            </select>
            <?php if ($adminId === $currentAdmin['id'] && $currentAdmin['role_id'] == 2): ?>
                <input type="hidden" name="role_id" value="2">
                <p class="mt-2 text-sm text-gray-500">Vous ne pouvez pas modifier votre propre rôle de superadmin.</p>
            <?php endif; ?>
        </div>

        <!-- Nouveau mot de passe -->
        <div class="border-t border-gray-100 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Changer le mot de passe</h3>
            <p class="text-sm text-gray-500 mb-4">Laissez vide pour conserver le mot de passe actuel</p>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nouveau mot de passe
                </label>
                <input type="password" name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-dark focus:border-transparent transition-all"
                       placeholder="Minimum 8 caractères">
                <p class="mt-2 text-xs text-gray-500">Minimum 8 caractères requis</p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100">
            <button type="submit"
                    class="flex-1 bg-canope-dark text-white px-6 py-3 rounded-xl font-medium hover:bg-canope-slate transition-colors shadow-lg">
                Enregistrer les modifications
            </button>
            <a href="admins.php"
               class="flex-1 text-center border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>