<?php

  include_once("header.php"); 
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Acceuil Art de Bibliothèque</title>

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
          
        <div class="pseudo"><h1><?php echo ('Bonjour '.$_SESSION['pseudo']); ?> </h1></div><br> <!--Affiche le pseudo de la personne connecté-->
          
        <form class="needs-validation" method="POST" novalidate><!--Formulaire de filtre des genres -->
          <b>Genres :</b> 
            <input type="checkbox"  id="Combat" name="Combat" value=1>
            <label  for="Combat">Combat</label>
            <input type="checkbox"  id="Histoire" name="Histoire" value=2>
            <label  for="Histoire">Histoire</label>
            <input type="checkbox"  id="Aventure" name="Aventure" value=3>
            <label  for="Aventure">Aventure</label>
            <input type="checkbox"  id="Phylosophique" name="Phylosophique" value=4>
            <label  for="Phylosophique">Phylosophique</label>
            <input type="checkbox"  id="Fantasy" name="Fantasy" value=5>
            <label  for="Fantasy">Fantasy</label>
            <input type="checkbox"  id="Science-Fiction" name="Science-Fiction" value=6>
            <label  for="Science-Fiction">Science-Fiction</label>
            <input type="checkbox"  id="Horreur" name="Horreur" value=7>
            <label  for="Horreur">Horreur</label>
            <input type="checkbox"  id="Romance" name="Romance" value=8>
            <label  for="Romance">Romance</label>
            <input type="checkbox"  id="Surnaturel" name="Surnaturel" value=9>
            <label  for="Surnaturel">Surnaturel</label>
            <input type="checkbox"  id="Comédie" name="Comédie" value=10>
            <label  for="Comédie">Comédie</label>

          <button type="submit">Filtrer</button>      
        </form><br>


        <form  method="POST" novalidate> <!--formulaire pour selectionner un livre à emprunter-->
          <label> Sélectioner le Livre à Emprunter :</label>
          <select  name="ajouter_livre" required>


            <?php
              function nbrDePlaceDispo($bdd,$utilisateur_id){ // fonction renvoie le nombre de livre de l'utilisateur
                $result = $bdd->prepare('SELECT COUNT(*) 
                                FROM utilisateur
                                INNER JOIN livre
                                ON utilisateur.uti_id=livre.id_uti_ext    
                                WHERE uti_id=:valeur');
                $result -> execute(array('valeur' => $utilisateur_id));
                $uti = $result->fetch();
                return $uti[0];
                
              }
              //requete de la liste des livres disponibles
              $listelivredispo = $bdd->prepare('SELECT livre.id_livre, livre.liv_titre, livre.liv_annee_parution FROM livre WHERE id_uti_ext IS NULL GROUP BY liv_titre ');

              $listelivredispo -> execute(array());
              $biblio = $listelivredispo->fetchAll();
                
              foreach($biblio as $aut){
                echo('<option value='.$aut['id_livre'].'>'.$aut['liv_titre'].'</option>'); // affichage les livres disponible dans le select
              }

              if(isset($_POST['ajouter_livre']) && intval(nbrDePlaceDispo($bdd,$_SESSION['id']))<5){
                //change la valeur de id_uti_ext afin de la supprimer de la liste des livres disponibles et que l'utilisateur puissent les voir dans sa liste personnel
                $requete = $bdd->prepare('UPDATE livre                                              
                                          SET id_uti_ext=:valeur 
                                          WHERE id_livre=:idlivre');
                $requete -> execute(array('valeur' => $_SESSION['id'],'idlivre'=>$_POST['ajouter_livre']));     
                header('Refresh: 0; URL=pageAdm.php');       //Refresh la page
                            
              }
                

            ?>
          </select>
          <button  type="submit">Emprunter</button>
        </form>


        <?php if(intval(nbrDePlaceDispo($bdd,$_SESSION['id']))==5) { // Si l'utilisateur a déjà emprunter 5 livre avertie qu'il ne peut pas en emprunter plus
          echo('<font color="red">Capacité maximal de livre emprunté atteint</font><br><br>'); 
          }
        ?>


        <p class="lead">Liste des livres disponible à l'emprunt :</p>     
        <?php
          $tableau=['Combat','Histoire','Aventure','Phylosophique','Fantasy','Science-Fiction','Horreur',
          'Romance','Surnaturel','Comédie'];//liste des genres disponibles

          $unGenreSelectionner=0;

          //parcours toutes checkboxs et ajoute les genres au différents livres
          $livregenre=[];
          foreach($tableau as $genre){
            if(isset($_POST[$genre])){//Séléctionne les livres dont le genre correspond à la case coché
              $result = $bdd->prepare('SELECT id_livre,`liv_titre`,`liv_annee_parution`,auteur.aut_nom,auteur.aut_prenom
                                      FROM `livre` 
                                      INNER JOIN auteur
                                      ON livre.id_auteur_ext=auteur.id_auteur
                                      INNER JOIN attribuer
                                      ON attribuer.id_livre_ext=livre.id_livre
                                      WHERE livre.id_uti_ext IS NULL AND attribuer.id_genre_ext=:valeur
                                      GROUP BY livre.liv_titre');
              $result -> execute(array('valeur' => $_POST[$genre]));
              $uti = $result->fetchAll();
              

              foreach($uti as $livre){//affiche les livres disponibles
                $livregenre[$livre["id_livre"]]=[
                  "titre"=>$livre["liv_titre"],
                  "annee"=>$livre["liv_annee_parution"],
                  "autnom"=>$livre["aut_nom"],
                  "autprenom"=>$livre["aut_prenom"]
                ];
              };
              
              $unGenreSelectionner+=1;
            }
          }
          if($unGenreSelectionner!=0){//variable tampon pour l'affichage en fonction des genres
            foreach($livregenre as $livre){
              echo($livre['titre'].' ('.$livre['annee'].') de '.$livre['autnom'].$livre['autprenom'].'<br>');
            }
          }


          if($unGenreSelectionner==0){//Affiche tous les livres disponibles si aucun genre n'a été sélectionner/filtrer
            $result = $bdd->prepare('SELECT `liv_titre`,`liv_annee_parution`,auteur.aut_nom,auteur.aut_prenom
                                      FROM `livre` 
                                      INNER JOIN auteur
                                      ON livre.id_auteur_ext=auteur.id_auteur
                                      WHERE livre.id_uti_ext IS NULL 
                                      GROUP BY livre.liv_titre');
            $result -> execute(array());
            $uti = $result->fetchAll();
            
            
            foreach($uti as $livre){
              echo($livre['liv_titre'].' ('.$livre['liv_annee_parution'].') de '.$livre['aut_nom'].$livre['aut_prenom'].'<br>');
          
          
            };
          }

        ?>
          


        
        
      </div><!-- fin div started template -->
    </main><!-- /.container -->
  </body>
</html>
