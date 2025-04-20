<?php
/**
 * Fichier include pour le header de la page d'accueil
 */

if (isset($_GET['style'])){
	$style = ($_GET['style'] === 'alternatif') ? 'alternatif' : 'standard';
	setcookie('style', $style, time() + (86400 * 3), '/');
	header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$style = (isset($_COOKIE['style']) && $_COOKIE['style'] === 'alternatif')? 'alternatif' : 'standard';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= isset($title) ? $title : "Projet Meteo" ?></title>
    <meta name="description" content="<?= isset($description) ? $description : "Projet meteo" ?>"/>
    <link rel="stylesheet" href="<?= $style == 'alternatif' ? './styles_alternatif.css' : './styles.css' ?>"/>
    <link rel="icon" type="image/png" href="images/favicon.png"/>

    <?php if($cheminImage): ?>
    <style>
    aside.back {
    display: flex;
    flex-direction: column;
    min-height: 120vh;
    
    background-image: url('<?php echo $cheminImage; ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    }
    </style>
    <?php endif ?>
</head>
<body>
    
<header>
    <nav id="navbarAcc">
        <div class="logo">
			<a href="index.php"><img src="images/logo.png" alt="logo site"/></a>
        </div>
		<div class="nav-links">
            <a href="tech.php">La page Tech</a>
            <a href="carte.php">Carte</a>
            <a href="ville.php">Localité</a>
            <a href="Statistiques.php">Statistiques</a>
        </div>
		<div class="settings">
            <form action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="get">
                <button type="submit" name="style" value="standard">
                    <img src="images/light.png" alt="Mode Clair" title="Light Mode"/>
                </button>
                <button type="submit" name="style" value="alternatif">
                    <img src="images/dark.png" alt="Mode Sombre" title="Dark Mode"/>
                </button>
            </form>
        </div>
    </nav>
</header>

