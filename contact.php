<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="max-w-5xl mx-auto px-5 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mt-8">
        <div>
            <h1 class="text-4xl font-normal mb-6 text-gray-900">Contactez-nous</h1>
            <p class="text-gray-600 mb-8">
                The Location<br>
                Location<br>
                City, ZZ 00000
            </p>
            
            <form action="" method="POST">
                <button type="submit" class="bg-canope-olive text-white px-6 py-3 rounded-full text-sm cursor-pointer border-none hover:opacity-90 transition-opacity">
                    Envoyer >
                </button>
            </form>
        </div>
        
        <div>
            <!-- Map Placeholder -->
            <div class="bg-blue-100 w-full h-96 flex items-center justify-center rounded-lg">
                <span class="text-5xl">📍</span>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
