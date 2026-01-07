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
                    <svg xmlns="http://www.w3.org/2000/svg" width="26.463" height="26.647" viewBox="0 0 26.463 26.647">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-normal text-gray-900">Votre avis compte !</h1>
                <p class="text-gray-500 text-sm mt-2">Partagez nous votre expérience.</p>
            </div>


            <!-- Login Form -->
            <form action="login.php" method="POST" class="space-y-5">
                <!-- Nom de la personne qui laisse l'avis -->
                <div>
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                             <svg xmlns="http://www.w3.org/2000/svg" width="26.463" height="26.647" viewBox="0 0 26.463 26.647">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                        </span>
                        <input type="text" id="name" id="name" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800 placeholder-gray-400"
                               placeholder="Votre nom ">
                    </div>
                </div>

                <!-- Commentaire -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                    <div class="relative">
                         <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="text" id="comment" name="comment" required
                               class="w-full pl-12 pr-12 py-3 rounded-xl border border-gray-200 focus:border-canope-green focus:ring-2 focus:ring-canope-green/20 outline-none transition-all text-gray-800 placeholder-gray-400"
                               placeholder="C'était comment ?">
                    </div>
                </div>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="px-4 text-sm text-gray-400"> Notez nous !</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <div class="rating flex flex-row-reverse justify-center gap-1 text-3xl">

  <input type="radio" id="star5" name="rate" value="5" class="peer hidden">
  <label for="star5"
         class="cursor-pointer text-gray-500
                peer-hover:text-orange-400
                peer-checked:text-orange-400">
    ★
  </label>

  <input type="radio" id="star4" name="rate" value="4" class="peer hidden">
  <label for="star4"
         class="cursor-pointer text-gray-500
                peer-hover:text-orange-400
                peer-checked:text-orange-400">
    ★
  </label>

  <input type="radio" id="star3" name="rate" value="3" class="peer hidden" checked>
  <label for="star3"
         class="cursor-pointer text-gray-500
                peer-hover:text-orange-400
                peer-checked:text-orange-400">
    ★
  </label>

  <input type="radio" id="star2" name="rate" value="2" class="peer hidden">
  <label for="star2"
         class="cursor-pointer text-gray-500
                peer-hover:text-orange-400
                peer-checked:text-orange-400">
    ★
  </label>

  <input type="radio" id="star1" name="rate" value="1" class="peer hidden">
  <label for="star1"
         class="cursor-pointer text-gray-500
                peer-hover:text-orange-400
                peer-checked:text-orange-400">
    ★
  </label>

</div>
                    <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-canope-green text-white py-3 px-6 rounded-xl font-semibold hover:bg-gradient-to-r hover:from-canope-green hover:to-[#4a8a70] transition-all duration-300 shadow-lg shadow-canope-green/25 flex items-center justify-center gap-2">
                    Envoyer
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
            

</head>


<?php include 'includes/footer.php'; ?>