<?php
// Projet Réservations M2L - version web mobile
// fichier : vues/VueChangerDeMdp.php
// Rôle : traiter la demande de changement de mot de passe
// Création : 08/11/2016 par Melvin Leveque
// Mise à jour : 08/11/2016 par Melvin Leveque

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauUtilisateur'] != 'utilisateur' && $_SESSION['niveauUtilisateur'] != 'administrateur') {
	// si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
	// dans ce cas, on provoque une redirection vers la page de connexion
	header ("Location: index.php?action=Deconnecter");
}
else {
	if ( ! isset($_POST["NewMdp"])&&! isset ($_POST["ConfMdp"])){
		// si les données n'ont pas été postées, c'est la premier appel du formulaire;
		// on affiche alors la vue sans message d'erreur :
		$nouveauMdp='';
		$confirmationMdp='';
		$afficherMdp='off';
		$message='';
		$typeMessage='';
		include_once('vues/VueChangerDeMdp.php');
	}
	else{
		// récuperation des données postées
		if( empty($_POST["NewMdp"])==true) $nouveauMdp = "";
			else $nouveauMdp=$_POST["NewMdp"];
		if ( empty ($_POST["ConfMdp"])==true) $confirmationMdp = "";
			else $confirmationMdp=$_POST["ConfMdp"];
		if ( empty ($_POST["caseAfficherMdp"])==true) $afficherMdp='off';
		 else $afficherMdp = $_POST["caseAfficherMdp"];
		
		if ( $nouveauMdp == "" || $confirmationMdp =="" ){
			// si les données sont incomplètes, réaffichage de la vue avec un message explicatif
			$message = 'Données incomplètes !';
			$typeMessage = 'avertissement';
			include_once('vues/VueChangerDeMdp.php');
		}
		else{
			if($nouveauMdp != $confirmationMdp =""){
				//si les 2 saisies sont differentes, raffichage de la vue avec un message explicatif
				$message = 'Le nouveau mot de passe et sa confirmation sont différents !';
				$typeMessage='Avertissement';
				include_once('vues/VueChangerDeMdp.php');
			}
			else{
				
				$dao->modifierMdpUser($nom, $nouveauMdp);
				
				if( $ok ){
					$message = "Enregistrement effectué.<br>Vous allez recevoir un mail de confirmation.";
					$typeMessage = "information";
				}
				else{
					$message = "Enregistrement effectué.<br>L'envoi du mail de confirmation a rencontré un problème.";
					$typeMessage = 'avertissement';
				}
				include_once ('vues/VueChangerDeMdp.php');
			}	
		}
	}

	unset($dao);		// fermeture de la connexion à MySQL
}