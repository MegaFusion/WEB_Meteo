<?php
/** 
 * Page localisation et méteo des villes
 * Affiche les méteo d'une ville apres avoir localisé
 */
$title = "Localisation de la ville";
$description = "La Page Meteo d'une ville";

require_once "./include/header.inc.php";
require_once "./include/cookies.inc.php";
require_once "./include/functions.inc.php";

$depart = $_GET['depart'] ?? '';
$region = $_GET['region'] ?? '';
$selectedDepartment = $depart ? ucwords($depart) : '';
$selectedRegion = $region ? ucwords($region) : '';

if (isset($_POST['info'])){
	$info = $_POST['info'];
}
else{
	$info=null;
}

if (isset($_POST['ajouter'])){
	$ajouter = $_POST['ajouter'];
}
else{
	$ajouter = true;
}

$selectedCity = $_POST['ville'] ?? null;
$weather = null;

$cookieNom = 'dernier_Consultations';
$cookieValide = isset($_COOKIE['cookies_acceptes']) && $_COOKIE['cookies_acceptes'] === 'true';

if ($selectedCity) {
    $weatherJson = getWeather($selectedCity);
    if ($weatherJson && isset($weatherJson['location'])) {
        $weather = $weatherJson;
		if ($_SERVER["REQUEST_METHOD"] === "POST" && (!empty($_POST["ville"]) && $ajouter) ) {
    		$ville = $_POST["ville"];
    		$date = date("Y-m-d H:i:s");

    		$ligne = [$ville, $date];
    		$fichier = fopen("stockage/consultations.csv", "a");
    		fputcsv($fichier, $ligne);
    		fclose($fichier);
			
			if ($cookieValide) {
                $consulte = [];
                if (!empty($_COOKIE[$cookieNom])) {
                    $consulte = json_decode($_COOKIE[$cookieNom], true) ?: [];
                }
                array_unshift($consulte, ['ville' => $ville, 'date' => $date]);
                $consulte = array_slice($consulte, 0, 5);
                setcookie($cookieNom, json_encode($consulte), time() + (86400 * 3), '/');
            }
		}
    }
}

$dernierConsultations = [];
if ($cookieValide && !empty($_COOKIE[$cookieNom])) {
    $dernierConsultations = json_decode($_COOKIE[$cookieNom], true) ?: [];
}
?>

<main>
	<h1> Méteo de la ville</h1>
	
	<?php if($cookieValide && !empty($dernierConsultations)): ?>
	<section class="dernier-consultations">
		<h2>Dernier Consultations</h2>
			<ul>
				<?php foreach($dernierConsultations as $Consultations): ?>
				<li><strong><?= htmlspecialchars($Consultations['ville']) ?></strong> le <?= htmlspecialchars($Consultations['date'])?></li>
				<?php endforeach; ?>
			</ul>
	</section>
	<?php endif; ?>
	
	<section>
		<h2>Entrez une région en France</h2>
		<form method="GET" action=" <?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
            <label for="region">Region</label>
            <input list="regions" id="region" name="region" value="<?= htmlspecialchars($selectedRegion) ?>" />
            <datalist id="regions">
                <?php
                    echo afficheDataRegions("ressources/csv/regions.csv");
                ?>
            </datalist>
            <input type="submit" value="Valider la region"/>       
        </form>

		<?php
				if ($region === ''){
					echo "
					<h2>Ou entrez un departement en France</h2>
					<form method=\"GET\" action=\"". htmlspecialchars($_SERVER['REQUEST_URI']). "\">
					<input type=\"hidden\" name=\"region\" value=\"". htmlspecialchars($selectedRegion) ."\">
            		<label for=\"depart\">Departement</label>
            		<input list=\"departs\" id=\"depart\" name=\"depart\" value=\"". htmlspecialchars($selectedDepartment) ."\" />

            		<datalist id=\"departs\">
					";
                    echo afficheDataDepartements("ressources/csv/departments.csv");
					echo "
            		</datalist>
            		<input type=\"submit\" value=\"Valider le depart.\"/>       
        			</form>
					";
				}
				else if (isset($_GET['region'])){
					$region = $_GET['region'];
					echo "
					<h2>Entrez Le departement</h2>
					<form method=\"GET\" action=\"". htmlspecialchars($_SERVER['REQUEST_URI']). "\">
					<input type=\"hidden\" name=\"region\" value=\"". htmlspecialchars($selectedRegion) ."\" />
            		<label for=\"depart\">Departement</label>
            		<input list=\"departs\" id=\"depart\" name=\"depart\" value=\"". htmlspecialchars($selectedDepartment) ."\" />
            		<datalist id=\"departs\">
					";
                    echo afficheDataDepart("ressources/csv/departments.csv",getNumeroRegion("ressources/csv/regions.csv",$region));
					echo "
            		</datalist>

            		<input type=\"submit\" value=\"Valider le depart.\"/>       
        			</form>
					";
				}
		?>
		
		<?php
			if (isset($_GET['depart'])){
				$depart = $_GET['depart'];
				echo
		"<h2>Entrez une ville en France</h2>
		<form method=\"POST\">
			<label for=\"ville\">Ville</label>
			<input list=\"villes\" name=\"ville\" id=\"ville\" value=\"". enleverAccents(htmlspecialchars($selectedCity ?? '')) ."\"/>
			<datalist id=\"villes\">";
						echo afficheDataCity("ressources/csv/cities.csv",getNumeroDepartement("ressources/csv/departments.csv",$depart));
				echo "</datalist>
			<input type=\"hidden\" name=\"ajouter\" value=\"true\" />
			<input type=\"submit\" value=\"Obtenir la méteo\" />
		</form>";
		}
		?>

	</section>
	
	<?php if ($weather): ?>
	<section>
		<h2>Météo actuelle pour <?= htmlspecialchars($weather['location']['name']) ?></h2>
		<p>
			<strong>Température:</strong> <?= $weather['current']['temp_c'] ?> °C<br>
			<strong>Condition:</strong> <?= $weather['current']['condition']['text'] ?><br>
			<img src="<?= $weather['current']['condition']['icon'] ?>" alt="<?= htmlspecialchars($weather['current']['condition']['text']) ?>"><br>
			<strong>Vent:</strong> <?= $weather['current']['wind_kph'] ?> km/h
		</p>
	</section>
	
	<?php
	if (!$info): ?>
	<form action="" method="POST">
		<input type="hidden" name="ville" value=" <?= htmlspecialchars($selectedCity ?? '') ?>" />
		<input type="submit" name="info" value="AFFICHER TOUT" />
		<input type="hidden" name="ajouter" value="0" />
	</form>
	<?php
	;else : ?>
	<!-- Météo du Jour (Heure par Heure) -->
	<section class="hourly">
		<h2>Météo du jour (horaire)</h2>
		<?php
		$todayForecast = $weather['forecast']['forecastday'][0];
		if (isset($todayForecast['hour'])):
		?>
		<!-- De minuit a midi -->
		<div class="hourly-row">
			<?php
			foreach ($todayForecast['hour'] as $index => $hourData):
				if ($index == 12) break;
				$time = date("H:i", strtotime($hourData['time']));
			?>
				<div class="hour-block">
					<strong><?= $time ?></strong><br>
					<img src="<?= $hourData['condition']['icon'] ?>" 
						 alt="<?= htmlspecialchars($hourData['condition']['text']) ?>">
					<?= $hourData['temp_c'] ?> °C<br>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- De midi a minuit -->
		<div class="hourly-row">
			<?php
			foreach ($todayForecast['hour'] as $index => $hourData):
				if ($index < 12) continue;
				if ($index >= 24) break;
				$time = date("H:i", strtotime($hourData['time']));
			?>
				<div class="hour-block">
					<strong><?= $time ?></strong><br>
					<img src="<?= $hourData['condition']['icon'] ?>" 
						 alt="<?= htmlspecialchars($hourData['condition']['text']) ?>">
					<?= $hourData['temp_c'] ?> °C<br>
					
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</section>
	
	<!-- Prévisions sur 3 jours -->
	<section class="multi-day-forecast">
		<h2>Prévisions sur 3 jours</h2>
		<div class="forecast-row">
			<?php foreach ($weather['forecast']['forecastday'] as $day): ?>
			<article class="forecast-jour">
				<h3><?= $day['date'] ?></h3>
				<div class="forecast-icon">
					<img src="<?= $day['day']['condition']['icon'] ?>" 
						 alt="<?= htmlspecialchars($day['day']['condition']['text']) ?>">
				</div>
				<p>
					<strong>Condition:</strong> <?= $day['day']['condition']['text'] ?><br>
					<strong>Min:</strong> <?= $day['day']['mintemp_c'] ?> °C<br>
					<strong>Max:</strong> <?= $day['day']['maxtemp_c'] ?> °C<br>
					<strong>Lever du soleil:</strong> <?= $day['astro']['sunrise'] ?><br>
					<strong>Coucher du soleil:</strong> <?= $day['astro']['sunset'] ?>
				</p>
			</article>
			<?php endforeach; ?>
		</div>
	</section>
		<?php endif; ?>
	<?php endif; ?>
	
	
</main>
<?php require_once "./include/footer.inc.php"; ?>
