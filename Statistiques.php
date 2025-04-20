<?php
/** 
 * Page Statistiques du site 
 * Affiche une Histogramme avec les noms des villes et nombres de consultations
 */
$title = "Statistiques des villes";
$description = "page de Statistiques";

require_once "./include/header.inc.php";
require_once "./include/cookies.inc.php";
require_once "./include/functions.inc.php";

?>
<main>
    <h1>
        Statistiques
    </h1>
    <section>
        <h2>
            Histogramme des villes
        </h2>
        <p>
            Voici la liste des villes déja consultées :
        </p>
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reset'])) {
                file_put_contents("stockage/consultations.csv", "");
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }            

            $donnees = [];
            if (($handle = fopen("stockage/consultations.csv", "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    $ville = $data[0];
                    if (!isset($donnees[$ville])) {
                        $donnees[$ville] = 0;
                    }
                    $donnees[$ville]++;
                }
                fclose($handle);
            }
            
            if (!empty($donnees)){
                $max = max($donnees);
            echo "
            <table>\n
                <thead>\n
                    <tr>\t
                    <th>Ville</th>\n
                    <th>Consultations</th>\n
                    <th>Histogramme</th>\n
                    </tr>\n
                </thead>\n
            <tbody>\n";
            foreach ($donnees as $ville => $nombre){
            $largeur = ($nombre / $max) * 300; /* 300px max */
            echo "
            <tr>\t
            <td> $ville </td>\n
            <td> $nombre </td>\n
            <td class=\"histogramme\"><div class=\"barre\" style=\"width: {$largeur}px;\"></div>\n</td>\n
            </tr>";
            }
            echo "
            </tbody>\n
            </table>\n";
            echo "
			<form action=\"" . htmlspecialchars($_SERVER['REQUEST_URI']) . "\" method=\"POST\">
				<input type=\"hidden\" name=\"reset\" value=\"1\"/>
				<input type=\"submit\" onclick=\"return confirm('Etes vous sûr de vouloir tout effacer ?');\" value=\"EFFACER TOUT\"/>
			</form>";
            }
            else{
            echo "
                <p>Aucune donnée pour le moment.</p>\n";
            }
        ?>
    
    </section>

</main>


<?php require_once "./include/footer.inc.php"; ?>