<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<head>
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-canope-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-normal text-gray-900">Connexion</h1>
                <p class="text-gray-500 text-sm mt-2">Accédez à votre espace personnel</p>
            </div>


            <!-- Login Form -->
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

                <!-- Password -->
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

                <!-- Forgot Password Link -->
                <div class="text-right">
                    <a href="#" class="text-sm text-canope-green hover:underline">Mot de passe oublié ?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-canope-green text-white py-3 px-6 rounded-xl font-semibold hover:bg-gradient-to-r hover:from-canope-green hover:to-[#4a8a70] transition-all duration-300 shadow-lg shadow-canope-green/25 flex items-center justify-center gap-2">
                    Se connecter
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="px-4 text-sm text-gray-400">ou</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <!-- Sign Up Link -->
            <p class="text-center text-gray-600">
                Pas encore de compte ? 
                <a href="register.php" class="text-canope-green font-semibold hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>
</div>



</head>


<?php include 'includes/footer.php'; ?>