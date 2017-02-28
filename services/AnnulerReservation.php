<?php
// Service web du projet Réservations M2L
// Ecrit le 22/11/16 par Chefdor
// Modifié le 22/11/16 par Chefdor
// Ce service web permet à un utilisateur de supprimer une réservation si il en est l'auteur
// et fournit un flux XML contenant un compte-rendu d'exécution
// Le service web doit recevoir 2 paramètres : nom, mdp, numeroReservation
// Les paramètres peuvent être passés par la méthode GET (pratique pour les tests, mais à éviter en exploitation) :
//     http://<hébergeur>/AnnulerReservation.php?nom=zenelsy&mdp=passe&numreservation=2
// Les paramètres peuvent être passés par la méthode POST (à privilégier en exploitation pour la confidentialité des données) :
//     http://<hébergeur>/AnnulerReservation.php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');
// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
if ( empty ($_GET ["mdp"]) == true)  $mdp = "";  else   $mdp = $_GET ["mdp"];
if ( empty ($_GET ["numreservation"]) == true)  $numreservation = "";  else   $numreservation = $_GET ["numreservation"];
// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $nom == "" && $mdp == "" && $numreservation == "")
{	if ( empty ($_POST ["nom"]) == true)  $nom = "";  else   $nom = $_POST ["nom"];
	if ( empty ($_POST ["mdp"]) == true)  $mdp = "";  else   $mdp = $_POST ["mdp"];
	if ( empty ($_POST ["numreservation"]) == true)  $numreservation = "";  else   $numreservation = $_POST ["numreservation"];
}
// Contrôle de la présence des paramètres
if ( $nom == "" || $mdp == "" || $numreservation == "")
{	
	$msg = "Erreur : données incomplètes.";
}
else
{	// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('../modele/DAO.class.php');
	$dao = new DAO();
	
	if ( $dao->getNiveauUtilisateur($nom, $mdp) == "inconnu" )
	{
		$msg = "Erreur : authentification incorrecte.";
	}
	else
	{
		if ($dao->existeReservation($numreservation) == false)
		{
			$msg = "Erreur : numéro de réservation inexistant.";
		}
		else
		{
			if ($dao->estLeCreateur($nom, $numreservation) == false)
			{
				$msg = "Erreur : vous n'êtes pas l'auteur de cette réservation.";
			}
			else 
			{
				if (($dao->getReservation($numreservation)->getStart_time()) < time())
				{
					$msg = "Erreur : cette réservation est déjà passée.";
				}
				else 
				{
					$dao->annulerReservation($numreservation);
					
					$unUtilisateur = $dao->getUtilisateur($nom);
					$adrMail = $unUtilisateur->getEmail();
					$sujet = "Annulation de votre réservation dans le système de réservation de M2L";
					$contenuMail = "L'administrateur du système de réservations de la M2L vient d'annuler votre réservation.\n\n";
					$contenuMail .= "Les données enregistrées sont :\n\n";
					$contenuMail .= "Votre réservation : " . $numreservation . "\n";
				
					$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
					if ( ! $ok ) 
					{
						$msg = "Réservation annulée ; l'envoi du mail d'annulation de la réservation a rencontré un problème.";
					}
					else 
					{
						$msg = "Réservation annulée ; vous allez recevoir un mail de confirmation.";
					}
				}
			}
		}
	}
	// ferme la connexion à MySQL
	unset($dao);
}
// création du flux XML en sortie
creerFluxXML ($msg);
// fin du programme (pour ne pas enchainer sur la fonction qui suit)
exit;
// création du flux XML en sortie
function creerFluxXML($msg)
{	// crée une instance de DOMdocument (DOM : Document Object Model)
	$doc = new DOMDocument();	
	// specifie la version et le type d'encodage
	$doc->version = '1.0';
	$doc->encoding = 'ISO-8859-1';
	
	// crée un commentaire et l'encode en ISO
	$elt_commentaire = $doc->createComment('Service web AnnulerReservation - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
		
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' juste après l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);
	
	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	echo $doc->saveXML();
	return;
}
?>