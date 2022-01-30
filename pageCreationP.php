<?php
  include_once("header.php");
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Acceuil Art de Bibliothèque</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/checkout/">
    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/starter-template/">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
  </head>


<!--- ************************************************************************************-->


  <body>
    
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="pageAdm.php">Acceuil</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault"> <!--Navbar-->

        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="pageProfil.php">Mon Profil</a>
          </li>
          <?php
            if($_SESSION['role']=="admin")
            echo('<li class="nav-item"><a class="nav-link" href="pageCreationP.php">Creation Utilisateur</a></li>')
          ?>
          
          <li class="nav-item">
            <a class="nav-link" href="pageDec.php">Déconnexion</a>
          </li>
        </ul>

        


        
      </div>
    </nav>

    <main role="main" class="container">

      <div class="starter-template">
        <div class="row">  

          <div class="col-md-6">
            <h4 class="mb-3">Creation de Compte</h4>
            <?php
              $_SESSION["tampon"]=0;
              $_SESSION["tamponLivre"]=0;
              if(isset($_POST["pseudo"])&&isset($_POST["email"])&&isset($_POST["pass"])&&isset($_POST["pass_repeat"])){//Vérifie si toute les informations ont été entré dans le formulaire
                $erreur=0;
                
               
                
                $result = $bdd->prepare('SELECT * 
                                            FROM utilisateur
                                            WHERE uti_adress_mail=:email OR uti_pseudo=:pseudo');
                $result -> execute(array('email' => $_POST["email"],'pseudo' => $_POST["pseudo"]));
                $uti = $result->fetchAll();
                  
                  

                foreach ($uti as $user){
                  if($user['uti_pseudo']==$_POST["pseudo"]){//Vérifie si le pseudo existe déjà
                      $erreur=1;
                      echo ('<b><FONT color="red">Erreur ce pseudo est déjà pris</FONT></b>'.'<br>');    
                  }
                  if($user['uti_adress_mail']==$_POST["email"]){//Vérifie si l'email' existe déjà

                      $erreur=1;
                      echo ('<b><FONT color="red">Erreur cette adress Email est déjà prise</FONT></b>'.'<br>');
                  }
                }
                
                if($_POST["pass"]!=$_POST["pass_repeat"]){//Vérifie si les 2 mot de passe rentré sont identique
                      
                    $erreur=1;
                    echo ('<b><FONT color="red">Erreur le mot de passe n est pas vérifier</FONT></b>'.'<br>');
                }
                    
                      
                if($erreur==0){ //si toutes les informations sont bien rentré et qu'il n'y a aucune erreur, on peut donc inserer les données dans la base de donnée
                    $result = $bdd->prepare('INSERT INTO utilisateur(uti_pseudo,uti_password,uti_adress_mail,uti_droit_access) 
                                            VALUES (:pseudo,:pass,:email,:role)');
                    $result -> execute(array('pseudo' => $_POST["pseudo"],'pass' => password_hash($_POST["pass"],PASSWORD_BCRYPT) ,'email' => $_POST["email"],'role' => $_POST["rôle"]));
                    echo ('<b><FONT color="green">L Utilisateur a été ajouté à la base de donné</FONT></b>'.'<br>');// indique que tout c'est bien passé
                    header('Refresh: 10; URL=pageCreationP.php'); // refresh la page
                }
                      
              }






















              //Code pour Ajouter Auteur

              function recherche($bdd,$nom_var,$new_valeur){//Fonction recherche indentique sauf que c'est dans la table auteur
                $result = $bdd->prepare('SELECT * 
                                          FROM auteur
                                          WHERE '.$nom_var.'=:valeur');
                $result -> execute(array('valeur' => $new_valeur));
                $uti = $result->fetchAll();
                return $uti==NULL;
                //return $uti?0:1;
              }

              if(isset($_POST["aut_nom"]) && $_POST["aut_nom"]!="" && isset($_POST["aut_prenom"]) && $_POST["aut_prenom"]!=""){////Vérifie si toute les informations ont été entré dans le formulaire
                if(recherche($bdd,'aut_nom',$_POST["aut_nom"]) && recherche($bdd,'aut_prenom',$_POST["aut_prenom"])){//Vérifie que les informations n'existe pas dans la base de donnée
                  $result = $bdd->prepare('INSERT INTO auteur(aut_nom, aut_prenom) VALUES (:autnom,:autprenom)');
                  $result -> execute(array('autnom' => $_POST["aut_nom"],'autprenom' => $_POST["aut_prenom"]));
                  $_SESSION["tampon"]=1;
                  header('Refresh: 10; URL=pageCreationP.php');

                }
                else{
                  echo ('<b><FONT color="red">Erreur cette auteur existe déjà</FONT></b><br>'); 
                }
              }



















              //Code pour la partie Ajouter Livre

              if(isset($_POST["liv_titre"])&&isset($_POST["liv_annee"])&&isset($_POST["liv_libelle"])&&isset($_POST["auteur_nom"])){
              //Vérifie si toute les informations ont été entré dans le formulaire

                function rechercheLivre($bdd,$nom_var,$new_valeur){//function de recherche d'un livre qui renvoie true si le livre n'existe pas encore
                  $result = $bdd->prepare('SELECT * 
                                            FROM livre
                                            WHERE '.$nom_var.'=:valeur');
                  $result -> execute(array('valeur' => $new_valeur));
                  $uti = $result->fetchAll();
                  return $uti==NULL;
                  //return $uti?0:1;
                }

                function ajoutergenre($bdd,$id_livre,$id_genre){//fonction d'insertion de donnée genre dans la base de donnée
                  $result = $bdd->prepare('INSERT INTO attribuer VALUES (:id_livre_ext,:id_genre_ext)');
                  $result -> execute(array('id_livre_ext' => $id_livre,
                                          'id_genre_ext' => $id_genre));
                }
                
                if(rechercheLivre($bdd,'liv_titre',$_POST['liv_titre'])){//Appel function rechercheLivre
                  $result = $bdd->prepare('INSERT INTO livre(id_auteur_ext,liv_titre,liv_libellé,liv_annee_parution) 
                                            VALUES (:idauteur,:livretitre,:libelle,:livreannee)');
                  $result -> execute(array('livretitre' => $_POST["liv_titre"],
                                          'livreannee' => intval ($_POST["liv_annee"]),
                                          'libelle' => $_POST["liv_libelle"],
                                          'idauteur' => intval ($_POST["auteur_nom"])));
                  $_SESSION["tamponLivre"]=1;
                  header('Refresh: 10; URL=pageCreationP.php');//Refresh la page


                  //récupere l'id de l'élèment creer juste au dessus
                  $result = $bdd->prepare('SELECT id_livre 
                                            FROM livre
                                            WHERE liv_titre=:valeur');
                  $result -> execute(array('valeur' => $_POST['liv_titre']));
                  $uti = $result->fetch();
                  
                  //tableau liste différent genre
                  $tableau=['Combat','Histoire','Aventure','Phylosophique','Fantasy','Science-Fiction','Horreur',
                            'Romance','Surnaturel','Comédie'];
                  
                  //parcours toutes checkboxs et ajoute les genres au différents livres
                  foreach($tableau as $genre){
                    if(isset($_POST[$genre])){
                      ajoutergenre($bdd,$uti[0],$_POST[$genre]);
                    }
                  }


                }
                else{
                  echo ('<b><FONT color="red">Erreur le livre existe déjà ou information incomplete.</FONT></b><br>');
                }
      
              }
            ?>
            <form class="needs-validation" method="POST" novalidate>
              
              <div class="mb-3">
                <label for="pseudo">Username</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="pseudo" value="<?php if(isset($_POST["pseudo"])){echo($_POST["pseudo"]);}?>"placeholder="Username" required>
                </div>
              </div>

              <div class="mb-3">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" value="<?php if(isset($_POST["email"])){echo($_POST["email"]);}?>"placeholder="prenom.nom@lacatholille.fr">
              </div>

              <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="pass" value="<?php if(isset($_POST["pass"])){echo($_POST["pass"]);}?>"placeholder="***************">
              </div>

              <div class="mb-3">
                <label for="password">Repeat Password</label>
                <input type="password" class="form-control" name="pass_repeat" value="<?php if(isset($_POST["pass_repeat"])){echo($_POST["pass_repeat"]);}?>"placeholder="***************">
              </div>
              


              <div class="d-block my-3">

                <label for="rôle">Rôle</label>
                  <select class="custom-select d-block w-100" id="country" name='rôle' required>
                    <option value="utilisateur">Utilisateur</option>
                    <option value="admin">Admin</option>
                  </select>
                
              </div>
              <button class="btn btn-primary btn-lg btn-block" type="submit">Creer</button>
            </form>
            

          </div>

          <div class="col-md-6">
            <h4 class="mb-3">Ajout de Livre</h4>
            <?php  
              if($_SESSION["tamponLivre"]==1){
                echo ('<b><FONT color="green">Le Livre a été ajouté à la base de donné</FONT></b><br>');//Indique que tout c'est bien passé
                
              }
            
              
            ?>
            <form class="needs-validation" method="POST" novalidate>
              
              <div class="mb-3">
                <label for="pseudo">Titre</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="liv_titre" value="<?php if(isset($_POST["liv_titre"])){echo($_POST["liv_titre"]);}?>"placeholder="exemple: Nier Automata" required>
                </div>
              </div>
        
              <div class="mb-3">
                <label for="email">Année de Parution</label>
                <input type="text" class="form-control" name="liv_annee" value="<?php if(isset($_POST["liv_annee"])){echo($_POST["liv_annee"]);}?>"placeholder="exemple : 2017" required>
              </div>

              <div class="mb-3">
                <label for="email">Libellé</label>
                <input type="text" class="form-control" name="liv_libelle" value="<?php if(isset($_POST["liv_libelle"])){echo($_POST["liv_libelle"]);}?>"placeholder="exemple : Guerre Post Apocalyptique" required>
              </div>

              <div class="d-block my-3">

                <label for="rôle">Auteur</label>
                  <select class="custom-select d-block w-100" name="auteur_nom" required>
                    <?php
                      $result = $bdd->prepare('SELECT * FROM auteur');//récupere donnée de auteur
                      $result -> execute(array());
                      $auteur = $result->fetchAll();

                      foreach($auteur as $aut){
                        echo('<option value='.$aut['id_auteur'].'>'.$aut['aut_nom'].' '.$aut['aut_prenom'].'</option>');//affiche une liste d'auteur
                      }

                    ?>
                  </select>
              </div>

              <div class="custom-control custom-checkbox"><!--peu être optimiser mais zut ! -->
                <input type="checkbox"  id="Combat" name="Combat" value=1>
                <label  for="Combat">Combat</label>
                <input type="checkbox"  id="Histoire" name="Histoire" value=2>
                <label  for="Histoire">Histoire</label>
                <input type="checkbox"  id="Aventure" name="Aventure" value=3>
                <label  for="Aventure">Aventure</label><br>

                <input type="checkbox"  id="Phylosophique" name="Phylosophique" value=4>
                <label  for="Phylosophique">Phylosophique</label>
                <input type="checkbox"  id="Fantasy" name="Fantasy" value=5>
                <label  for="Fantasy">Fantasy</label>
                <input type="checkbox"  id="Science-Fiction" name="Science-Fiction" value=6>
                <label  for="Science-Fiction">Science-Fiction</label><br>
                <input type="checkbox"  id="Horreur" name="Horreur" value=7>
                <label  for="Horreur">Horreur</label>
                <input type="checkbox"  id="Romance" name="Romance" value=8>
                <label  for="Romance">Romance</label>
                <input type="checkbox"  id="Surnaturel" name="Surnaturel" value=9>
                <label  for="Surnaturel">Surnaturel</label>
                <input type="checkbox"  id="Comédie" name="Comédie" value=10>
                <label  for="Comédie">Comédie</label><br>
                
              </div>

            
              <button class="btn btn-primary btn-lg btn-block" type="submit">Ajouter un Livre</button>
            </form>
          </div>
        </div><!-- fin class row --><br><br>
        <div class="col-8 offset-2">
            <h4 class="mb-3">Creation d'Auteur</h4>
            <?php 
              if($_SESSION["tampon"]==1){
                echo('<FONT color="green">Cette Auteur a été ajouter à la base de donnée</FONT></b>');//bouger variable
                
              }
              
            ?>



          <form class="needs-validation" method="POST" novalidate>
          
            <div class="mb-3">
              <label for="pseudo">Nom</label>
              <div class="input-group">
                <input type="text" class="form-control" name="aut_nom" value="<?php if(isset($_POST["aut_nom"])){echo($_POST["aut_nom"]);}?>"placeholder="Nom de l'auteur" required>
              </div>
            </div>
      
            <div class="mb-3">
              <label for="email">Prenom</label>
              <input type="text" class="form-control" name="aut_prenom" value="<?php if(isset($_POST["aut_prenom"])){echo($_POST["aut_prenom"]);}?>"placeholder="Prenom de l'auteur" required>
            </div>
            
            
            <button class="btn btn-primary btn-lg btn-block" type="submit">Creer Auteur</button><br>
          </form>
        </div>
      
      </div><!-- fin class template -->

    </main><!-- /.container --> 
  </body>
</html>
