<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../bd.php');
require_once("../csrf.php");
$bdd = getBD();

//Récupère l'id depuis url 
// ? = condition ? valeur_si_vrai : valeur si faux 
$id = isset($_GET['id_art']) ? (int)$_GET['id_art'] : 0;

$article = null;
if($id) { // id qui existe et pas 0
    $req = $bdd->prepare('SELECT * FROM articles WHERE id_art = ?');
    $req->execute([$id]);
    $article = $req->fetch(); // 1 seul ligne
    // acceder à une méthode ou une propriété d'un objet
}

if ($article) {
    // Note moyenne
    $rating = $bdd->prepare("SELECT COALESCE(AVG(note),0) AS avg_note FROM product_ratings WHERE id_art = ?"); // si note on la met sinon 0 (coalesce)
    $rating->execute([$id]);
    $avg_note = (float)$rating->fetchColumn(); //recup une valeur la moyenne

    // Répartition des étoiles
    $distStmt = $bdd->prepare("SELECT note, COUNT(*) AS c FROM product_ratings WHERE id_art = ? GROUP BY note ORDER BY note DESC");
    $distStmt->execute([$id]);
    $distribution = $distStmt->fetchAll(PDO::FETCH_KEY_PAIR);// recup resultat sql en tableau clé -> valeur

    // Commentaires
    $commentsStmt = $bdd->prepare("
        SELECT c.id_comment, c.content, c.note, c.created_at, cl.prenom AS username,
               (SELECT COUNT(*) FROM comment_likes l WHERE l.id_comment = c.id_comment) AS likes,
               c.id_client
        FROM comments c
        JOIN clients cl ON cl.id_client = c.id_client
        WHERE c.id_art = ?
        ORDER BY c.created_at DESC
    ");
    $commentsStmt->execute([$id]);
    $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Questions
    $questionsStmt = $bdd->prepare("
        SELECT q.id_question, q.content, q.created_at, cl.prenom AS username, q.id_client
        FROM questions q
        JOIN clients cl ON cl.id_client = q.id_client
        WHERE q.id_art = ?
        ORDER BY q.created_at DESC
    ");
    $questionsStmt->execute([$id]);
    $questions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Réponses
    $answersStmt = $bdd->prepare("
        SELECT a.id_answer, a.id_question, a.parent_id, a.content, a.created_at, cl.prenom AS username, a.id_client
        FROM answers a
        JOIN clients cl ON cl.id_client = a.id_client
        WHERE a.id_question IN (SELECT id_question FROM questions WHERE id_art = ?)
        ORDER BY a.created_at ASC
    ");
    $answersStmt->execute([$id]);
    $answers = $answersStmt->fetchAll(PDO::FETCH_ASSOC);

    $byQuestion = [];
    foreach ($answers as $a) { $byQuestion[$a['id_question']][] = $a; } //classes par question

    function renderAnswers($list, $parent = null) { //fct recursive pour réponsess imbriquées
        echo "<ul>";
        foreach ($list as $a) {
            if ((int)$a['parent_id'] === (int)$parent) { // uniquement réponse du bon parents
                echo "<li><b>".$a['username']."</b>: ".$a['content']." <small>".$a['created_at']."</small></li>";
                renderAnswers($list, $a['id_answer']);// appelle recursive pour afficher sous-réponses
            }
        }
        echo "</ul>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Article</title>
    <base href="../">
    <link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
</head>
<body>

<?php if ($article): ?>
    <h1><?= ($article['nom']) ?></h1> 
    <img src="<?= ($article['url_photo']) ?>" alt="Photo de <?= ($article['nom']) ?>" style="max-width:400px;">
    <ul>
        <li><strong>Identifiant :</strong> <?= $article['id_art'] ?></li>
        <li><strong>Quantité en stock :</strong> <?= $article['quantite'] ?></li>
        <li><strong>Prix :</strong> <?= $article['prix'] ?> couronnes d'or</li>
        <li><strong>Description :</strong> <?= nl2br(($article['description'])) ?></li>
    </ul>

    <?php if (isset($_SESSION['client'])): ?>
        <form action="ajouter.php" method="POST" style="margin: 1em 0;">
            <input type="hidden" name="id_art" value="<?= $article['id_art'] ?>"> 
            <label for="quantite">Nombre d'exemplaires :</label>
            <input type="number" name="quantite" id="quantite" min="1" value="1" required>
            <?= csrf_input(); ?>
            <input type="submit" value="Ajouter à votre panier">
        </form>
    <?php endif; ?>

    <h3>Note du produit</h3>
    <p>
        <?php
        $stars = max(0, min(5, (int)round($avg_note)));
        echo str_repeat("⭐", $stars) . " (" . number_format($avg_note, 1) . ")";
        ?>
    </p>
    <ul>
        <?php for ($n=5; $n>=1; $n--): ?>
            <li><?= $n ?> étoiles: <?= (int)($distribution[$n] ?? 0) ?> avis</li>
        <?php endfor; ?>
    </ul>

    <hr>
    <section id="comments">
        <h3>Commentaires</h3>
        <?php foreach ($comments as $c): ?>
            <div class="comment">
                <div>
                    <?= str_repeat("⭐", (int)$c['note']) ?>
                    <b><?= ($c['username']) ?></b>
                    <small><?= $c['created_at'] ?></small>
                </div>
                <div><?= ($c['content']) ?></div>
                <div>
                    Likes: <?= (int)$c['likes'] ?>
                    <?php if (isset($_SESSION['client'])): ?>
                        <form method="POST" action="like_comment.php" class="likeForm" style="display:inline;">
                            <input type="hidden" name="id_comment" value="<?= (int)$c['id_comment'] ?>">
                            <?= csrf_input(); ?>
                            <button type="submit">Like</button>
                        </form>
                        <?php if ($_SESSION['client']['id_client'] == $c['id_client']): ?>
                            <form method="POST" action="delete_comment.php" style="display:inline;">
                                <input type="hidden" name="id_comment" value="<?= (int)$c['id_comment'] ?>">
                                <?= csrf_input(); ?>
                                <button type="submit">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; //fin de if ?> 
                </div>
            </div>
        <?php endforeach; // fin de foreach?>

        <?php if (isset($_SESSION['client'])): ?>
            <h4>Poster un commentaire</h4>
            <form method="POST" action="post_comment.php" id="commentForm">
                <input type="hidden" name="id_art" value="<?= (int)$id ?>">
                <textarea name="content" maxlength="256" required></textarea><br>
                <label>Note :</label>
                <select name="note" required>
                    <?php for ($i=1; $i<=5; $i++): ?>
                        <option value="<?= $i ?>"><?= str_repeat("⭐", $i) ?></option>
                    <?php endfor; ?>
                </select><br>
                <?= csrf_input(); ?>
                <button type="submit">Poster</button>
            </form>
        <?php else: ?>
            <p>Connectez-vous pour commenter.</p>
        <?php endif; ?>
    </section>

    <hr>
    <section id="qa">
        <h3>Questions</h3>

        <form method="GET" action="Articles/article.php">
            <input type="hidden" name="id_art" value="<?= (int)$id ?>">
            <input type="text" name="search" placeholder="Rechercher une question..."> 
            <button type="submit">Rechercher</button>
        </form>

        <?php
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        if ($search !== '') {
            $qSearch = $bdd->prepare("
                SELECT q.id_question, q.content, q.created_at, cl.prenom AS username, q.id_client
                FROM questions q JOIN clients cl ON cl.id_client = q.id_client
                WHERE q.id_art = ? AND q.content LIKE ?
                ORDER BY q.created_at DESC
            ");
            $qSearch->execute([$id, '%'.$search.'%']); // chercher mot clé partout dans le texte 
            $questions = $qSearch->fetchAll(PDO::FETCH_ASSOC); // appelle de la méthode fetchAll dans l'objet $qSearch
            echo "<p>Résultats pour '".htmlspecialchars($search)."'</p>";
        }
        ?>

        <?php foreach ($questions as $q): ?>
            <div class="question">
                <div>
                    <b><?= ($q['username']) ?></b>
                    <small><?= $q['created_at'] ?></small>
                </div>
                <div><?= ($q['content']) ?></div>

                <?php if (isset($byQuestion[$q['id_question']])): ?>
                    <?php renderAnswers($byQuestion[$q['id_question']], null); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['client'])): ?>
                    <form method="POST" action="post_answer.php" class="answerForm">
                        <input type="hidden" name="id_question" value="<?= (int)$q['id_question'] ?>">
                        <input type="hidden" name="parent_id" value="">
                        <textarea name="content" maxlength="256" required></textarea>
                        <?= csrf_input(); ?>
                        <button type="submit">Répondre</button>
                    </form>

                    <?php if ($_SESSION['client']['id_client'] == $q['id_client']): ?>
                        <form method="POST" action="delete_question.php" style="display:inline;">
                            <input type="hidden" name="id_question" value="<?= (int)$q['id_question'] ?>">
                            <?= csrf_input(); ?>
                            <button type="submit">Supprimer la question</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['client'])): ?>
            <h4>Poser une question</h4>
            <form method="POST" action="post_question.php" class="questionForm">
                <input type="hidden" name="id_art" value="<?= (int)$id ?>">
                <textarea name="content" maxlength="256" required></textarea>
                <?= csrf_input(); ?>
                <button type="submit">Poser</button>
            </form>
        <?php else: ?>
            <p>Connectez-vous pour poser une question.</p>
        <?php endif; ?>
    </section>
<?php else: ?>
    <p>Article introuvable.</p>
<?php endif; ?>

<p><a href="index.php">← Retour à l'accueil</a></p>
<?php include("../chat.php"); ?>

<script>
    // Envoyer formulaire sans recharcher la page (ajax)
document.addEventListener("DOMContentLoaded", () => { // DOM(html sous forme d'objet manipulable par JavaScript) 
    // Donc attend que tout le html soit chargé, () => { fct fléché, c'est juste une simplification de function(){
    async function sendForm(form, url, onSuccess) { // definition de la fct pour envoyer des formulaire 
    // async pourattendre chose lente (je peux attendre )
        const data = new FormData(form); //transforme formulaire html en donnée envoyables (POST)
        try {
            // const = crée une variables en javaScript
            const res = await fetch(url, { method: 'POST', body: data }); // fetch : envoie requete http post vers l'url avec les données du formulaire
            // await = attend la réponse du serveur, en lien avec async (j'attend)    
            const json = await res.json(); //json= facon standard d'écrire des données pour les envoyer entre un serveur et Javascript
            // lis res et le transforme en objet javaScript
            if (json.success) {
                onSuccess(json); // appelle fct de rappel pour maj de la page
                form.reset();// vide form pour utilisateur ecrive a nouveau 
            } else {
                alert(json.message || "Erreur inconnue");
            }
        } catch (err) {
            alert("Erreur réseau : " + err.message);
        }
    }

    // Commentaires
    const commentForm = document.getElementById("commentForm");
    // commentFrom est mtn une reference au form dans le DOM
    if(commentForm) {
        commentForm.addEventListener("submit", e => { //déclanche fct quand l'utilisateur clique sur poster
            //e = evenement du formulaire 
            e.preventDefault(); //eviter  que page se refresh et d'envoyer en POST
            sendForm(commentForm, "post_comment.php", json => { //fct qui envoie le from a post_comment en AJAX 
                div.className = "comment"; // garder style cohérent avec les autres comm
                div.innerHTML = `
                    <div>${"⭐".repeat(json.note)} <b>${json.username}</b> <small>${json.created_at}</small></div>
                    <div>${json.content}</div>
                    <div>Likes: 0</div>
                `;// creation nouveau commentaire dans la page 
                document.querySelector("#comments").prepend(div);
                // selction qui contient            // met le nouveau comm en haut 
                // tout les comm
            });
        });
    }

    // Questions
    document.querySelectorAll(".questionForm").forEach(form => {
        //récupère tous                         //pour chaque ajout de la fct
        //les formulaires ayant la classe questionForm.
        form.addEventListener("submit", e => {
            e.preventDefault();
            sendForm(form, "post_question.php", json => {
                const div = document.createElement("div");
                div.className = "question";
                div.innerHTML = `<div><b>${json.username}</b> <small>${json.created_at}</small></div>
                                 <div>${json.content}</div>`;
                form.parentNode.insertBefore(div, form); // question appartait au dessus du form sans recharcher page
            });
        });
    });

    // Réponses
    document.querySelectorAll(".answerForm").forEach(form => {
        form.addEventListener("submit", e => {
            e.preventDefault();
            sendForm(form, "post_answer.php", json => {
                const li = document.createElement("li");
                li.innerHTML = `<b>${json.username}</b>: ${json.content} <small>${json.created_at}</small>`;
                let ul = form.parentNode.querySelector("ul"); // cherche ul pour mettre la reponse
                if(!ul) { // si ul existe pas
                    ul = document.createElement("ul");  //crée ul et insert avant le form
                    form.parentNode.insertBefore(ul, form); 
                }
                ul.appendChild(li);
            });
        });
    });

    // Likes
    document.querySelectorAll(".likeForm").forEach(form => {
        form.addEventListener("submit", e => {
            e.preventDefault();
            sendForm(form, "like_comment.php", json => {
                const div = form.parentNode; //conteneur du form commentaire
                div.querySelector("div:last-child").textContent = "Likes: " + (json.likes ?? 1); //si json.lijes est null affiche 1 par defaut (met a jour le nbr de like)
            });
        });
    });

});// fermeture de DOMcontentLoaded
</script>
</body>
</html>
