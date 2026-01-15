<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>
<!-- texte de bienvenue -->

<div class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-3 mb-2">

<div class="max-w-5xl mx-auto px-5 py-8">
    <section class="text-center py-12">
        <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-sm font-medium px-4 py-2 rounded-full border border-white/30">
  Réseau Canopé – Corse
</span>
        <h1 class="text-6xl font-normal mb-8 leading-tight text-canope-light">Dotations pédagogiques pour les établissements corses</h1>
        <p class="text-white/80 text-sm ml-13">Accédez à notre catalogue de ressources et équipements pédagogiques.</br> Faites votre demande en ligne et suivez son traitement en temps réel. </p>
</br>
        <!-- section des boutons -->
        <div class="flex items-center justify-center gap-4 mb-8">
            <!-- bouton 1 - gradient hover effect -->
            <div class="relative group">
                <a href="donations.php"
                    class="relative inline-block p-px font-semibold leading-6 text-white bg-canope-green cursor-pointer rounded-xl transition-transform duration-300 ease-in-out"
                >
                    <span
                        class="absolute inset-0 rounded-xl bg-gradient-to-r from-canope-green via-emerald-500 to-canope-olive p-[2px] opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                    ></span>
                    <span class="relative z-10 block px-6 py-3 rounded-xl bg-canope-slate">
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
                class="group/button relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-canope-teal/10 backdrop-blur-lg px-6 py-3 text-base font-semibold text-canope-green transition-all duration-300 ease-in-out border-2 border-canope-teal/40 hover:border-canope-teal"
            >
                <span class="text-canope-light">Faire une demande</span>
                <div
                    class="absolute inset-0 flex h-full w-full justify-center [transform:skew(-13deg)_translateX(-100%)] group-hover/button:duration-1000 group-hover/button:[transform:skew(-13deg)_translateX(100%)]"
                >
                    <div class="relative h-full w-10 bg-canope-teal/30"></div>
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
    <!-- Section Comment ça marche -->


    <!-- Titre et sous-titre -->
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-canope-light mb-4">Comment ça marche ?</h2>
      <p class="text-white/80 text-sm ml-13">Un processus simple et transparent pour accéder aux ressources Canopé</p>
    </div>

    <!-- Grille des 4 cartes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Carte 1 : Catalogue en ligne -->
      <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Catalogue en ligne</h3>
        <p class="text-gray-600">Consultez notre catalogue complet de dotations pédagogiques avec disponibilité en temps réel.</p>
      </div>

      <!-- Carte 2 : Demande simplifiée -->
      <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Demande simplifiée</h3>
        <p class="text-gray-600">Formulaire unique pour demander plusieurs dotations, sans création de compte.</p>
      </div>

      <!-- Carte 3 : Suivi en temps réel -->
      <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Suivi en temps réel</h3>
        <p class="text-gray-600">Suivez l'état de votre demande grâce à un lien sécurisé envoyé par email.</p>
      </div>

      <!-- Carte 4 : Données protégées -->
      <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Données protégées</h3>
        <p class="text-gray-600">Vos informations sont traitées conformément au RGPD.</p>
      </div>

    </div>
  </div>
</section>
</div>


<?php include 'includes/footer.php'; ?>
