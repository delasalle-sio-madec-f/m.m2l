<?php
	// Projet Réservations M2L - version web mobile
	// fichier : vues/VueAnnulerReservations.php
	// Rôle : supprimer une réservation en entrant le numéro de la réservation
	// cette vue est appelée par le contôleur controleurs/CtrlAnnulerReservation.php
	// Création : 08/11/2016 par Chefdor
	// Mise à jour : 08/11/2016 par Chefdor
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
	</head>
	 
	<body>
		<div data-role="page">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4>M2L-GRR</h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Annuler une réservation</h4>
				<form action="index.php?action=AnnulerReservation" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-hide-label">
						<input type="text" name="txtAnnulerReservation" id="txtAnnulerReservation" required placeholder="Entrez le numéro de réservation à annuler">
					</div>
					<div data-role="fieldcontain">
						<input type="submit" name="btnAnnulerReservation" id="btnAnnulerReservation" value="Annuler la réservation" data-mini="true">
					</div>
				</form>
			</div>
		</div>
	</body>
</html>