<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="container">
    <section class="hero">
        <h1>Bienvenue sur la Plateforme de Dotation</h1>
        <p>Gérez vos projets et demandes de financement en toute simplicité.</p>
        <a href="#" class="btn btn-primary">Voir les projets</a>
    </section>

    <section class="status-check">
        <h2>État du système</h2>
        <?php
        if (isset($pdo)) {
            echo '<p style="color: green; font-weight: bold;">✔ Connexion à la base de données établie avec succès.</p>';
        } else {
            echo '<p style="color: red; font-weight: bold;">✘ Erreur de connexion à la base de données.</p>';
        }
        ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
