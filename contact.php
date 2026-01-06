<?php
require_once 'includes/db.php';
include 'includes/header.php';
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
            
            <form action="" method="POST">
                <button type="submit" class="bg-canope-olive text-white px-6 py-3 rounded-full text-sm cursor-pointer border-none hover:opacity-90 transition-opacity">
                    Envoyer >
                </button>
            </form>
        </div>
        

        
        <div>
            <!-- Map Placeholder --><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2968.073876723538!2d8.75318227648099!3d41.93426387123558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12da6a32050d040b%3A0x5d1a1183b2ec787a!2sCANOPE%20Acad%C3%A9mie%20de%20Corse!5e0!3m2!1sfr!2sfr!4v1767691927215!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
