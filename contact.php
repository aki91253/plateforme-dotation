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
                France<br>
                Corse 20090<br>
                Ajaccio, 19 Avenue du Mont Thabor
            </p>
            
            <!-- Button to open modal -->
            <button onclick="openEmailModal()" class="bg-canope-olive text-white px-6 py-3 rounded-full text-sm cursor-pointer border-none hover:opacity-90 transition-opacity flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
                Nous contacter
            </button>
        </div>
        

        
        <div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2968.073876723538!2d8.75318227648099!3d41.93426387123558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12da6a32050d040b%3A0x5d1a1183b2ec787a!2sCANOPE%20Acad%C3%A9mie%20de%20Corse!5e0!3m2!1sfr!2sfr!4v1767691927215!5m2!1sfr!2sfr" 
                    width="100%" height="450" style="border:0; border-radius: 16px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<!-- Email Provider Modal -->
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
        
        <!-- Email Providers Grid -->
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
                    <svg viewBox="0 0 24 24" class="w-10 h-10">
                        <path fill="#0078D4" d="M24 7.387v10.478c0 .23-.08.424-.238.576-.158.154-.352.23-.582.23h-8.026v-6.18l1.274.96 1.124-1.478L14 9.373V7.387h2.104c1.322 0 2.104.782 2.104 2.104v1.882l1.792-1.433V7.387A4.213 4.213 0 0 0 15.787 3.2H8.213A4.213 4.213 0 0 0 4 7.387v9.426A4.213 4.213 0 0 0 8.213 21h7.574A4.213 4.213 0 0 0 20 16.813v-1.334l4 3.2V7.387zM0 7.1v9.613c0 .23.08.424.238.576.158.154.352.23.582.23h8.026V7.1H0z"/>
                    </svg>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-blue-600">Outlook</span>
            </a>

            <!-- Yahoo Mail -->
            <a href="https://compose.mail.yahoo.com/?to=<?php echo $contactEmail; ?>&subject=<?php echo urlencode($emailSubject); ?>" 
               target="_blank"
               class="flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-gray-100 hover:border-purple-400 hover:bg-purple-50 transition-all group">
                <div class="w-12 h-12 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-10 h-10">
                        <path fill="#6001D2" d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 4.795 2.135 5.206h-2.303l-2.07-5.206 2.07-4.795h2.138zm-7.021 0l2.07 4.795-2.07 5.206H8.57l2.135-5.206-1.97-4.795h2.138z"/>
                    </svg>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-purple-600">Yahoo</span>
            </a>

            <!-- Default Mail App -->
            <a href="mailto:<?php echo $contactEmail; ?>?subject=<?php echo urlencode($emailSubject); ?>" 
               class="flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-gray-100 hover:border-canope-green hover:bg-canope-light/30 transition-all group">
                <div class="w-12 h-12 flex items-center justify-center bg-canope-green/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-canope-green" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-canope-green">Autre</span>
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
