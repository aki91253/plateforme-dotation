<?php
require_once 'includes/auth.php';

// Logout user and redirect to home
logoutUser();
redirect('index.php');
?>
