<?php

require_once('inc/init.php');
$title='Panier';

if(isset($_POST['ajout_panier'])){
   //je sais qu'il s'agit d'un ajout au panier
$resultat = execRequete("SELECT prix FROM produit WHERE id_produit=:id_produit",array('id_produit' => $_POST['id_produit']));
if ($resultat->rowCount()>0){
   $produit = $resultat->fetch();
   addPanier($_POST['id_produit'],$_POST['quantite'],$produit['prix']);
   header('location:'. URL . 'fiche_produit.php?id_produit='. $_POST['id_produit'] . '&statut_produit=ajoute');
   exit();

}

}

//suppression d'une ligne du panier
if (isset($_GET['action']) && $_GET['action']=='sup' && !empty($_GET['id_produit'])){
    removePanier($_GET['id_produit']);
 }

// vider le panier
if(isset($_GET['action']) && $_GET['action']== 'vider'){
    unset($_SESSION['panier']);
}


//valider le panier  (=transformation en commande)

if(isset($_GET['action']) && $_GET['action']== 'valider' && isConnected() ){
    $feu_rouge = false;

    // controle du panier avant commande 
    for($i=0 ; $i < count($_SESSION['panier']['id_produit']); $i++ ){
        $resultat = execRequete("SELECT * FROM produit WHERE id_produit=:id_produit",array('id_produit' => $_SESSION['panier']['id_produit'][$i]));
        $produit = $resultat-> fetch();
        if($_SESSION['panier']['quantite'][$i] > 10 ) $feu_rouge = true ;
        if($produit['stock'] < $_SESSION['panier']['quantite'][$i]) $feu_rouge = true;
        if($produit['prix'] != $_SESSION['panier']['prix'][$i]) $feu_rouge = true;
    }

    if($feu_rouge === false){
        // on procede à la commande 
        $id_membre = $_SESSION['membre']['id_membre'];
        $montant_total = montantPanier();
        execRequete("INSERT INTO commande VALUES (NULL,:id_membre,:montant,NOW(), 'en cours de traitement')",array(
            'id_membre' => $id_membre,
            'montant'=> $montant_total
        ));
        
        $id_commande = $pdo->lastInsertId();

        // on va boucler sur le panier pour alimenter lal table details_commande et mettre a jour le stock 
        for($i=0; $i < count($_SESSION['panier']['id_produit']); $i++){
            $id_produit = $_SESSION['panier']['id_produit'][$i];
            $quantite = $_SESSION['panier']['quantite'][$i];
            $prix = $_SESSION['panier']['prix'][$i];
            // on alimente details_commande

            execRequete("INSERT INTO details_commande VALUES (NULL,:id_commande,:id_produit,:quantite,:prix)",array(
                'id_commande' => $id_commande,
                'id_produit'=> $id_produit,
                'quantite'=> $quantite,
                'prix'=> $prix
            ));

            // on décrémente le stock 
            execRequete("UPDATE produit SET stock = stock - :quantite WHERE id_produit=:id_produit",array(
                'quantite'=> $quantite,
                'id_produit'=> $id_produit
            ));
        }
        // detruire le panier aprés instruction 
        unset($_SESSION['panier']);
        // redirection sur la page 'mes commande '
        header('location:'.URL.'commandes.php');
        exit();
        }

    else{
        $content .= '<div class="alert alert-danger">la commande n\'a pas été validée en raison de modifications concernant le stock ou le prix des articles. Merci de valider à nouveau aprés vérification </div>';
    }
}




require_once('inc/header.php');
echo $content;
// page du panier
?>
<h2>Votre Panier</h2>
<?php
if(empty($_SESSION['panier']['id_produit'])){
    ?>
<div class="alert alert-info">Votre Panier est vide :( </div>
    <?php
}
else{
    ?>
    <table class="table table-bordered table-striped">
    <tr>
    <th>Réference</th>
    <th>Titre</th>
    <th>Quantite</th>
    <th>Prix unitaire</th>
    <th>Total</th>
    <th>Action</th>
    </tr>

 <?php
// Controles et réécriture eventuelle du panier 
    for( $i=0;$i < count($_SESSION['panier']['id_produit']);$i++):
        $resultat = execRequete ("SELECT * FROM produit WHERE id_produit=:id_produit",array('id_produit'=> $_SESSION['panier']['id_produit'][$i]));
        $produit = $resultat->fetch();
        $message = '' ;
        if($_SESSION['panier']['quantite'][$i] > 10){
        $_SESSION['panier']['quantite'][$i] = 10 ;
        }
        if($produit['stock'] < $_SESSION['panier']['quantite'][$i]){
            $_SESSION['panier']['quantite'][$i] = $produit['stock'];
            $message .= '<div class="alert alert-info">La quantite a été réajustée en fonction du stock et dans la limite de 10 artcles pas commande </div>';
        }
        if($_SESSION['panier']['prix'][$i] != $produit['prix']){
            $_SESSION['panier']['prix'][$i] = $produit['prix'];
            $message .= '<div class="alert alert-info">Le prix a été réactualisé</div>';
        }
    ?>
    <tr>
     <td><a href="<?= URL . 'fiche_produit.php?id_produit=' . $_SESSION['panier']['id_produit'][$i] ?>"> <?= $produit['reference'] ?></a></td>
     <td><?=  $produit['titre'] . $message ?></td>
     <td><?=   $_SESSION['panier']['quantite'][$i] ?></td>
     <td><?=   $_SESSION['panier']['prix'][$i] ?></td>
     <td><?=   $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i] ?>€</td>
     <td><a href="?action=sup&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><i class="fa fa-trash"></i></a></td>
    </tr>
        <?php
    endfor;
?>
    <tr class="bg-info">
    <th colspan="4" class="text-right">Total</th>
    <th colspan="2"><?= montantPanier() ?></th>
    </tr>
    <?php
    if(isConnected() ){
        ?>
        <tr>
        <td colspan="6" class="text-center">
            <a href="?action=valider" class="btn btn-primary">Valider le panier</a>
        </td>
        </tr>
        <?php
    }
    else{
        ?>
        <tr>
        <td colspan="6" class="text-center">
            Veuillez vous <a href="<?= URL . 'inscription.php' ?>">inscrire </a> ou vous <a href="<?= URL . 'inscription.php' ?>">connecter</a> afin de valider votre panier 
        </td>
        </tr>
        <?php
    }

    ?>
    <tr>
    <td colspan="6" class="text-center">
        <a href="?action=vider" class="btn btn-warning">Vider le panier</a>
    </td>
    </tr>
    </table>
<?php
}
?>


<?php

require_once('inc/footer.php');