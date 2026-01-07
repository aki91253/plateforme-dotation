<?php
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-5 py-12">
    <!-- Hero Header -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-normal mb-4 text-gray-900">Plateforme de dotation</h1>
        <p class="text-2xl text-canope-green font-medium">Réseau Canopé</p>
        <p class="text-gray-400 mt-4">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
    </div>

    <!-- Table of Contents -->
    <nav id="sommaire" class="rounded-2xl p-6 mb-12">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">📋 Sommaire</h2>
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
            <li><a href="#article1" class="text-canope-green hover:underline">1. Préambule</a></li>
            <li><a href="#article2" class="text-canope-green hover:underline">2. Principes de collecte</a></li>
            <li><a href="#article3" class="text-canope-green hover:underline">3. Données collectées</a></li>
            <li><a href="#article4" class="text-canope-green hover:underline">4. Responsable du traitement</a></li>
            <li><a href="#article5" class="text-canope-green hover:underline">5. Droits de l'utilisateur</a></li>
            <li><a href="#article6" class="text-canope-green hover:underline">6. Mesures de sécurité</a></li>
            <li><a href="#article7" class="text-canope-green hover:underline">7. Modifications</a></li>
            <li><a href="#contact" class="text-canope-green hover:underline">Contact</a></li>
        </ul>
    </nav>

    <!-- Article 1 -->
    <section id="article1" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">1</span>
            <h2 class="text-2xl font-normal text-gray-900">Préambule</h2>
        </div>
        <div class="pl-11 text-gray-600 leading-relaxed space-y-4">
            <p>La présente politique de confidentialité a pour but d'informer les utilisateurs du site :</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Sur la manière dont sont collectées leurs données personnelles</li>
                <li>Sur les droits dont ils disposent concernant ces données</li>
                <li>Sur la personne responsable du traitement des données</li>
                <li>Sur les destinataires de ces données personnelles</li>
                <li>Sur la politique du site en matière de cookies</li>
            </ul>
        </div>
    </section>

    <!-- Article 2 -->
    <section id="article2" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">2</span>
            <h2 class="text-2xl font-normal text-gray-900">Principes relatifs à la collecte et au traitement</h2>
        </div>
        <div class="pl-11 text-gray-600 leading-relaxed space-y-4">
            <p>Conformément à l'article 5 du Règlement européen 2016/679, les données à caractère personnel sont :</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Traitées de manière licite, loyale et transparente</li>
                <li>Collectées pour des finalités déterminées, explicites et légitimes</li>
                <li>Adéquates, pertinentes et limitées à ce qui est strictement nécessaire</li>
                <li>Exactes et, si nécessaire, tenues à jour</li>
                <li>Conservées pendant une durée n'excédant pas celle nécessaire</li>
                <li>Traitées de façon à garantir une sécurité appropriée</li>
            </ul>
        </div>
    </section>

    <!-- Article 3 -->
    <section id="article3" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">3</span>
            <h2 class="text-2xl font-normal text-gray-900">Données à caractère personnel collectées</h2>
        </div>
        <div class="pl-11 space-y-6">
            <!-- Sub-sections as cards -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-800 mb-3">👤 Pour les usagers</h3>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li>• Nom et prénom</li>
                    <li>• Adresse email professionnelle</li>
                    <li>• Établissement d'affectation</li>
                    <li>• Fonction</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-800 mb-3">🛡️ Pour les administrateurs</h3>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li>• Nom et prénom</li>
                    <li>• Adresse email professionnelle</li>
                    <li>• Fonction</li>
                </ul>
            </div>

            <div class="bg-canope-light/50 rounded-xl p-5">
                <h3 class="font-semibold text-gray-800 mb-3">📊 Finalités du traitement</h3>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li>• Gestion des demandes de dotation</li>
                    <li>• Communication entre les usagers et les équipes Canopé</li>
                    <li>• Suivi des stocks et de la disponibilité</li>
                    <li>• Notification par email des changements de statut</li>
                    <li>• Gestion des comptes utilisateurs</li>
                </ul>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-5">
                <p class="text-red-700 font-medium">⚠️ Nous ne collectons aucune donnée sensible (origine ethnique, opinions politiques, convictions religieuses, données de santé, etc.)</p>
            </div>

            <div class="text-gray-600 leading-relaxed">
                <h4 class="font-semibold text-gray-800 mb-2">Durées de conservation</h4>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    <li><strong>3 ans</strong> pour les comptes utilisateurs inactifs</li>
                    <li><strong>5 ans</strong> pour l'historique des demandes (obligation légale)</li>
                    <li><strong>Suppression immédiate</strong> sur demande de l'utilisateur</li>
                </ul>
            </div>

            <div class="text-gray-600 leading-relaxed">
                <h4 class="font-semibold text-gray-800 mb-2">🍪 Politique cookies</h4>
                <p class="text-sm">Notre site utilise uniquement des <strong>cookies strictement nécessaires</strong> au fonctionnement (session et sécurité CSRF). Aucun cookie de tracking ou publicitaire.</p>
            </div>
        </div>
    </section>

    <!-- Article 4 -->
    <section id="article4" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">4</span>
            <h2 class="text-2xl font-normal text-gray-900">Responsable du traitement</h2>
        </div>
        <div class="pl-11 text-gray-600 leading-relaxed">
            <p class="mb-4">Les données sont collectées par <strong>Réseau Canopé - Corse</strong>.</p>
            <div class="bg-gray-50 rounded-xl p-5">
                <p class="font-semibold text-gray-800 mb-2">Délégué à la Protection des Données (DPO)</p>
                <p class="text-sm">Email : <a href="mailto:dpo@reseau-canope.fr" class="text-canope-green hover:underline">dpo@reseau-canope.fr</a></p>
            </div>
        </div>
    </section>

    <!-- Article 5 -->
    <section id="article5" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">5</span>
            <h2 class="text-2xl font-normal text-gray-900">Droits de l'utilisateur</h2>
        </div>
        <div class="pl-11">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="font-medium text-gray-800">📖 Droit d'accès</p>
                    <p class="text-sm text-gray-500 mt-1">Obtenir confirmation du traitement de vos données</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="font-medium text-gray-800">✏️ Droit de rectification</p>
                    <p class="text-sm text-gray-500 mt-1">Corriger des données inexactes</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="font-medium text-gray-800">🗑️ Droit à l'effacement</p>
                    <p class="text-sm text-gray-500 mt-1">Demander la suppression de vos données</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="font-medium text-gray-800">📤 Droit à la portabilité</p>
                    <p class="text-sm text-gray-500 mt-1">Recevoir vos données dans un format lisible</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="font-medium text-gray-800">⏸️ Droit à la limitation</p>
                    <p class="text-sm text-gray-500 mt-1">Limiter le traitement de vos données</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="font-medium text-gray-800">🚫 Droit d'opposition</p>
                    <p class="text-sm text-gray-500 mt-1">S'opposer au traitement de vos données</p>
                </div>
            </div>
            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-5">
                <p class="text-blue-800 font-medium">💡 Nous nous engageons à répondre à votre demande dans un délai maximum d'1 mois.</p>
            </div>
        </div>
    </section>

    <!-- Article 6 -->
    <section id="article6" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">6</span>
            <h2 class="text-2xl font-normal text-gray-900">Mesures de sécurité</h2>
        </div>
        <div class="pl-11">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div class="bg-canope-light/50 rounded-lg p-4 text-center">
                    <p class="text-2xl mb-2">🔒</p>
                    <p class="text-sm font-medium text-gray-700">HTTPS</p>
                </div>
                <div class="bg-canope-light/50 rounded-lg p-4 text-center">
                    <p class="text-2xl mb-2">🔐</p>
                    <p class="text-sm font-medium text-gray-700">Mots de passe hashés</p>
                </div>
                <div class="bg-canope-light/50 rounded-lg p-4 text-center">
                    <p class="text-2xl mb-2">👥</p>
                    <p class="text-sm font-medium text-gray-700">Accès restreint</p>
                </div>
                <div class="bg-canope-light/50 rounded-lg p-4 text-center">
                    <p class="text-2xl mb-2">💾</p>
                    <p class="text-sm font-medium text-gray-700">Sauvegardes</p>
                </div>
                <div class="bg-canope-light/50 rounded-lg p-4 text-center">
                    <p class="text-2xl mb-2">📋</p>
                    <p class="text-sm font-medium text-gray-700">Journalisation</p>
                </div>
                <div class="bg-canope-light/50 rounded-lg p-4 text-center">
                    <p class="text-2xl mb-2">🔄</p>
                    <p class="text-sm font-medium text-gray-700">Mises à jour</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Article 7 -->
    <section id="article7" class="mb-10 scroll-mt-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-canope-green text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">7</span>
            <h2 class="text-2xl font-normal text-gray-900">Modifications de la politique</h2>
        </div>
        <div class="pl-11 text-gray-600 leading-relaxed">
            <p>Réseau Canopé se réserve le droit de modifier cette politique à tout moment. Les modifications seront communiquées par :</p>
            <ul class="list-disc pl-5 mt-3 space-y-1">
                <li>Notification par email</li>
                <li>Bannière d'information lors de la prochaine connexion</li>
            </ul>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="scroll-mt-8">
        <div class="bg-gradient-to-br from-canope-green to-canope-olive rounded-2xl p-8 text-white">
            <h2 class="text-2xl font-normal mb-4">📬 Contact</h2>
            <p class="opacity-90 mb-4">Pour toute question concernant cette politique de confidentialité :</p>
            <div class="space-y-2 text-sm">
                <p><strong>Email :</strong> <a href="mailto:dpo@reseau-canope.fr" class="underline">dpo@reseau-canope.fr</a></p>
                <p><strong>Adresse :</strong> Réseau Canopé - Corse</p>

            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
