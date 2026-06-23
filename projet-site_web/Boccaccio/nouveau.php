<?php
require_once("csrf.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Client</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
    <style>
        input.valid { border: 2px solid green; }
        input.invalid { border: 2px solid red; }
        .error-message { color: red; font-size: 0.9em; }
        #feedback { font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
<h1>Création de compte</h1>

<form id="form-inscription" autocomplete="off">
    <p>Nom : <input type="text" name="n" id="n"/><br><span id="error-n" class="error-message"></span></p>
    <p>Prénom : <input type="text" name="p" id="p"/><br><span id="error-p" class="error-message"></span></p>
    <p>Adresse : <input type="text" name="adr" id="adr"/><br><span id="error-adr" class="error-message"></span></p>
    <p>Téléphone : <input type="text" name="num" id="num"/><br><span id="error-num" class="error-message"></span></p>
    <p>Email : <input type="email" name="email" id="email"/><br><span id="error-email" class="error-message"></span></p>
    <p>Mot de passe : <input type="password" name="mdp1" id="mdp1"/><br><span id="error-mdp1" class="error-message"></span></p>
    <p>Confirmer mot de passe : <input type="password" name="mdp2" id="mdp2"/><br><span id="error-mdp2" class="error-message"></span></p>
     <?php echo csrf_input(); ?>
    <p><input type="button" value="Créer le compte" id="submitBtn" disabled></p>
</form>

<div id="feedback"></div>

<p><a href="index.php">Retour à l'accueil</a></p>

<script>
$(document).ready(function() {
    function validateField(id, condition, message) {//fct qui valide tout les champs
        const input = $('#' + id);
        const error = $('#error-' + id);
        if (condition) {
            input.removeClass('invalid').addClass('valid');
            error.text('');
        } else {
            input.removeClass('valid').addClass('invalid');
            error.text(message);
        }
    }

    function validateForm() {
    const allValid = $('input:not([type="button"]):not([type="hidden"])') // $(..) -> jQuery
    // selectionne tout les elements html du formulaire
        .toArray() // transforml'objet jQuesry en tableau JavaScript
        .every(el => $(el).hasClass('valid')); // el= un intput a la fois regarde si champ valide 
    $('#submitBtn').prop('disabled', !allValid); //bouton activé ou pas en fct de si les champs son valid
}

    function checkEmailExists(email) {
        return $.ajax({ //envoie mail au serveur verif_email
            url: 'verif_email.php',
            method: 'POST',
            dataType: 'text',
            data: { email: email }
        });
    }

    $('input').on('input', function() { //code se déclenche à chaque frappe clavier
        const id = $(this).attr('id'); // this = le champs que l'utilisateur est entrain de modifier
        // attr = recupere attribut id
        const val = $(this).val().trim(); //val() texte tapé

        switch(id) { //switch : en fct du champs id une règle diff s'applique
            case 'n':
            case 'p':
            case 'adr':
            case 'num':
                validateField(id, val !== '', 'Ce champ ne peut pas être vide.');
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(val)) {
                    validateField(id, false, "Format d'adresse e-mail invalide.");
                    validateForm();
                    return;
                }
                checkEmailExists(val) //si mail existe deja
                    .done(function(response) { //appelle checkemailExist quand la requete est reussi
                        let data;
                        try { data = JSON.parse(response); } // transforme texte en Json
                        catch (e) { validateField(id, false, "Réponse serveur invalide."); return; } // executer si erruer de try
                        if (data.exists) {
                            validateField(id, false, "Cette adresse e-mail est déjà utilisée.");
                        } else {
                            validateField(id, true, '');
                        }
                        validateForm();
                    })
                    .fail(function(xhr, status, error) {
                        console.error("Erreur AJAX :", status, error);
                        validateField(id, false, "Erreur de vérification.");
                        validateForm();
                    });
                break;
            case 'mdp1':
                const pwdRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&]).{6,}$/;
                                                //au moin un chiffre
                validateField(id, pwdRegex.test(val), "Mot de passe faible (1 lettre, 1 chiffre, 1 caractère spécial).");
                if ($('#mdp2').val().trim() !== '') $('#mdp2').trigger('input');//revalide mdp2 si deja ecrit avant 1é
                break;
            case 'mdp2':
                const pwd1 = $('#mdp1').val();
                validateField(id, val === pwd1 && val !== '', "Les mots de passe ne correspondent pas.");
                break;
        }
        validateForm();
    });

    // Envoi AJAX du formulaire
    $('#submitBtn').on('click', function(e) {
        e.preventDefault(); //empeche la page de se recharger
        const data = { 
            n: $('#n').val(),
            p: $('#p').val(),
            adr: $('#adr').val(),
            num: $('#num').val(),
            email: $('#email').val(),
            mdp1: $('#mdp1').val(),
            mdp2: $('#mdp2').val(),
            csrf_token: $('input[name="csrf_token"]').val()
        };

        $.ajax({
            url: 'enregistrement.php',
            method: 'POST',
            dataType: 'json',
            data: data,
            xhrFields: { withCredentials: true },//xhrFields: option avancé de la requete ajax, 
            //withCredentials: envoie cookies de la session php avec la requète
            //Pour que la session commence 
            success: function(res) {
                if (res.success) {
                    $('#feedback').css('color', 'green').text(res.message);
                    setTimeout(() => window.location.href = 'index.php', 1000);
                } else {
                    $('#feedback').css('color', 'red').text(res.message);
                }
            },
            error: function(jqXHR) { // variables speciale de jQuery elle contient reponse du serveur, le code HTTP et les erreurs
                console.log("Réponse brute :", jqXHR.responseText);
                $('#feedback').css('color', 'red').text("Erreur serveur.");
            }
        });
    });
});
</script>
<?php include("chat.php"); ?>
</body>
</html>
