<?php
/**
 * Fichier include pour le formulaire de gestion des cookies
 */

$demandeCookie='cookies_acceptes';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['consentement'])) {
    $consentement = $_POST['consentement'] === 'oui' ? 'true' : 'false';
    setcookie($demandeCookie, $consentement, time() + (86400 * 3), '/');
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
if (!isset($_COOKIE[$demandeCookie])){
    echo "
    <form id=\"null\" method=\"post\" class=\"cookie\">
        <p>Ce site utilise des cookies pour stocker les dernières villes consultées. Acceptez-vous ?</p>
        <button type=\"submit\" name=\"consentement\" value=\"oui\">Oui</button>
        <button type=\"submit\" name=\"consentement\" value=\"non\">Non</button>
    </form>
    ";
}
?>