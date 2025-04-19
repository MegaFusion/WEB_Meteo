<?php 

/**
 *Fonction qui informe le moteur de recherche utilisé
 *
 * @return string Le moteur de recherche
 */
function getNavigateur(): string {
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return "Navigateur inconnu";
    }
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    if (strpos($userAgent, 'Firefox') !== false) {
        return "Mozilla Firefox";
	} elseif (strpos($userAgent, 'Chrome') !== false) {
        return "Google Chrome";
	} elseif (strpos($userAgent, 'Safari') !== false) {
        return "Apple Safari";
	} elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
		return "Opera";
    } elseif (strpos($userAgent, 'Edg') !== false) {
        return "Microsoft Edge";
    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
        return "Internet Explorer";
    } else {
        return "Navigateur inconnu";
    }
}

/**
 * Fonction qui affiche une datalist de régions. Utilise un fichier csv à lire ( regions.csv )
 *
 * @param string $fichier Chemin vers le fichier csv
 * @return string La datalist des régions ou un message d'erreur si n'arrive pas à ouvrir le fichier
 */
function afficheDataRegions($fichier): string {
    $fullPath = __DIR__ . '/../' . $fichier;
    if (!file_exists($fullPath)) {
        return "<p>Error: File not found at {$fullPath}</p>";
    }

    $datalist = "";
    $fileHandle = fopen($fullPath, 'r');
    if (!$fileHandle) {
        return "<p>Error: Unable to open file at {$fullPath}</p>";
    }

    fgetcsv($fileHandle);

    $regions = [];


    while ($ligne = fgetcsv($fileHandle)) {
        $region = ucfirst(strtolower(trim($ligne[3])));
        $regions[] = $region;
    }

    $regions = array_unique($regions);
    sort($regions);

    fclose($fileHandle);

    foreach ($regions as $region) {
        $datalist .= "<option value=\"$region\"></option>\t";
    }

    return $datalist;
}

/**
 * Fonction qui affiche une datalist de départements. Utilise un fichier csv à lire ( departments.csv )
 *
 * @param string $fichier Chemin vers le fichier csv
 * @return string La datalist des départements ou un message d'erreur si n'arrive pas à ouvrir le fichier
 */
function afficheDataDepartements($fichier): string {
    $fullPath = __DIR__ . '/../' . $fichier;
    if (!file_exists($fullPath)) {
        return "<p>Error: File not found at {$fullPath}</p>";
    }

    $datalist = "";
    $fileHandle = fopen($fullPath, 'r');
    if (!$fileHandle) {
        return "<p>Error: Unable to open file at {$fullPath}</p>";
    }

    fgetcsv($fileHandle);

    $departements = [];


    while ($ligne = fgetcsv($fileHandle)) {
        $departement = ucfirst(strtolower(trim($ligne[4])));
        $departements[] = $departement;
    }

    $departements = array_unique($departements);
    sort($departements);

    fclose($fileHandle);

    foreach ($departements as $departement) {
        $datalist .= "<option value=\"$departement\"></option>\t";
    }

    return $datalist;
}

/**
 * Fonction qui renvoie les villes correspondant au département séléctionné
 *
 * @param string $fichier Chemin vers le fichier csv
 * @param string|null $departement Nom du département à filtrer
 * @return array  Liste des noms de villes
 */
function getVillesParDepartement(string $fichier, ?string $departement = null): array {
    $fullPath = __DIR__ . '/../' . $fichier;
    if (!file_exists($fullPath)) {
        return [];
    }

    $file = fopen($fullPath, 'r');
    if (!$file) {
        return [];
    }

    fgetcsv($file);
    $villesAssoc = [];

    while ($ligne = fgetcsv($file)) {
        if ($departement !== null && trim($ligne[1]) !== trim($departement)) {
            continue;
        }

        $ville = trim($ligne[4]);
        if (!empty($ville)) {
            $villesAssoc[$ville] = true;
        }
    }

    fclose($file);

    $villes = array_keys($villesAssoc);
    natcasesort($villes);

    return $villes;
}

/**
 * Fonction qui renvoie les départements correspondant à la région sélectionnée
 *
 * @param string $fichier Chemin vers le fichier csv
 * @param string|null $region  Nom de la région à filtrer 
 * @return array Liste des départements 
 */
function getDepartementParRegions(string $fichier, ?string $region = null): array {
    $fullPath = __DIR__ . '/../' . $fichier;
    if (!file_exists($fullPath)) {
        return [];
    }

    $file = fopen($fullPath, 'r');
    if (!$file) {
        return [];
    }

    fgetcsv($file);
    $departAssoc = [];

    while ($ligne = fgetcsv($file)) {
        if ($region !== null && trim($ligne[1]) !== trim($region)) {
            continue;
        }

        $depart = trim($ligne[4]);
        if (!empty($depart)) {
            $departAssoc[$depart] = true;
        }
    }

    fclose($file);

    $depart = array_keys($departAssoc);
    natcasesort($depart);

    return $depart;
}

/**
 * Fonction qui affiche une liste des villes pour un département donné
 * Utilise la fonction getVillesParDepartement() pour récupérer les villes correspondant au départment
 *
 * @param string $fichier Chemin vers le fichier csv
 * @param string|null $departement Nom du département à filtrer
 * @return string $datalist La liste des villes filtré selon le département
 */
function afficheDataCity(string $fichier, ?string $departement = null): string {
    $villes = getVillesParDepartement($fichier, $departement);

    $datalist = "";
    foreach ($villes as $ville) {
        $datalist .= "<option value=\"" . htmlspecialchars($ville) . "\">\n";
    }

    return $datalist;
}

/**
 * Fonction qui affiche une liste des département pour une région donné.
 * Utilise la fonction getDepartementParRegions() pour récupérer les département correspondant au région
 *
 * @param string $fichier Chemin vers le fichier csv
 * @param string|null $region Nom de la région à filtrer
 * @return string $datalist La liste des département filtré selon la région
 */
function afficheDataDepart(string $fichier, ?string $region = null): string {
    $departement = getDepartementParRegions($fichier, $region);

    $datalist = "";
    foreach ($departement as $departements) {
        $datalist .= "<option value=\"" . htmlspecialchars($departements) . "\">\n";
    }

    return $datalist;
}

/**
 * Fonction qui renvoie le numéro d’un département d’après son nom
 *
 * @param string $fichier Chemin vers le fichier csv
 * @param string $nomDepartement Nom du département recherché
 * @return string Numéro du département
 */
function getNumeroDepartement($fichier, $nomDepartement): string {
    $fullPath = __DIR__ . '/../' . $fichier;
    if (!file_exists($fullPath)) {
        return "<p>Error: File not found at {$fullPath}</p>";
    }

    $fileHandle = fopen($fullPath, 'r');
    if (!$fileHandle) {
        return "<p>Error: Unable to open file at {$fullPath}</p>";
    }

    fgetcsv($fileHandle);

    while ($ligne = fgetcsv($fileHandle)) {
        $departement = strtolower(trim($ligne[4]));
        if ($departement === strtolower(trim($nomDepartement))) {
            fclose($fileHandle);
            return $ligne[2];
        }
    }

    fclose($fileHandle);

    return "<p>Department non trouvé.</p>";
}

/**
 * Fonction qui enleve les lettres avec des accents dans une chaine de caractères
 *
 * @param string $texte la chaine de caractères
 * @return void le texte sans accent
 */
function enleverAccents($texte) {
    return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte);
}


/**
 * Fonction qui renvoie le numéro d’une région d’après son nom
 *
 * @param string $fichier Chemin vers le fichier csv
 * @param string $nomRegion Nom de la région recherchée
 * @return string Numéro de la région
 */
function getNumeroRegion($fichier, $nomRegion): string {
    $fullPath = __DIR__ . '/../' . $fichier;
    if (!file_exists($fullPath)) {
        return "<p>Error: File not found at {$fullPath}</p>";
    }

    $fileHandle = fopen($fullPath, 'r');
    if (!$fileHandle) {
        return "<p>Error: Unable to open file at {$fullPath}</p>";
    }

    fgetcsv($fileHandle);

    while ($ligne = fgetcsv($fileHandle)) {
        $region = strtolower(trim($ligne[3]));
        if ($region === strtolower(trim($nomRegion))) {
            fclose($fileHandle);
            return $ligne[1];
        }
    }

    fclose($fileHandle);

    return "<p>Department non trouvé.</p>";
}

/**
 * Récupère les prévisions météo pour une ville donnée sur 3 jours
 *
 * @param string $city Nom de la ville 
 * @return array|null $data Les données météo décodé
 */
function getWeather(string $city): ?array {
    $apiKey = 'e9af5d87f4bc48009c0191739253003';
    $url = "http://api.weatherapi.com/v1/forecast.json?key=$apiKey&q=" . urlencode($city) . "&days=3&lang=fr";

    $response = @file_get_contents($url);
    if ($response === false) return null;

    $data = json_decode($response, true);
    return $data;
}

?>


