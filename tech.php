<?php
$title = "Tech";
$description = "La Page TECH";
require_once "./include/cookies.inc.php";
require_once "./include/header.inc.php";
require_once "./include/functions.inc.php";

$api_key = "JPLPbMyBJWPkGuhMmCHEPtGBvfoGD0ZIeSYxWDB6";
$date = date('Y-m-d');

//Recuperation des donnees apod
$apod_url = "https://api.nasa.gov/planetary/apod?api_key=$api_key&date=$date";
$apod_json = file_get_contents($apod_url);
$apod_data = json_decode($apod_json, true);

function getUserIP() {
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}
//Recuperation des donnees GeoPlugin
$ip = getUserIP();
$geo_url = "http://www.geoplugin.net/xml.gp?ip=$ip";
$geo_xml = file_get_contents($geo_url);
$geo_data = simplexml_load_string($geo_xml);

//Recuperation des donnees ipinfo.io
$ipinfo_url = "http://ipinfo.io/$ip/geo";
$ipinfo_json = file_get_contents($ipinfo_url);
$ipinfo_data = json_decode($ipinfo_json, true);

//Recuperation des donnees whatismyip
$whatismyip_key = "320cf46134aa64d8e7d5c8d4378934e9";
$whatismyip_url = "https://api.whatismyip.com/ip-address-lookup.php?key=$whatismyip_key&input=$ip&output=json";
$whatismyip_xml = file_get_contents($whatismyip_url);
$whatismyip_data = json_decode($whatismyip_xml,true);
$whatismyip_data = $whatismyip_data['ip_address_lookup'][0] ?? [];
?>
<main>
	<h1> Page Tech du Projet</h1>
	<section>
		<h2>Astronomy Picture of the Day</h2>
		<?
			if (isset($apod_data['media_type']) && $apod_data['media_type'] === 'image'){
				echo '<img src="' . htmlspecialchars($apod_data['url']) . '" alt="Image apod"/>';
			} elseif ($apod_data['media_type'] === 'video'){
				echo '<video controls src="' . htmlspecialchars($apod_data['url']) . '" alt="Video apod"/>'; 
			}
			elseif ($apod_data['media_type'] === 'other'){
			echo '<p>Contenu other</p>'; 
			}
			echo '<p><strong>' . htmlspecialchars($apod_data['title']) . '</strong></p>';
			echo '<p>' . htmlspecialchars($apod_data['explanation']) . '</p>';
		?>
	</section>
	
	<section>
		<h2>Localisation GeoPlugin</h2>
		<?
			echo '<p>Adresse IP : ' . htmlspecialchars($ip) . '</p>';
			echo '<p>Pays : ' . htmlspecialchars($geo_data->geoplugin_countryName) . '</p>';
			echo '<p>Région : ' . htmlspecialchars($geo_data->geoplugin_region) . '</p>';
			echo '<p>Ville : ' . htmlspecialchars($geo_data->geoplugin_city) . '</p>';
			echo '<p>Latitude : ' . htmlspecialchars($geo_data->geoplugin_latitude) . '</p>';
			echo '<p>Longitude : ' . htmlspecialchars($geo_data->geoplugin_longitude) . '</p>';
		?>
	</section>
	
	<section>
		<h2>Localisation Ipinfo.io</h2>
		<?
			echo '<p>Adresse Ip : ' . $ip . '</p>';
			echo '<p>Pays : ' . htmlspecialchars($ipinfo_data['country']) . '</p>';
			echo '<p>Région : ' . htmlspecialchars($ipinfo_data['region']) . '</p>';
			echo '<p>Ville : ' . htmlspecialchars($ipinfo_data['city']) . '</p>';
			echo '<p>Code Postal : ' . htmlspecialchars($ipinfo_data['postal']) . '</p>';
			echo '<p>Coordonnées : ' . htmlspecialchars($ipinfo_data['loc']) . '</p>';
			echo '<p>Fuseau Horaire : ' . htmlspecialchars($ipinfo_data['timezone']) . '</p>';
		?>
	</section>
	
	<section>
		<h2>Localisation Whatismyip</h2>
		<?
			echo '<p>IP : ' . htmlspecialchars($ip) . '</p>';
			echo '<p>Pays : ' . htmlspecialchars($whatismyip_data['country']) . '</p>';
			echo '<p>Ville : ' . htmlspecialchars($whatismyip_data['city']) . '</p>';
			echo '<p>Région : ' . htmlspecialchars($whatismyip_data['region']) . '</p>';
			echo '<p>Code Postal : ' . htmlspecialchars($whatismyip_data['postalcode']) . '</p>';
			echo '<p>Fournisseur : ' . htmlspecialchars($whatismyip_data['isp']) . '</p>';
			echo '<p>Fuseau horaire : ' . htmlspecialchars($whatismyip_data['time']) . '</p>';
			echo '<p>Latitude : ' . htmlspecialchars($whatismyip_data['latitude']) . '</p>';
			echo '<p>Longitude : ' . htmlspecialchars($whatismyip_data['longitude']) . '</p>';
		?>
	</section>
</main>
<?php require_once "./include/footer.inc.php"; ?>
