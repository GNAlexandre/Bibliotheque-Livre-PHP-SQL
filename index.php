<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Page de Connexion Art de Bibliothèque</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/sign-in/">
    <link href="signin.css" rel="stylesheet">
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
    
  </head>

    
  <!--Formulaire de Login -->
    
    
  <body class="text-center">
    <div class="form-signin">
      <form method="POST">
        <img src="images/icone_catho.jpg" alt="logo_catho">
        <h1 class="h3 mb-3 font-weight-normal">Page de Connexion</h1>

        <label for="inputEmail" class="sr-only">Email address</label> <!--penser à remodifier le type text en email après la fin des test-->
        <input type="email" name="email" id="inputEmail" class="form-control" value="<?php if(isset($_POST["email"])){echo($_POST["email"]);}?>"placeholder="Email address" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" value="<?php if(isset($_POST["password"])){echo($_POST["password"]);}?>"placeholder="Password" required>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

      <?php

        include_once("header.php");
        if(isset($_POST["email"])&&isset($_POST["password"])){ // vérification pour savoir si l'email existe dans la base de donnée
          $result = $bdd->prepare('SELECT * 
                                    FROM utilisateur
                                    WHERE uti_adress_mail=:email');
          $result -> execute(array('email' => $_POST["email"]));
          $uti = $result->fetchAll();

          if($uti!=null){
            if(password_verify( $_POST["password"],$uti[0]["uti_password"])){ // vérifie si le mot de passe entré correspond au mot de passe 
                                                                              //associer à l'email entré précédemment 
            
              $_SESSION['id']= $uti[0]["uti_id"]; // Check Enregistre les infos utile dans des variables Sessions
              $_SESSION['email']= $uti[0]["uti_adress_mail"]; // Check 
              $_SESSION['role']= $uti[0]["uti_droit_access"]; // Check 
              $_SESSION['pseudo']= $uti[0]["uti_pseudo"]; // Check 
              
              echo ('je suis connecté en tant que '.$uti[0]["uti_pseudo"]);



              header('Location: pageAdm.php');//Redirection page d'acceuil du site

            }
          }
          echo("Erreur Email ou Password Incorrect"); 
          

        }

      ?>

      <p class="copy">&copy; 2021</p>
    </div>


    
  </body>
</html>


