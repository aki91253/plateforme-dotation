<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/security.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$currentUser = getCurrentUser();
$error = '';
$success = '';

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate all fields are filled
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'Veuillez remplir tous les champs.';
    } 
    // Check for SQL injection in all fields
    elseif (containsSqlInjectionChars($currentPassword)) {
        $error = getSqlInjectionErrorMessage('Mot de passe actuel');
    }
    elseif (containsSqlInjectionChars($newPassword)) {
        $error = getSqlInjectionErrorMessage('Nouveau mot de passe');
    }
    // Validate new password strength
    else {
        $passwordValidation = validatePassword($newPassword);
        if ($passwordValidation !== true) {
            $error = $passwordValidation;
        }
        // Check passwords match
        elseif ($newPassword !== $confirmPassword) {
            $error = 'Les nouveaux mots de passe ne correspondent pas.';
        }
        else {
            // Verify current password
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$currentUser['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $error = 'Le mot de passe actuel est incorrect.';
            } else {
                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                
                if ($stmt->execute([$hashedPassword, $currentUser['id']])) {
                    $success = 'Votre mot de passe a été modifié avec succès.';
                } else {
                    $error = 'Une erreur est survenue. Veuillez réessayer.';
                }
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="min-h-[70vh] py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-canope-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h1 class="text-3xl font-normal text-gray-900">Mon profil</h1>
            <p class="text-gray-500 mt-2">Gérez vos informations personnelles</p>
        </div>

        <!-- Account Information Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informations du compte
            </h2>
            
            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Adresse email</label>
                    <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-800"><?= htmlspecialchars($currentUser['email']) ?></span>
                    </div>
                </div>
                
                <!-- Établissement -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Établissement</label>
                    <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-gray-800"><?= htmlspecialchars($currentUser['etablissement']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Changer le mot de passe
            </h2>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>

            <form action="profile.php" method="POST" class="space-y-4">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800"
                               placeholder="Votre mot de passe actuel">
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </span>
                        <input type="password" id="new_password" name="new_password" required minlength="12"
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800"
                               placeholder="Minimum 12 caractères">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Majuscule, minuscule, chiffre et caractère spécial (!@^&()[]{}|:,.<>?/~) requis.</p>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="12"
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800"
                               placeholder="Répétez le nouveau mot de passe">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-canope-green text-white py-3 px-6 rounded-xl font-semibold hover:bg-gradient-to-r hover:from-canope-green hover:to-[#4a8a70] transition-all duration-300 shadow-lg shadow-canope-green/25 flex items-center justify-center gap-2 mt-6">
                    Changer le mot de passe
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="index.php" class="text-canope-green hover:underline text-sm">← Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
