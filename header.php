<?php
session_start();
try{
  $bdd = new PDO('mysql:host=localhost;dbname=base_de_donnee_projet_php;charset=utf8', 'root', '');
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(Exception $e){
  die('Erreur : '.$e->getMessage());
}
?>