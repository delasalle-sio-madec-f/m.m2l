<?php
// Projet Réservations M2L - version web mobile
// fichier : vues/VueAnnulerReservations.php
// Rôle : supprimer une réservation en entrant le numéro de la réservation	// cette vue est appelée par le contôleur controleurs/CtrlAnnulerReservation.php
// Création : 08/11/2016 par Chefdor
// Mise à jour : 08/11/2016 par Chefdor
	
// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauUtilisateur'] != 'utilisateur' && $_SESSION['niveauUtilisateur'] != 'administrateur') {
	// si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
	// dans ce cas, on provoque une redirection vers la page de connexion
	header ("Location: index.php?action=Deconnecter");
}
else {
	// connexion du serveur web à la base MySQL
	
	if ( ! isset ($_POST ["txtAnnulerReservation"])) {
		// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
		$numeroReservation = '';
		$message = '';
		$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
		$themeFooter = $themeNormal;
		include_once ('vues/VueAnnulerReservation.php');
	}
	else {
		// récupération des données postées
		if ( empty ($_POST ["txtAnnulerReservation"]) == true)  $numeroReservation = "";  else   $numeroReservation = $_POST ["txtAnnulerReservation"];
		
		if ($numeroReservation == '') {
			// si les données sont incomplètes, réaffichage de la vue avec un message explicatif
			$message = 'Données incomplètes ou incorrectes !';
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueAnnulerReservation.php');
		}
		else {
			// connexion du serveur web à la base MySQL
			include_once ('modele/DAO.class.php');
			$dao = new DAO();
			
			// test de la réservation de l'utilisateur
			$_SESSION['nom'] = $nom;
			// la méthode estLeCreateur de la classe DAO retourne les valeurs true ou false celon si la réservation correspond ou non à l'utilisateur
			if ($dao->estLeCreateur($nom, $numeroReservation) == false){
				$message = 'Ce numéro de réservation ne correspond pas à une de vos réservation !';
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueAnnulerReservation.php');
			}
			else{
				$dao->annulerReservation($numeroReservation);	
				echo "La réservation n°$numeroReservation à bien été annulé";
			}
	
			
			unset($dao);		// fermeture de la connexion à MySQL
		}
	}
	
	unset($dao);		// fermeture de la connexion à MySQL
}

?>