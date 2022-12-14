<?php

require_once('../inc/init.php');
$title='Gestion des villas';

// Controle des autorisations
if(!isAdmin()){
    header('location:'. URL . 'connexion.php');
    exit();
}

// 5 - Suppression d'une villa 
if( isset($_GET['action']) && $_GET['action']=='sup' && !empty($_GET['id'])){
    // Je vais chercher la photo de la villa
    $resultat = execRequete("SELECT photo FROM villa WHERE id_villa=:id",array('id' => $_GET['id']));
    // si je trouve la villa 
    if( $resultat->rowCount() > 0 ){
        $villa = $resultat->fetch();
        // si le champ photo est renseigné
        if(!empty($villa['photo'])){
            $fichier = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/' . $villa['photo'];
            if(file_exists($fichier)){
                // suppression de la photo
                unlink($fichier);
            }
        }
    }
    // Supression en BDD
    execRequete("DELETE FROM villa WHERE id_villa=:id",array('id' => $_GET['id']));
    $content .= '<div class="alert alert-success">Le villa a été supprimé</div>';
    $_GET['action']= 'affichage';
}


// 3- enregistrement d'une villa en BDD (ajout et en modif)
if(!empty($_POST) ){

    // contrôles 
    $nb_champs_vides = 0;
    foreach($_POST as $value){
        if($value == '') $nb_champs_vides++;
    }
    if($nb_champs_vides > 0){
        $content .= '<div class="alert alert-danger">Merci de remplir les ' .$nb_champs_vides. ' information(s) manquante(s)</div>';
    }

    // gerer la photo 
    $photo_bdd = $_POST['photo_courante'] ?? '';


    if(!empty($_FILES['photo']['name'])){
        $photo_bdd = $_POST['titre']. '_' . $_FILES['photo']['name'];
        $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL .'photo/';
        $ext_auto = ['image/jpeg','image/png','image/gif'];

        if(in_array($_FILES['photo']['type'], $ext_auto)){
            move_uploaded_file($_FILES['photo']['tmp_name'],$dossier_photo.$photo_bdd);
        }
        else{
            $content .= '<div class="alert alert-danger"> La photo n\a pas été enregistrée. Format acceptés : jpeg, png, gif </div>';
        } 
    }
   if(empty($content)){
       extract($_POST);
       if($id_villa== 0){
       execRequete("INSERT INTO villa VALUES (NULL,:id_agence,:titre,:marque,:modele,:description,:photo,:prix_journalier)",array(
        'id_agence' => $id_agence,
        'titre' => $titre,
        'marque' => $marque,
        'modele' => $modele,
        'description' => $description,
        'photo' => $photo_bdd,
        'prix_journalier' => $prix_journalier
       ));
       $content .= '<div class="alert alert-success"> La villa a été enregistré </div>';
       $_GET['action'] = 'affichage';
    }
    else{
        execRequete("UPDATE villa SET titre=:titre,marque=:marque,modele=:modele,description=:description,photo=:photo,prix_journalier=:prix_journalier WHERE id_villa=:id_villa",array(
            'id_villa' => $id_villa,
            'titre' => $titre,
            'marque' => $marque,
            'modele' => $modele,
            'description' => $description,
            'photo' => $photo_bdd,
            'prix_journalier' => $prix_journalier
           ));
           $content .= '<div class="alert alert-success"> La villa a été mis à jour </div>';
    }
       $_GET['action'] = 'affichage';
   } 
}

require_once('../inc/header.php');
echo $content;
// page gestion des villas

// 1- Onglets pour affichage / ajout-modif villa
?>
<!--
<ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
  //  <a class="nav-link < ?= ( (!isset($_GET['action']) || (isset($_GET//['action']) && $_GET['action'] == 'affichage')) ? 'active': '' )?>" href="?action=affichage">Affichage des villas</a>
    </li>
    <li class="nav-item">
 //   <a class="nav-link < ?= ( (!isset($_GET['action']) || (isset($_GET//['action']) && $_GET['action'] == 'ajout' || $_GET['action']== 'edit')) ? 'active': '' )?>" href="?action=ajout">Ajouter un villa</a>
    </li>
</ul>

<?php
// 4. Affichage des villa en BDD


//if(!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage')){

//    $resultat = execRequete("SELECT * FROM villa");

//    if($resultat->rowCount() == 0){
        ?>
        <div class="alert alert-warning">Il n'ya pas encore de villas enregistrés</div>
        
        <?php
 //   }
//    else{
        ?>
        <p> Il y a < ?= $resultat->rowCount() ?> villa(s) dans la boutique</p>
                <?php

        // Générer les catégories
    ?>
-->
    <!-- filtre agence villa -->

    <ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
    <a class="nav-link <?= ( (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage')) ? 'active': '' )?>" href="?action=affichage">Affichage des villas</a>
    </li>
    <li class="nav-item">
    <a class="nav-link <?= ( (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'ajout' || $_GET['action']== 'edit')) ? 'active': '' )?>" href="?action=ajout">Ajouter une villa</a>
    </li>
</ul>

    <?php
$agences = execRequete ("
      SELECT id_agence, ville 
      FROM agence 
      ");
  
if ( !isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage') ) {
     
  if (isset($_GET['ville'])) {

    if ( $_GET['ville'] =='all' )  {
      $whereclause = '';
    }else{

      $ville = $_GET['ville'];
      $whereclause = "AND a.ville = '$ville'";

      }
    }
  else{
    $whereclause = '';
  }
  
  $resultat = execRequete(
  
    "SELECT v.id_villa,a.ville,v.titre,v.marque,v.modele,v.description,v.photo,v.prix_journalier
     FROM
     villa v, agence a 
     WHERE v.id_agence = a.id_agence
     $whereclause
     "

     );


   if ( $resultat->rowCount() == 0 ){
    ?>
    <div class="alert alert-warning">Il n'y a pas encore de villas enregistrés</div>
    <?php
  }
  else{


    ?>
    <h5>Gestion des véhicules</h5>
    <p>Nous avons <?= $resultat->rowCount() ?> villa(s) <?= (empty($ville)) ? '' : "à " . $ville ?></p>

   
<div class="input-group mb-3">
    <form action="" method="get" class="d-flex w-25">  
    
    <select class="custom-select mr-2" name="ville" id="ville" onChange ="this.form.submit();-->"  >
            <option value="all" >Agences</option>
           <?php
           while($agence = $agences->fetch()){       
          ?>

            <option value="<?= $agence['ville']?>"><?= $agence['ville']?></option>
   
          <?php 
           }
        ?>

    </select>
      <div class="input-group-append form-group">      
        
        <input type="submit" class="btn btn-dark input-group-btn " name="selectVille"  value="Selectionner">
      </div>
     </form> 
      </div>
     <!-- fin de filtre agence villa  -->

<table class="table table-bordered table-striped">
<tr>

    <?php
    for($i=0;$i<$resultat->columnCount();$i++){

        $colonne = $resultat->getColumnMeta($i);

    ?>
    <th><?= ucfirst($colonne['name']) ?></th>
<?php
    }
    ?>
    <th colspan="3">Actions</th>
</tr>
<?php
// donnée de la table villa 
while($ligne = $resultat->fetch()){
    ?>
    <tr>
    <?php 
    foreach($ligne as $key => $value){
        if($key == 'photo'){
            $value = '<img class="img-fluid" src="'.URL.'photo/'.$value.'" alt="'.$ligne['titre'].'">';
        }
        ?>
        <td><?= $value ?></td>
        <?php
    }
    ?>
    <td><a href="?action=edit&id=<?= $ligne['id_villa'] ?>"><i class="fas fa-pen"></i></a></td>
    <td><a class="confirm"  href="?action=sup&id=<?= $ligne['id_villa']?>"><i class="fas fa-trash"></i></a></td>

    </tr>
    <?php
}
?>
</table>
<?php
}
}



// 2. formulaire ajout/modif de villa

if(isset($_GET['action']) && ($_GET['action']=='ajout'|| $_GET['action']=='edit') ):

// 6 - cahrgement d'une villa en edition 
if(!empty($_GET['id'])){
    $resultat = execRequete("SELECT * FROM villa WHERE id_villa=:id",array('id' => $_GET['id']));
    $villa_courant = $resultat->fetch();
}

?>
<form method="post" action="" enctype="multipart/form-data">
   <input type="hidden" name="id_villa" value="<?= $_POST['id_villa'] ?? $villa_courant['id_villa'] ?? 0 ?>">

    <div class="form-row">
       <div class="form-goup col-6">
        <label for="titre">Titre</label>
     <input type="text" name="titre" id="titre" class="form-control" value="<?= $_POST['titre'] ?? $villa_courant['titre'] ?? '' ?>">
     </div>

    <div class="form-goup col-6"> 
    <label for="id_agence">Agence</label>
    <select name="id_agence" class="custom-select" id="inputGroupSelect01" value="<?= $_POST['id_agence'] ?? $villa_courant['id_agence'] ?? '' ?>"> 
    <option  id="id_agence">1</option>
    <option  id="id_agence">2</option>
    <option  id="id_agence">3</option>
  </select>
  </div>

</div>
</div>
<div class="form-row">
    <div class="form-goup col-6">
        <label for="marque">Marque</label>
        <input type="marque" name="marque" id="marque" class="form-control" value="<?= $_POST['marque'] ?? $villa_courant['marque'] ?? '' ?>">
    </div>
    <div class="form-goup col-6">
        <label for="modele">Modele</label>
        <input type="modele" name="modele" id="modele" class="form-control" value="<?= $_POST['modele'] ?? $villa_courant['modele'] ?? '' ?>">
    </div>
</div>

    <div class="form-goup">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control"><?= $_POST['description'] ?? $villa_courant['description'] ?? '' ?></textarea>
    </div>
    <br>
       <div class="form-group">
           <label for="photo"><i class="fa fa-camera"></i> <span id="fichier"></span>
        </label>
           <input type="file" name="photo" id="photo" class="form-control">
           <?php
           if(!empty($villa_courant['photo'])){
               ?>
               <em>Vous pouvez uploader une nouvelle photo</em>
               <img src="<?= URL . 'photo/' . $villa_courant['photo'] ?>" class="img-fluid w-25" alt="<?= $villa_courant['titre'] ?>">
               <input type="hidden" name="photo_courante" value="<?= $villa_courant['photo'] ?>">
             <?php
           }
           ?>
       </div>
       <div class="form-row">
           <div class="form-group col-1">
               <label for="prix_journalier">Prix</label>
               <input type="number" name="prix_journalier" id="prix_journalier" class="form-control" value="<?= $_POST['prix_journalier'] ?? $villa_courant['prix_journalier'] ?? '' ?>">
           </div>
       </div>
       <input type="submit" class="btn btn-primary" value="Enrengistrer">
</form>


<?php

endif;

require_once('../inc/footer.php');
