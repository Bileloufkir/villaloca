<?php

require_once('inc/init.php');
$title = 'Accueil';

?>

<div class="container-fluid bg d-flex align-items-end flex-column">

  <div class="blocindex prems">
    <h1>Bienvenue sur VillaLoca</h1>
    </div>
    <div class="blocindex">
    <h3>Location de villa 24h/24 et 7j/7 </h3>
    </div>
<!-- formulaire reservation Accueil -->

 <div class="container d-flex picker1 justify-content-center">
    <div class='col-md-5'>
        <div class="form-group">
           <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker7" placeholder="date de début"/>
                <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class='col-md-5'>
        <div class="form-group">

           <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker8" placeholder="date de fin"/>
                <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>
    </div>
</div> 


</div>
<?php
require_once('inc/header.php');
?>

<!-- <div class="row"> -->
  <!-- <div class="col-3"> -->
  <?php
    // Générer les catégories
    $categories = execRequete("SELECT * FROM villa");
    ?>

  <div class="col-12">
      
  <h1 class="titreindex">Nos villa de luxe à la location</h1>

<form class="form-inline">

  <select class="form-control form-control-sm filtreindex " id="inputGroupSelect01" >
    <option selected value="1">Prix Croissant</option>
    <option value="2">Prix Décroissant</option>
</select>
</form>
   <!-- <button type="button" class="btn btn-outline-secondary btn-sm">Validé</button> -->

  <?php
    // Afficher les villas de la boutique
    $whereclause = '';
    $arg = array();
    
    // Eventuel filtre sur la categ

    $villas = execRequete("SELECT * FROM villa $whereclause",$arg);
    
    // natsort($villas);
    ?>
    <div class="row indexcar">
      <?php
          while( $prd = $villas->fetch() ):
            ?>
            <div class="col-6 pt-2">
              <div class="border">
                <div class="thumbnail">
                  <a href="fiche_villa.php?id_villa=<?= $prd['id_villa'] ?>">
                    <img src="<?= URL . 'photo/' . $prd['photo'] ?>" alt="<?= $prd['titre'] ?>" class="img-fluid" style="width:700px;">
                  </a>
                </div>
                <div class="caption mx-2">
                  <h6 class="float-right"><?= $prd['prix_journalier'] ?>€/Jour</h6>
                  <h5><a href="fiche_villa.php?id_villa=<?= $prd['id_villa'] ?>"><?= $prd['titre'] ?></a></h5>
                </div>
              </div>
            </div>
            <?php
          endwhile;
      ?>
    </div>
  </div>


<?php

require_once('inc/footer.php');