<?php
/**
 * AJAX endpoint for chart data
 * Returns daily request statistics as JSON
 */
require_once 'includes/admin_auth.php';
require_once '../includes/db.php';
require_once '../includes/queries.php';

// Require admin authentication
requireAdmin();

// Get parameters
$days = isset($_GET['days']) ? max(7, min(365, intval($_GET['days']))) : 30;

// Parse multi-select filters
$selectedStatuses = [];
if (isset($_GET['status_filter'])) {
    if (is_array($_GET['status_filter'])) {
        $selectedStatuses = array_map('intval', $_GET['status_filter']);
    } else {
        $selectedStatuses = array_map('intval', explode(',', $_GET['status_filter']));
    }
}

$selectedCategories = [];
if (isset($_GET['category_chart'])) {
    if (is_array($_GET['category_chart'])) {
        $selectedCategories = array_map('intval', $_GET['category_chart']);
    } else {
        $selectedCategories = array_map('intval', explode(',', $_GET['category_chart']));
    }
}

// Get data from database
$dailyStats = getDailyRequestStats($days, $selectedStatuses, $selectedCategories);

// Prepare chart data
$dates = [];
$totals = [];

for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = date('d/m', strtotime($date));
    
    $found = false;
    foreach ($dailyStats as $stat) {
        if ($stat['date'] === $date) {
            $totals[] = (int)$stat['total'];
            $found = true;
            break;
        }
    }
    if (!$found) {
        $totals[] = 0;
    }
}

// Calculate summary stats
$totalSum = array_sum($totals);
$average = $days > 0 ? round($totalSum / $days, 1) : 0;
$peak = !empty($totals) ? max($totals) : 0;

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'dates' => $dates,
    'totals' => $totals,
    'summary' => [
        'total' => $totalSum,
        'average' => $average,
        'peak' => $peak
    ]
]);
