<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="container">
    
    <!-- Filter/Sort placeholder if needed -->
    <!-- <div style="display: flex; gap: 20px; font-weight: bold; margin-bottom: 20px;">
        <span>Tout</span> <span>Vie Quotidienne</span> <span>Santé</span>
    </div> -->

    <div class="donations-grid">
        <!-- Static Data for Design Review -->
        <div class="donation-card">
            <img src="https://via.placeholder.com/400x300/5CA4F8/ffffff?text=Project+1" class="card-image" alt="Project">
            <div class="card-info">
                <span>First project</span>
                <span>2025</span>
            </div>
        </div>

        <div class="donation-card">
            <img src="https://via.placeholder.com/400x300/60D0A0/ffffff?text=Project+2" class="card-image" alt="Project">
            <div class="card-info">
                <span>Another project</span>
                <span>2025</span>
            </div>
        </div>

           <div class="donation-card">
            <img src="https://via.placeholder.com/400x300/FFB347/ffffff?text=Project+3" class="card-image" alt="Project">
            <div class="card-info">
                <span>Third project</span>
                <span>2025</span>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
