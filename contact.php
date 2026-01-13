<?php
require_once 'includes/db.php';
include 'includes/header.php';

// Email address to contact
$contactEmail = "contact@reseau-canope.fr";
$emailSubject = "Contact depuis la plateforme de dotation";
?>

<div class="max-w-5xl mx-auto px-5 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mt-8">
        <div>
            <h1 class="text-4xl font-normal mb-6 text-gray-900">Contactez-nous</h1>
            <p class="text-gray-600 mb-8">
               <u> ​Atelier d'AJACCIO</u><br>

                Immeuble Castellani Avenue Mont Thabor Ajaccio<br>
                +33 4 20 97 00 20<br>
                <a href="mailto:contact.atelier2a@reseau-canope.fr">contact.atelier2a@reseau-canope.fr</a> <br>
​               <u> Atelier de BASTIA</u><br>
                Casa di e lingue , 15 Rue Saint-Angelo, Bastia <br>
                +33 4 20 97 00 10<br>
                <a href="mailto:contact.atelier2b@reseau-canope.fr">contact.atelier2b@reseau-canope.fr</a><br>

            </p>
            
            <!-- Bouton avec méthode pour ouvrir l'interface de mail / Quand on clique on nous demande notre messagerie préféré  -->
            <button onclick="openEmailModal()" class="border hover:scale-95 duration-300 relative group cursor-pointer text-sky-50  overflow-hidden h-13 w-60 rounded-full bg-sky-200 p-2 flex justify-center items-center font-extrabold">
                    
  <div class="absolute right-32 -top-4  group-hover:top-1 group-hover:right-2 z-10 w-40 h-40 rounded-full group-hover:scale-150 duration-500 bg-sky-900"></div>
  <div class="absolute right-2 -top-4  group-hover:top-1 group-hover:right-2 z-10 w-32 h-32 rounded-full group-hover:scale-150  duration-500 bg-sky-800"></div>
  <div class="absolute -right-12 top-4 group-hover:top-1 group-hover:right-2 z-10 w-24 h-24 rounded-full group-hover:scale-150  duration-500 bg-sky-700"></div>
  <div class="absolute right-20 -top-4 group-hover:top-1 group-hover:right-2 z-10 w-16 h-16 rounded-full group-hover:scale-150  duration-500 bg-sky-600"></div>
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 z-10 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
  <p class="z-10">Nous contacter</p>
</svg>
</button>
        </div>
         <div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2968.073876723538!2d8.75318227648099!3d41.93426387123558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12da6a32050d040b%3A0x5d1a1183b2ec787a!2sCANOPE%20Acad%C3%A9mie%20de%20Corse!5e0!3m2!1sfr!2sfr!4v1767691927215!5m2!1sfr!2sfr" 
                    width="100%" height="450" style="border:0; border-radius: 16px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

 <!-- Interface avec les clients de messagerie qui apparaît quand on clique sur "Nous contacter" -->
<div id="emailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Choisissez votre messagerie</h3>
                <button onclick="closeEmailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-1">Sélectionnez votre client email préféré</p>
        </div>
        
        <!-- Les clients mail qui apparaît -->
        <div class="p-6 grid grid-cols-2 gap-4">
            <!-- Gmail -->
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $contactEmail; ?>&su=<?php echo urlencode($emailSubject); ?>" 
               target="_blank"
               class="flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-gray-100 hover:border-red-400 hover:bg-red-50 transition-all group">
                <div class="w-12 h-12 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-10 h-10">
                        <path fill="#EA4335" d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                    </svg>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-red-600">Gmail</span>
            </a>

            <!-- Outlook -->
            <a href="https://outlook.live.com/mail/0/deeplink/compose?to=<?php echo $contactEmail; ?>&subject=<?php echo urlencode($emailSubject); ?>" 
               target="_blank"
               class="flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-gray-100 hover:border-blue-400 hover:bg-blue-50 transition-all group">
                <div class="w-12 h-12 flex items-center justify-center">
                    <img src="assets/img/outlook.png" alt="Outlook" class="w-10 h-10 object-contain">
                </div>
                <span class="font-medium text-gray-700 group-hover:text-blue-600">Outlook</span>
            </a>

            <!-- Yahoo Mail -->
            <a href="https://compose.mail.yahoo.com/?to=<?php echo $contactEmail; ?>&subject=<?php echo urlencode($emailSubject); ?>" 
               target="_blank"
               class="flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-gray-100 hover:border-purple-400 hover:bg-purple-50 transition-all group">
                <div class="w-12 h-12 flex items-center justify-center">
                    <img src="assets/img/yahoo-icon.png" alt="Yahoo" class="w-10 h-10 object-contain">
                </div>
                <span class="font-medium text-gray-700 group-hover:text-purple-600">Yahoo</span>
            </a>

            <!-- Default Mail App -->
            <a href="mailto:<?php echo $contactEmail; ?>?subject=<?php echo urlencode($emailSubject); ?>" 
               class="flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-gray-100 hover:border-canope-slate hover:bg-canope-light/30 transition-all group">
                <div class="w-12 h-12 flex items-center justify-center bg-canope-slate/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-canope-slate" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-canope-slate">Autre</span>
            </a>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl">
            <p class="text-xs text-gray-500 text-center">
                Ou copiez l'adresse : <span class="font-mono bg-white px-2 py-1 rounded border text-canope-green"><?php echo $contactEmail; ?></span>
            </p>
        </div>
    </div>
</div>

<script>
    function openEmailModal() {
        const modal = document.getElementById('emailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEmailModal() {
        const modal = document.getElementById('emailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('emailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEmailModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmailModal();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
