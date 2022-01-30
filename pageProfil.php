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
      <div class="row">
        
        <div class="col-md-6">
          <h4 class="mb-3">Modifier le Profil</h4>
            <?php

              function recherche($bdd,$nom_var,$new_valeur){//function de recherche qui renvoie true si l'élèment' n'existe pas encore
                $result = $bdd->prepare('SELECT * 
                                          FROM utilisateur
                                          WHERE '.$nom_var.'=:valeur');
                $result -> execute(array('valeur' => $new_valeur));
                $uti = $result->fetchAll();
                var_dump($uti);
                return $uti==NULL;
                //return $uti?0:1;
              }

              function update($bdd,$nom_var,$new_valeur){//fonction update
                $result = $bdd->prepare('UPDATE utilisateur
                                          SET  '.$nom_var.'=:uti_new_pseudo
                                          WHERE uti_id=:id');
                $result -> execute(array('id' => $_SESSION['id'],
                                          'uti_new_pseudo' => $new_valeur));
              }
              function pass($bdd){//return le mot de passe 
                $result = $bdd->prepare('SELECT uti_password
                                          FROM utilisateur
                                          WHERE uti_id=:id');
                $result -> execute(array('id' => $_SESSION['id']));    
                $uti = $result->fetchAll();                
                return $uti[0]["uti_password"];
              }

                      
              $modif=0;
              if(isset($_POST["pseudo"]) && $_POST["pseudo"]!=""){//Vérifie si une donnée a été entré 
                if(recherche($bdd,'uti_pseudo',$_POST["pseudo"])){//vérifie si le pseudo existe déjà dans la base de donnée
                  update($bdd,'uti_pseudo',$_POST["pseudo"]);//modifie le pseudo dans la base de donnée
                  $_SESSION['pseudo']=$_POST["pseudo"];//modifie pseudo dans la variable de session
                  $modif=1;
                }
                else{
                  echo ('<b><FONT color="red">Erreur ce pseudo est déjà pris</FONT></b>'.'<br>'); 
                }
              }

              if(isset($_POST["email"])&& $_POST["email"]!=""){//meme chose pour l'email
                if(recherche($bdd,'uti_adress_mail',$_POST["email"])){
                  update($bdd,'uti_adress_mail',$_POST["email"]);
                  $_SESSION['email']=$_POST["email"];
                  $modif=1;
                }
                else{
                  echo ('<b><FONT color="red">Erreur cette adress Email est déjà prise</FONT></b>'.'<br>');
                }
              }
              
              if(isset($_POST["pass"]) && isset($_POST["last_pass"]) && isset($_POST["pass_repeat"]) && $_POST["pass"]!=""  && $_POST["last_pass"]!=""  && $_POST["pass_repeat"]!=""){
                //meme chose pour le password

                if( $_POST["pass"]==$_POST["pass_repeat"] && password_verify( $_POST["last_pass"],$val=pass($bdd))){
                  //vérifie si le mot de passe correcpond à celui hash dans la base de donéne
                  update($bdd,'uti_password',password_hash($_POST["pass"],PASSWORD_BCRYPT));
                  $modif=1;
                }
                else{
                  echo ('<b><FONT color="red">Erreur lors du changement de mot MDP vérifier votre syntaxe</FONT></b>'.'<br>');
                }  
                
                
              }
              
              if($modif==1){//Affiche si une au moins une donnée a été modifié
                echo ('<b><FONT color="green">L Utilisateur a bien été modifier dans la base de donné</FONT></b>'.'<br>');
                header('Refresh: 10; URL=pageProfil.php');
              }
        
            ?>


            <form class="needs-validation" method="POST" novalidate>
              <div class="mb-3">
                <label for="pseudo">New Username</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="pseudo" value="<?php if(isset($_POST["pseudo"])){echo($_POST["pseudo"]);}?>"placeholder="New Username" required>
                </div>
              </div>

              <div class="mb-3">
                <label for="email">New Email</label>
                <input type="text" class="form-control" name="email" value="<?php if(isset($_POST["email"])){echo($_POST["email"]);}?>"placeholder="prenom.nom@lacatholille.fr">
              </div>
              
              <div class="mb-3">
                <label for="password">Actually Password</label>
                <input type="password" class="form-control" name="last_pass" value="<?php if(isset($_POST["last_pass"])){echo($_POST["last_pass"]);}?>"placeholder="***************">
              </div> 

              <div class="mb-3">
                <label for="password">New Password</label>
                <input type="password" class="form-control" name="pass" value="<?php if(isset($_POST["pass"])){echo($_POST["pass"]);}?>"placeholder="***************">
              </div>

              <div class="mb-3">
                <label for="password">Repeat New Password</label>
                <input type="password" class="form-control" name="pass_repeat" value="<?php if(isset($_POST["pass_repeat"])){echo($_POST["pass_repeat"]);}?>"placeholder="***************">
              </div>
              
              <button class="btn btn-primary btn-lg btn-block col-6 offset-3" type="submit">Modifier</button>
            </form>


            

        </div><!-- fin div Modifier mon profil -->
        
        <div class=" col-md-6"><!-- début Afficher liste de livre -->
          <h4 class="mb-3">Liste des livres Emprunter</h4>
          
          

                <!--Formulaire pour supprimer le livre-->
          <div class="d-block my-3">
            <form class="needs-validation" method="POST" novalidate>

              <label for="rôle">Sélectioner le Livre à rendre :</label>
                <select class="custom-select d-block w-100" name="supp_livre" required>
                  <?php
                        $result2 = $bdd->prepare('SELECT livre.id_livre, livre.liv_titre, livre.liv_annee_parution 
                                          FROM utilisateur
                                          INNER JOIN livre
                                          ON utilisateur.uti_id=livre.id_uti_ext    
                                          WHERE uti_id=:valeur');

                        $result2 -> execute(array('valeur' => $_SESSION['id']));
                        $biblio = $result2->fetchAll();
                          
                        foreach($biblio as $aut){
                          echo('<option value='.$aut['id_livre'].'>'.$aut['liv_titre'].'</option>');
                          //Affiche liste des livres de l'utilisateur actuel dans un form afin de pourvoir envoyer un id pour supprimer un livre de sa liste
                        }

                        if(isset($_POST['supp_livre'])){//Si une donnée est envoyer reset utilisateur qui a emprunter le livre pour le rendre de nouveau disponible
                          $requete = $bdd->prepare('UPDATE livre
                                                    SET id_uti_ext=NULL 
                                                    WHERE id_livre=:idlivre');
                          $requete -> execute(array('idlivre' => $_POST['supp_livre']));
                          header('Refresh: 0; URL=pageProfil.php');    

                          echo('je rends un livre');  
                                      
                        };

                  ?>
                </select>
                <button  type="submit">Rendre</button>
            </form>
          </div>
          <?php
            $result = $bdd->prepare('SELECT COUNT(*) 
                                      FROM utilisateur
                                      INNER JOIN livre
                                      ON utilisateur.uti_id=livre.id_uti_ext    
                                      WHERE uti_id=:valeur');
            $result -> execute(array('valeur' => $_SESSION['id']));
            $uti = $result->fetch();
            
           
            echo('<font color="red">Livre Emprunter ('.$uti[0].'/5)</font> <br><br><br>');//Affiche le nombre de livre disponible

            $result2 = $bdd->prepare('SELECT livre.id_livre, livre.liv_titre, livre.liv_annee_parution 
                                        FROM utilisateur
                                        INNER JOIN livre
                                        ON utilisateur.uti_id=livre.id_uti_ext    
                                        WHERE uti_id=:valeur');

            $result2 -> execute(array('valeur' => $_SESSION['id']));
            $biblio = $result2->fetchAll();
        

            foreach($biblio as $livre){//recharge(pour eviter problème lors d'une suppression) et affiche la liste de livre disponible 
              echo ($livre['liv_titre'].' ('.$livre['liv_annee_parution'].')<br>');
            }
            
          
            
          ?>

        
        </div> <!-- fin Afficher liste de livre -->
            

      </div><!-- fin class row -->
      
    </div><!-- fin star template -->

  </main><!-- /.container -->


      
  </body>
</html>
