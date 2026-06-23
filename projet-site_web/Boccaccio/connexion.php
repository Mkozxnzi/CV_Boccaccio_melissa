<?php
require_once("csrf.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  input.valid { border: 2px solid green; }
  input.invalid { border: 2px solid red; }
  #feedback { margin-top: 10px; font-weight: bold; }
</style>
</head>
<body>
<header><h1>Connexion</h1></header>
<main>
  <form id="form-connexion" autocomplete="off">
    <p>Adresse e-mail : <input type="email" name="mail" id="mail"/></p>
    <p>Mot de passe : <input type="password" name="mdp1" id="mdp1"/></p>
    <?php
require_once("csrf.php");
echo csrf_input(); // ajoute automatiquement le bon <input hidden>
?>
    <input type="submit" value="Se connecter">
  </form>
  <div id="feedback"></div>
  <p><a href="nouveau.php">Nouveau compte client</a></p>
  <p><a href="index.php">Retour accueil</a></p>
</main>

<script>
$(document).ready(function() { // attend page complétement chargé 
  $('#form-connexion').on('submit', function(e) { //recup le form et fonction quand submit, e obj 
    e.preventDefault(); // empeche le rechargement 
    const email = $('#mail').val().trim();// recup valeur des inputs
    const mdp = $('#mdp1').val().trim();

    if (email === '' || mdp === '') {
      $('#feedback').css('color', 'red').text('Veuillez remplir tous les champs.');
      return;
    }

    $.ajax({ // requetes ajax
      url: 'connecter.php', // fichier serveur qui verifie 
      method: 'POST',
      dataType: 'json',
      data: { mail: email, mdp1: mdp, csrf_token: $('input[name="csrf_token"]').val()},
      xhrFields: { withCredentials: true }, // envoyer cookies session
      success: function(res) {
        if (res.success) {
          $('#feedback').css('color', 'green').text(res.message);
          setTimeout(() => window.location.href = 'index.php', 1000);
        } else {
          $('#feedback').css('color', 'red').text(res.message);
        }
      },
      error: function() {
        $('#feedback').css('color', 'red').text('Erreur de communication avec le serveur.');
      }
    });
  });
});
</script>
</body>
<?php include("chat.php"); ?>

</html>
