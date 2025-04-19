<?php
$title = "Plan du site";
$description = "Le plan du site";
$h1 = "Méteo";
require_once "./include/cookies.inc.php";
require_once "./include/header.inc.php";
require_once "./include/functions.inc.php";
?>
<main>
    <section>
        <h2>
            Plan du site
        </h2>
        <ul>
            <li><a href="./index.php">Accueil</a></li>
            <li><a href="./carte.php">Carte</a></li>
            <li><a href="./ville.php">Recherche par Localisation manuelle</a></li>
            <li><a href="./Statistiques.php">Statistiques</a></li>
        </ul>
        <h2>
            Annexes des liens utilisés
        </h2>
        <ul>
            <li><a href="http://api.weatherapi.com">http://api.weatherapi.com</a></li>
            <li><a href="https://www.data.gouv.fr/fr/datasets/regions-departements-villes-et-villages-de-france-et-doutre-mer/">https://www.data.gouv.fr/fr/datasets/regions-departements-villes-et-villages-de-france-et-doutre-mer/</a></li>
        </ul>
    </section>
</main>
<?php require_once "./include/footer.inc.php"; ?>