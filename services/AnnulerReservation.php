<?php
// Service web du projet Réservations M2L
// Ecrit le 06/12/2016 par Melvin
// Ecrit le 06/12/2016 par Melvin
// Ce service web permet à un administrateur authentifié d'annuler une reservation
// et fournit un compte-rendu d'exécution
// Le service web doit être appelé avec 2 paramètres : name eet mdp
// Les paramètres peuvent être passés par la méthode GET (pratique pour les tests, mais à éviter en exploitation) :
//     http://<hébergeur>/services/annulerReservation.php?nom=zenelsy&mdp=passe
// Les paramètres peuvent être passés par la méthode POST (à privilégier en exploitation pour la confidentialité des données) :
//     http://<hébergeur>/services/annulerReservation.php
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

// Contrôle de la présence des paramètres
if ( $nom == "" || $mdp == "" )
{	$msg = "Erreur : données incomplètes.";
}
else {
	// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('../modele/DAO.class.php');
	$dao = new DAO();
	if ( $nouveauMdp != $confirmationMdp ) {
		$msg = "Le nouveau mot de passe et sa confirmation sont différents !";
	}
	else {
		$unUtilisateur = $dao->getUtilisateur($name);
		$password = $unUtilisateur->getPassword();
		// utilisation de la fonction md5($mdp) parce que l'ancien mot de passe est stockée en md5 dans la base de données
		if ( md5($ancienMdp) != $password ) {
			$msg = "Erreur : authentification incorrecte.";
		}
		else{
			// modification du mot de passe
			$password = $nouveauMdp;
			$ok = $dao->modifierMdpUser($name, $password);
			if ( ! $ok ) {
				$msg = "Erreur : problème lors de la modification du mot de passe.";
			}
			else {
				// envoi d'un mail avec le nouveau mot de passe
				$adrMail = $unUtilisateur->getEmail();
				$level = $dao->getNiveauUtilisateur($name, $password);

				$sujet = "Votre nouveau mot de passe";
				$contenuMail = "Voici vos données utilisateur, ainsi que votre nouveau mot de passe.\n\n";
				$contenuMail .= "Les données enregistrées sont :\n\n";
				$contenuMail .= "Votre nom : " . $name . "\n";
				$contenuMail .= "Votre nouveau mot de passe : " . $password . " (nous vous conseillons de le changer lors de la première connexion)\n";
				$contenuMail .= "Votre niveau d'accès : " . $level . "\n";
					
				$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
				if ( ! $ok ) {
					// l'envoi de mail a échoué
					$msg = "Modification du mot de passe effectué ; l'envoi du mail à l'utilisateur a rencontré un problème.";
				}
				else {
					// tout a bien fonctionné
					$msg = "Modification du mot de passe effectué ; Vous allez recevoir un mail avec votre nouveau mot de passe.";
				}
			}
		}
	}
	// ferme la connexion à MySQL :
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
$elt_commentaire = $doc->createComment('Service web CreerUtilisateur - BTS SIO - Lycée De La Salle - Rennes');
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