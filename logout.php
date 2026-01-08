<?php
require_once 'includes/auth.php';

// Déconnexion de l'utilisateur et redirection vers la page d'accueil
logoutUser();
redirect('index.php');
?>
