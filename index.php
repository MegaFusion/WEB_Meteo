
<?php
/**
 * Page D'accueil pour le site
 */

$title = "Bienvenue dans l'Accueil";
$h1 = "Méteo";
$description = "La page d'accueil";
$allFiles = scandir(__DIR__ . '/photos');
	$images = array_filter($allFiles, function($file) {
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		return in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
	});
	if (!empty($images)) {
		$imageRandom = $images[array_rand($images)];
		$cheminImage = 'photos/'.$imageRandom;
	}
	else{
		$cheminImage = '';
	}


require_once "./include/headerIndex.inc.php";
require_once "./include/cookies.inc.php";
require_once "./include/functions.inc.php";
?>
<main>
<aside class="back" aria-hidden="true">
    <div id="accueil">
        <h1>
            Méteo CLIMASCOPE
        </h1>
        <a class="button" href="./carte.php" >Lien vers la carte</a>
    </div>
	</aside>
</main>



<?php require_once "./include/footer.inc.php"; ?>