<?php
// Projet Réservations M2L - version web mobile
// fichier : vues/VueChangerDeMdp.php
// Rôle : entrer les données de chagement de mot de passe
// cette vue est appelée par le contôleur controleurs/CtrlChangerDeMdp.php
// Création : 08/11/2016 par Melvin Leveque
// Mise à jour : 08/11/2016 par Melvin Leveque
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
			// associe une fonction à l'événement pageinit
			$(document).bind('pageinit', function() {
				<?php if ($typeMessage != '') { ?>
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				<?php } ?>
			} );
		</script>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4>M2L-GRR</h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Changer de mot de passe</h4>
				<form action="index.php?action=ChangerDeMdp" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="Mdp">Nouveau mot de passe* :</label>
						<input type="password" name="NewMdp" id="NewMdp" required placeholder="Entrer votre nouveau mot de passe">
					</div>
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="NewMdp">Confirmation* :</label>
						<input type="password" name="ConfMdp" id="ConfMdp" required placeholder="Confirmer votre nouveau mot de passe">
					</div>
					<p>
						<label for="caseAfficherMdp">Afficher en clair :</label>
						<input type="checkbox" id="caseAfficherMdp" name="caseAfficherMdp"/>
					</p>
					<div data-role="fieldcontain">
						<input type="submit" name="btnChangerDeMdp" id="btnChangerDeMdp" value="Changer mon mot de passe" data-mini="true">
					</div>
				</form>

				<?php if($debug == true) {
					// en mise au point, on peut afficher certaines variables dans la page
					echo "<p>name = " . $name . "</p>";
					echo "<p>adrMail = " . $adrMail . "</p>";
					echo "<p>level = " . $level . "</p>";
				} ?>
				
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4>Suivi des réservations de salles<br>Maison des ligues de Lorraine (M2L)</h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>