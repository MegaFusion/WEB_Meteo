import re

# Dictionnaire des départements avec leur code INSEE
departements = {
    "01": "Ain", "02": "Aisne", "03": "Allier", "04": "Alpes-de-Haute-Provence", "05": "Hautes-Alpes",
    "06": "Alpes-Maritimes", "07": "Ardèche", "08": "Ardennes", "09": "Ariège", "10": "Aube", 
    "11": "Aude", "12": "Aveyron", "13": "Bouches-du-Rhône", "14": "Calvados", "15": "Cantal", 
    "16": "Charente", "17": "Charente-Maritime", "18": "Cher", "19": "Corrèze", "20": "Corse",
    "2A": "Corse-du-Sud", "2B": "Haute-Corse", "21": "Côte-d'Or", "22": "Côtes-d'Armor", "23": "Creuse",
    "24": "Dordogne", "25": "Doubs", "26": "Drôme", "27": "Eure", "28": "Eure-et-Loir", "29": "Finistère",
    "30": "Gard", "31": "Haute-Garonne", "32": "Gers", "33": "Gironde", "34": "Hérault", "35": "Ille-et-Vilaine",
    "36": "Indre", "37": "Indre-et-Loire", "38": "Isère", "39": "Jura", "40": "Landes", "41": "Loir-et-Cher",
    "42": "Loire", "43": "Haute-Loire", "44": "Loire-Atlantique", "45": "Loiret", "46": "Lot", "47": "Lot-et-Garonne",
    "48": "Lozère", "49": "Maine-et-Loire", "50": "Manche", "51": "Marne", "52": "Haute-Marne", "53": "Mayenne",
    "54": "Meurthe-et-Moselle", "55": "Meuse", "56": "Morbihan", "57": "Moselle", "58": "Nièvre", "59": "Nord",
    "60": "Oise", "61": "Orne", "62": "Pas-de-Calais", "63": "Puy-de-Dôme", "64": "Pyrénées-Atlantiques",
    "65": "Hautes-Pyrénées", "66": "Pyrénées-Orientales", "67": "Bas-Rhin", "68": "Haut-Rhin", "69": "Rhône",
    "70": "Haute-Saône", "71": "Saône-et-Loire", "72": "Sarthe", "73": "Savoie", "74": "Haute-Savoie", "75": "Paris",
    "76": "Seine-Maritime", "77": "Seine-et-Marne", "78": "Yvelines", "79": "Deux-Sèvres", "80": "Somme", "81": "Tarn",
    "82": "Tarn-et-Garonne", "83": "Var", "84": "Vaucluse", "85": "Vendée", "86": "Vienne", "87": "Haute-Vienne",
    "88": "Vosges", "89": "Yonne", "90": "Territoire de Belfort", "91": "Essonne", "92": "Hauts-de-Seine", "93": "Seine-Saint-Denis",
    "94": "Val-de-Marne", "95": "Val-d'Oise", "971": "Guadeloupe", "972": "Martinique", "973": "Guyane", "974": "La Réunion", "976": "Mayotte"
}

def extraire_departements(input_file, output_file):
    with open(input_file, "r", encoding="utf-8") as file:
        data = file.read()

    # Expression régulière corrigée pour capturer les coordonnées
    matches = re.findall(r'---\s*Département\s*dpt-(\d{3}|\d{2}[AB]?)\s*---\s*([0-9,.\- \s]+)', data)

    # Liste pour stocker les résultats
    resultats = []

    for match in matches:
        code_departement = match[0]
        coords = match[1].replace("\n", "").strip()

        # Vérifier si le code du département existe dans le dictionnaire
        nom_departement = departements.get(code_departement, code_departement)  # Utilise le code comme fallback

        # Générer un lien en fonction du nom du département
        lien = nom_departement.lower().replace(" ", "_") + ".html"
        
        # Ajouter à la liste des résultats sous la forme stricte ["lien", "nom", "coords"]
        resultats.append([f'"{lien}"', f'"{nom_departement}"', f'"{coords}"'])

    # Écrire les résultats dans un fichier de sortie
    with open(output_file, "w", encoding="utf-8") as file:
        for ligne in resultats:
            file.write(f"[ {ligne[0]}, {ligne[1]}, {ligne[2]} ]\n")

    print(f"✅ Extraction terminée. Les données ont été enregistrées dans : {output_file}")


# Exemple d'utilisation
input_file = "C:/Devoir/Developpement_WEB/Meteo/pages/pythonExtract/coordonnees_svg_par_departement.txt"  # Chemin vers ton fichier d'entrée
output_file = "C:/Devoir/Developpement_WEB/Meteo/pages/pythonExtract/depart.txt"  # Spécifie ton chemin de sortie

# Extraire les données du fichier d'entrée et enregistrer dans un fichier de sortie
extraire_departements(input_file, output_file)
