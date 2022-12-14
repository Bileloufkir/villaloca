<?php
// l'existence du tableau membre dans la session indique que l'utilisateur s'est correctement connecté
function isConnected(){
  if ( isset($_SESSION['membre']) ){
    return true;
  }
  else{
    return false;
  }
}

// un admin est un membre connecté dont le statut vaut 1
function isAdmin(){
  if( isConnected() && $_SESSION['membre']['statut'] == 1){
    return true;
  }
  else{
    return false;
  }
}

function execRequete($req,$params=array()){
  global $pdo;
  $r = $pdo->prepare($req);
  if ( !empty($params) ){
    // sanatize et bindvalue
    foreach($params as $key => $value){
      $params[$key] = htmlspecialchars($value,ENT_QUOTES);
      $r->bindValue($key,$params[$key],PDO::PARAM_STR);
    }    
  }
  $r->execute();
  if ( !empty( $r->errorInfo()[2] )){
    die('Erreur rencontrée - merci de contacter l\'administrateur');
  }
  return $r;
}

// $villa = execRequete("SELECT * FROM villa WHERE id_villa=:id_villa", array('id_villa'=> 485));
// $membres = execRequete("SELECT * FROM membre");

// Fonctions liées au Panier

function createPanier(){
  if( !isset($_SESSION['panier']) ){
    $_SESSION['panier'] = array();
    $_SESSION['panier']['id_villa'] = array();
    $_SESSION['panier']['quantite'] = array();
    $_SESSION['panier']['prix'] = array();
  }
}

function addPanier($id_villa,$quantite,$prix){
  createPanier();

  $position_villa = array_search($id_villa,$_SESSION['panier']['id_villa']);
  if ( $position_villa === false ){
    // nouveau villa
    $_SESSION['panier']['id_villa'][] = $id_villa;
    $_SESSION['panier']['quantite'][] = $quantite;
    $_SESSION['panier']['prix'][] = $prix;
  }
  else{
    // villa présent dans le panier
    $_SESSION['panier']['quantite'][$position_villa] += $quantite;
  }
}

function removePanier($id_villa){
  $position_villa = array_search($id_villa,$_SESSION['panier']['id_villa']);
  if ( $position_villa !== false){

    array_splice($_SESSION['panier']['id_villa'],$position_villa,1);
    array_splice($_SESSION['panier']['quantite'],$position_villa,1);
    array_splice($_SESSION['panier']['prix'],$position_villa,1);

  }
}

function montantPanier(){
  $total = 0;
  for($i=0; $i < count($_SESSION['panier']['id_villa']); $i++)
  {
    $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
  }
  return $total;
}

function nbArticles(){
  $nb='';
  if( !empty($_SESSION['panier']['id_villa'])){
    $nb = '<span class="badge badge-primary">' .array_sum($_SESSION['panier']['quantite']) . '</span>';
  }
  return $nb;
}
