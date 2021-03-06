<?php
// Service web du projet Réservations M2L
// Ecrit le 29/11/2016 par Florian
// Modifié le 29/11/2016 par Florian
// Ce service web permet à un utilisateur de consulter ses réservations à venir
// et fournit un flux XML contenant un compte-rendu d'exécution
// Le service web doit recevoir 2 paramètres : nom, mdp
// Les paramètres peuvent être passés par la méthode GET (pratique pour les tests, mais à éviter en exploitation) :
//     http://<hébergeur>/ConsulterSalles.php?nom=zenelsy&mdp=passe
// Les paramètres peuvent être passés par la méthode POST (à privilégier en exploitation pour la confidentialité des données) :
//     http://<hébergeur>/ConsulterSalles.php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');
	
// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
if ( empty ($_GET ["mdp"]) == true)  $mdp = "";  else   $mdp = $_GET ["mdp"];
// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $nom == "" && $mdp == "" )
{	if ( empty ($_POST ["nom"]) == true)  $nom = "";  else   $nom = $_POST ["nom"];
	if ( empty ($_POST ["mdp"]) == true)  $mdp = "";  else   $mdp = $_POST ["mdp"];
}
// initialisation du nombre de réservations
$nbReponses = 0;
$lesSalles = array();
// Contrôle de la présence des paramètres
if ( $nom == "" || $mdp == "" )
{	$msg = "Erreur : données incomplètes.";
}
else
{	// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('../modele/DAO.class.php');
	$dao = new DAO();
	
	if ( $dao->getNiveauUtilisateur($nom, $mdp) == "inconnu" )
		$msg = "Erreur : authentification incorrecte.";
	else 
	{		
		// récupération des réservations à venir créées par l'utilisateur
		$lesSalles = $dao->getLesSalles();
		$nbReponses = sizeof($lesSalles);
	
		if ($nbReponses == 0)
			$msg = "Erreur : vous n'avez aucune salle.";
		else
			$msg = $nbReponses . " salles disponibles en réservation";
	}
	// ferme la connexion à MySQL
	unset($dao);
}
// création du flux XML en sortie
creerFluxXML ($msg, $lesSalles);
// fin du programme (pour ne pas enchainer sur la fonction qui suit)
exit;
 
// création du flux XML en sortie
function creerFluxXML($msg, $lesSalles)
{	// crée une instance de DOMdocument (DOM : Document Object Model)
	$doc = new DOMDocument();
	
	// specifie la version et le type d'encodage
	$doc->version = '1.0';
	$doc->encoding = 'ISO-8859-1';
	
	// crée un commentaire et l'encode en ISO
	$elt_commentaire = $doc->createComment('Service web ConsulterSalles - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
	
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' dans l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);
	
	// place l'élément 'donnees' dans l'élément 'data'
	$elt_donnees = $doc->createElement('donnees');
	$elt_data->appendChild($elt_donnees);
	
	// traitement des réservations
	if (sizeof($lesSalles) > 0) {
		foreach ($lesSalles as $uneSalle)
		{
			// crée un élément vide 'reservation'
			$elt_salle = $doc->createElement('salle');
			// place l'élément 'reservation' dans l'élément 'donnees'
			$elt_donnees->appendChild($elt_salle);
		
			// crée les éléments enfants de l'élément 'reservation'
			$elt_id         = $doc->createElement('id', $uneSalle->getId());
			$elt_salle->appendChild($elt_id);
			$elt_room_name  = $doc->createElement('room_name', $uneSalle->getRoom_name());
			$elt_salle->appendChild($elt_room_name);
			$elt_capacity = $doc->createElement('capcity', $uneSalle->getCapacity());
			$elt_salle->appendChild($elt_capacity);
			$elt_area_name  = $doc->createElement('area_name', $uneSalle->getArea_name());
			$elt_salle->appendChild($elt_area_name);
		}
	}
	
	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	echo $doc->saveXML();
	return;
}
?>
