<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="container">
    <div class="contact-container">
        <div class="contact-info">
            <h1>Contactez-nous</h1>
            <p>The Location<br>Location<br>City, ZZ 00000</p>
            
            <form action="" method="POST" style="margin-top: 2rem;">
                 <!-- This button in the design looks like it might open a modal or be the submit -->
                <button type="submit" class="btn-cta" style="border:none; cursor:pointer;">Envoyer ></button>
            </form>
        </div>
        
        <div class="contact-map">
            <!-- Map Placeholder -->
            <div style="background-color: #E6F0FF; width: 100%; height: 400px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 3rem;">📍</span>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
