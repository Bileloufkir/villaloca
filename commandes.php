<?php

require_once('inc/init.php');
$title="Mes commandes";
require_once('inc/header.php');

// page des commandes individuelles 
$commandes = execRequete("SELECT *, c.id_commande as numero, d.prix as prixU FROM commande c, details_commande d, produit p WHERE d.id_commande = c.id_commande AND d.id_produit = p.id_produit AND c.id_membre = :id_membre ORDER BY c.date_enregistrement DESC",array(
		'id_membre' => $_SESSION['membre']['id_membre']
));


if ( $commandes->rowCount() > 0 ){
	?>
	<table class="table table-bordered table-striped">
		<?php 

		$lastcmd = 0;
		while ( $cmd = $commandes->fetch() ): 
		
		// 1 ligne d'entete
			if ( $cmd['numero'] != $lastcmd ){
				$datecmd = new DateTime($cmd['date_enregistrement']);
				?>
				<tr>
					<th>Commande n°<?= $cmd['numero'] ?></th>
					<th colspan="2">Date : <?= $datecmd->format('d/m/Y à H:i:s') ?></th>
					<th colspan="3">Etat : <?= $cmd['etat'] ?></th>
					<th colspan="3">Montant : <?= $cmd['montant'] ?>€</th>
				</tr>
				<?php
			}
		// details de la commande
			?>
			<tr>
				<td><?= $cmd['reference'] ?></td>
				<td><?= $cmd['titre'] ?></td>
				<td>Taille : <?= $cmd['taille'] ?></td>
				<td>Catégorie : <?= $cmd['categorie'] ?></td>
				<td>Public : <?= $cmd['public'] ?></td>
				<td><img src="<?= URL . 'photo/' . $cmd['photo'] ?>" alt="<?= $cmd['titre'] ?>" class="img-fluid"></td>
				<td><?= $cmd['prixU'] ?>€</td>
				<td><?= $cmd['quantite'] ?></td>
				<td><?= $cmd['reference'] ?></td>
				<td><?= $cmd['prixU'] * $cmd['quantite'] ?>€</td>
			</tr>
			<?php
			$lastcmd = $cmd['numero'];
		endwhile;
		?>
	</table>
	<?php
}else{
	?>
	<div class="alert alert-info">Vous n'avez pas encore passé de commande</div>div>
	<?php
}
require_once('inc/footer.php');