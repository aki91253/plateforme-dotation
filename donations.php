<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="max-w-5xl mx-auto px-5 py-8">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
        <!-- Project Card 1 -->
        <div class="rounded-xl overflow-hidden">
            <img src="https://via.placeholder.com/400x300/5CA4F8/ffffff?text=Project+1" class="w-full h-52 object-cover rounded-xl bg-gray-200" alt="Project">
            <div class="flex justify-between py-3 text-sm font-bold text-gray-800">
                <span>First project</span>
                <span>2025</span>
            </div>
        </div>

        <!-- Project Card 2 -->
        <div class="rounded-xl overflow-hidden">
            <img src="https://via.placeholder.com/400x300/60D0A0/ffffff?text=Project+2" class="w-full h-52 object-cover rounded-xl bg-gray-200" alt="Project">
            <div class="flex justify-between py-3 text-sm font-bold text-gray-800">
                <span>Another project</span>
                <span>2025</span>
            </div>
        </div>

        <!-- Project Card 3 -->
        <div class="rounded-xl overflow-hidden">
            <img src="https://via.placeholder.com/400x300/FFB347/ffffff?text=Project+3" class="w-full h-52 object-cover rounded-xl bg-gray-200" alt="Project">
            <div class="flex justify-between py-3 text-sm font-bold text-gray-800">
                <span>Third project</span>
                <span>2025</span>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
