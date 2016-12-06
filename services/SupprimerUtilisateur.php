<?php
// Service web du projet Réservations M2L
// Ecrit le 29/11/2016 par Melvin
// Ecrit le 29/11/2016 par Melvin
// Ce service web permet à un administrateur authentifié de supprimer un nouvel utilisateur
// et fournit un compte-rendu d'exécution
// Le service web doit être appelé avec 3 paramètres : nomAdmin, mdpAdmin, name
// Les paramètres peuvent être passés par la méthode GET (pratique pour les tests, mais à éviter en exploitation) :
//     http://<hébergeur>/SupprimerUtilisateur.php?nomAdmin=admin&mdpAdmin=admin&name=test
// Les paramètres peuvent être passés par la méthode POST (à privilégier en exploitation pour la confidentialité des données) :
//     http://<hébergeur>/SupprimerUtilisateur.php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');
// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["nomAdmin"]) == true)  $nomAdmin = "";  else   $nomAdmin = $_GET ["nomAdmin"];
if ( empty ($_GET ["mdpAdmin"]) == true)  $mdpAdmin = "";  else   $mdpAdmin = $_GET ["mdpAdmin"];
if ( empty ($_GET ["name"]) == true)  $name = "";  else   $name = $_GET ["name"];
// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $nomAdmin == "" && $mdpAdmin == "" && $name == "") {
	if ( empty ($_POST ["nomAdmin"]) == true)  $nomAdmin = "";  else   $nomAdmin = $_POST ["nomAdmin"];
	if ( empty ($_POST ["mdpAdmin"]) == true)  $mdpAdmin = "";  else   $mdpAdmin = $_POST ["mdpAdmin"];
	if ( empty ($_POST ["name"]) == true)  $name = "";  else   $name = $_POST ["name"];
}
// Contrôle de la présence des paramètres
if ( $nomAdmin == "" || $mdpAdmin == "" || $name == "") {
	$msg = "Erreur : données incomplètes ou incorrectes.";
}
else {
	// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('../modele/DAO.class.php');
	$dao = new DAO();
	if ( $dao->getNiveauUtilisateur($nomAdmin, $mdpAdmin) != "administrateur" ) {
		$msg = "Erreur : authentification incorrecte.";
	}
	else {	// ! ou == false
		if ( ! $dao->existeUtilisateur($name) ) {
			$msg = "Erreur : nom d'utilisateur inexistant.";
		}
		else {
			if ( $dao->aPasseDesReservations($name) ) {
				$msg = "Erreur : cet utilisateur a passé des réservations à venir.";
			}
			else {
				$unUtilisateur = $dao->getUtilisateur($name);
				$adrMail = $unUtilisateur->getEmail();

				$ok = $dao->supprimerUtilisateur($name);

				if ( !$ok ){
					$msg = "Problème lors de la suppression de l'utilisateur !";
				}
				else {
					// envoi d'un mail de confirmation de la suppression
					$sujet = "Suppression de votre compte dans le système de réservation de M2L";
					$contenuMail = "L'administrateur du système de réservations de la M2L vient de supprimer votre compte utilisateur : " . $name . ".\n\n";
						
					$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
					if ( ! $ok ) {
						// l'envoi de mail a échoué
						$msg = "Suppression effectué ; l'envoi du mail à l'utilisateur a rencontré un problème.";
					}
					else {
						// tout a bien fonctionné
						$msg = "Suppression effectué ; un mail va être envoyé à l'utilisateur.";
					}
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