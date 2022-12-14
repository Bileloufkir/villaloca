<?php
require_once('inc/init.php');



if( empty($_GET['id_villa']) ){
    header('location:' .URL);
    exit();
}

$resultat = execRequete("SELECT * FROM villa WHERE id_villa=:id_villa",array('id_villa' => $_GET['id_villa']));
if( $resultat->rowCount() == 0){
    header('location:' . URL);
    exit();
}

$villa = $resultat->fetch();
$title= $villa['titre'];
require_once('inc/header.php');
?>
<div class="row">
<div class="col">
<h1 class="page-header text-center"><?= $villa['titre']?></h1>
<div class="row">
<div class="col-8">
<img class="img-fluid" src="<?= URL . 'photo/' . $villa['photo'] ?>" alt="<?= $villa['titre']?>">
</div>
<div class="col-4">
<h3>Déscription</h3>
<p><?= $villa['description']?></p>
<h3>Détail</h3>
<ul>
<li>Type : <?= $villa['marque'] ?></li>
<li>Ville : <?= $villa['modele'] ?></li>
</ul><br>
<p class="lead">Prix : <?= $villa['prix_journalier']?> €/jour</p>
<?php

// ------- REQUETE POUR RESERVATION A EFFECTUER 

    ?>

    <form action="panier.php" method="post">
    <input type="hidden" name="id_villa" value="<?= $villa['id_villa'] ?>">
    <div class="form-row">
    <div class="form-group col-4">
    <select name="quantite" class="form-control">

    </select>
    </div>
    <div class="form-group col-4">
    <input type="submit" name="ajout_panier" value="Reservez" class="btn btn-primary">
    </div>
    </div>

    </form>

    </div>
    </div>
    </div>
    </div>

<?php
if(isset($_GET['statut_villa']) && $_GET['statut_villa'] == 'ajoute'):
?>
<div class="modal fade" id="maModale" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">La villa a été reservez</h4>
            </div>
            <div class="modal-body">
            <a href="<?= URL . 'panier.php' ?>" class="btn btn-primary">Voir mes reservation</a>
            <a href="<?= URL ?>" class="btn btn-primary">Continuer ses achats</a>
            </div>
        </div>
    </div>
</div>

<?php

endif;

require_once('inc/footer.php');