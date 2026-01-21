<?php
/**
 * Admin Management - Create new administrator
 * Only accessible by superadmins
 */
require_once 'includes/admin_auth.php';
require_once '../includes/db.php';
require_once '../includes/security.php';

// Require superadmin access
requireSuperAdmin();

$error = '';
$success = '';

// Fetch available roles
$rolesStmt = $pdo->query("SELECT id, libelle FROM roles ORDER BY id");
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $jobTitle = trim($_POST['job_title'] ?? '');
    $roleId = (int)($_POST['role_id'] ?? 1);
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez entrer une adresse email valide.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (containsSqlInjectionChars($email) || containsSqlInjectionChars($firstName) || containsSqlInjectionChars($lastName)) {
        $error = 'Caractères non autorisés détectés.';
    } else {
        // Check if email already exists
        $checkStmt = $pdo->prepare("SELECT id FROM responsible WHERE email_pro = ?");
        $checkStmt->execute([$email]);
        
        if ($checkStmt->fetch()) {
            $error = 'Un administrateur avec cet email existe déjà.';
        } else {
            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO responsible (first_name, last_name, email_pro, password, job_title, role_id) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $jobTitle, $roleId]);
                
                $success = 'Administrateur créé avec succès !';
                // Clear form
                $firstName = $lastName = $email = $jobTitle = '';
            } catch (PDOException $e) {
                $error = 'Erreur lors de la création: ' . $e->getMessage();
            }
        }
    }
}

include 'includes/admin_header.php';
?>

<script>
    document.getElementById('page-title').textContent = 'Nouvel Administrateur';
</script>

<!-- Back Button -->
<a href="admins.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-canope-dark mb-6 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    Retour à la liste
</a>

<?php if ($success): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- Create Form -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <h2 class="text-lg font-semibold text-gray-800 mb-6">Créer un nouvel administrateur</h2>
    
    <form method="POST" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom <span class="text-red-500">*</span></label>
                <input type="text" id="first_name" name="first_name" required
                       value="<?= htmlspecialchars($firstName ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all"
                       placeholder="Prénom">
            </div>
            
            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom <span class="text-red-500">*</span></label>
                <input type="text" id="last_name" name="last_name" required
                       value="<?= htmlspecialchars($lastName ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all"
                       placeholder="Nom">
            </div>
        </div>
        
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email professionnel <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($email ?? '') ?>"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all"
                   placeholder="email@exemple.fr">
        </div>
        
        <!-- Job Title -->
        <div>
            <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">Poste</label>
            <input type="text" id="job_title" name="job_title"
                   value="<?= htmlspecialchars($jobTitle ?? '') ?>"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all"
                   placeholder="Ex: Chargé de dotations">
        </div>
        
        <!-- Role -->
        <div>
            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Rôle <span class="text-red-500">*</span></label>
            <select id="role_id" name="role_id" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all">
                <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>" <?= ($roleId ?? 1) == $role['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($role['libelle']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required minlength="6"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all"
                       placeholder="••••••••">
                <p class="text-xs text-gray-400 mt-1">Minimum 6 caractères</p>
            </div>
            
            <!-- Confirm Password -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-canope-teal focus:ring-2 focus:ring-canope-teal/20 outline-none transition-all"
                       placeholder="••••••••">
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full sm:w-auto bg-canope-dark text-white px-8 py-3 rounded-xl font-medium hover:bg-canope-slate transition-colors shadow-lg flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Créer l'administrateur
            </button>
        </div>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
