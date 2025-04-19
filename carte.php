<?php
$title = "Carte De France";
$description = "Carte de localisation";
$depart = $_GET['depart'] ?? null;
require_once "./include/cookies.inc.php";
require_once "./include/header.inc.php";
require_once "./include/functions.inc.php";
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET['depart'])){
        $depart = $_GET['depart'];
    }
}

?>
<main>
	<h1>Méteo</h1>
	<section>
		<h2>Carte de france</h2>
        <?php
		    require_once "./include/France.inc.php";
        ?>
	</section>
    
</main>
<?php require_once "./include/footer.inc.php"; ?>