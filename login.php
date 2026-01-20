<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/security.php';
require_once 'admin/includes/admin_auth.php';

// Redirection si déjà connecté
if (isLoggedIn()) {
    redirect('index.php');
}
if (isAdminLoggedIn()) {
    redirect('admin/index.php');
}

$error = '';

// Gestion de la validation du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (containsSqlInjectionChars($email)) {
        $error = getSqlInjectionErrorMessage('Email');
    } elseif (containsSqlInjectionChars($password)) {
        $error = getSqlInjectionErrorMessage('Mot de passe');
    } else {
        // Vérification si c'est un admin (table responsible)
        $stmt = $pdo->prepare("SELECT id, email_pro, password, first_name, last_name, job_title FROM responsible WHERE email_pro = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && !empty($admin['password']) && password_verify($password, $admin['password'])) {
            // Connexion admin réussie
            loginAdmin(
                $admin['id'],
                $admin['email_pro'],
                $admin['first_name'],
                $admin['last_name'],
                $admin['job_title']
            );
            redirect('admin/index.php');
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}

include 'includes/header.php';
?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- carte de connexion -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-canope-dark/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-canope-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-normal text-gray-900">Espace Administration</h1>
                <p class="text-gray-500 text-sm mt-2">Connectez-vous pour gérer les dotations</p>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form action="login.php" method="POST" class="space-y-5">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800 placeholder-gray-400"
                               placeholder="votre.email@exemple.fr">
                    </div>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-12 pr-12 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800 placeholder-gray-400"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-canope-green transition-colors">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mot de passe oublié -->
                <div class="text-right">
                    <a href="#" class="text-sm text-canope-green hover:underline">Mot de passe oublié ?</a>
                </div>

                <!-- Bouton de validation -->
                <button type="submit"
                        class="w-full bg-canope-dark text-white py-3 px-6 rounded-xl font-semibold hover:bg-gradient-to-r hover:from-canope-green hover:to-[#4a8a70] transition-all duration-300 shadow-lg shadow-canope-green/25 flex items-center justify-center gap-2">
                    Se connecter
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Séparateur 
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="px-4 text-sm text-gray-400">ou</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

             lien vers l'inscription 
            <p class="text-center text-gray-600">
                Pas encore de compte ? 
                <a href="register.php" class="text-canope-green font-semibold hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>
</div>-->
<!-- Script pour le bouton oeil (voire le mot de passe en claire) -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
</script>
<?php include 'includes/footer.php'; ?>
