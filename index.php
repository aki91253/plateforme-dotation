<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>
<!-- texte de bienvenue -->
<div class="max-w-5xl mx-auto px-5 py-8">
    <section class="text-center py-12">
        <h1 class="text-6xl font-normal mb-8 leading-tight text-gray-900">Bienvenue sur votre nouvel espace de demande​ de dotations</h1>
        
        <!-- section des boutons -->
        <div class="flex items-center justify-center gap-4 mb-8">
            <!-- bouton 1 - gradient hover effect -->
            <div class="relative group">
                <a href="donations.php"
                    class="relative inline-block p-px font-semibold leading-6 text-white bg-canope-green shadow-xl cursor-pointer rounded-xl shadow-canope-green/30 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95"
                >
                    <span
                        class="absolute inset-0 rounded-xl bg-gradient-to-r from-canope-green via-emerald-500 to-canope-olive p-[2px] opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                    ></span>

                    <span class="relative z-10 block px-6 py-3 rounded-xl bg-canope-green">
                        <div class="relative z-10 flex items-center space-x-2">
                            <span class="transition-all duration-500 group-hover:translate-x-1">Explorer le catalogue</span>
                            <svg
                                class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1"
                                data-slot="icon"
                                aria-hidden="true"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    clip-rule="evenodd"
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    fill-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                    </span>
                </a>
            </div>

            <!-- bouton 2 - shimmer effect -->
            <a href="selection.php"
                class="group/button relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-canope-green/20 backdrop-blur-lg px-6 py-3 text-base font-semibold text-canope-green transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl hover:shadow-canope-green/20 border-2 border-canope-green/40 hover:border-canope-green"
            >
                <span class="text-lg">Faire une demande</span>
                <div
                    class="absolute inset-0 flex h-full w-full justify-center [transform:skew(-13deg)_translateX(-100%)] group-hover/button:duration-1000 group-hover/button:[transform:skew(-13deg)_translateX(100%)]"
                >
                    <div class="relative h-full w-10 bg-canope-green/30"></div>
                </div>
            </a>
        </div>

        <!-- image de bienvenue -->
        <div class="flex justify-center mt-8">
            <div class="bg-gray-900 rounded-[40px] p-5 shadow-2xl max-w-4xl animate-slide-up">
                <div class="bg-white rounded-2xl overflow-hidden">
                    <img src="assets/img/hero-illustration.png" alt="Réseau Canopé Illustration" class="w-full block">
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
