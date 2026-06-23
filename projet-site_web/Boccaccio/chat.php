<?php
if (!isset($_SESSION['client'])) {
    return;
}
?>
<link rel="stylesheet" href="Styles/chat.css">

<div id="chat-box" style="display:none;">
    <div id="chat-header">
        Chat des dragons
        <button id="chat-toggle">−</button>
    </div>
    <div id="chat-content">
        <div id="chat-messages"></div>
        <div id="chat-input-box">
            <input type="text" id="chat-input" maxlength="256" placeholder="Votre message...">
            <button id="chat-send">Envoyer</button>
        </div>
    </div>
</div>

<div id="chat-mini" style="display:flex;">💬</div>

<script>
function loadMessages() { // aller chercher mess du chat sur le serveur et l'afficher dans la page 
    fetch('fetch_messages.php')// appelle le serveur pour récup le message 
        .then(r => r.json()) // converti en Json, r c'est reponse du serveur
        .then(data => { // data contient sucess, me, messages
            if (!data.success) return;
            const me = data.me; // id du client connecté 
            const div = document.getElementById("chat-messages");
            div.innerHTML = "";// vide la zone de message 

            data.messages.forEach(m => { //m = message
                const side = (m.id_client == me) ? "right" : "left"; // message le mien ou pas si a droit ou a gauche 
                div.innerHTML += `<div class="message ${side}"> 
                <b>${m.username}</b> dit '<span>${m.content}</span>' 
                </div>`; // affiche 
            });
            div.scrollTop = div.scrollHeight;
        });
}
//envoie au serveur apres le click (post message pour verification)
document.getElementById("chat-send").addEventListener("click", async () => { // quand click fait ce qui suit 
    const input = document.getElementById("chat-input"); // recup le champs de texte
    const text = input.value.trim();// recupere le texte taper, trim enleve escpace inutile
    if (!text) return;

    const form = new FormData();
    form.append("content", text); // crée une enveloppe ou il y a le message

    const res = await fetch("post_message.php", { method: "POST", body: form });// envoie du mess dans le serveur dans reload puis post_message check tout
    const resp = await res.json(); 

    if (!resp.success) {
        alert(resp.message + (resp.bert_score ? " (Score BERT: " + resp.bert_score + ")" : ""));
        return;
    }

    input.value = "";
    loadMessages();// si sucess on appelle loadmessages pour afficher le message
});

// Toggle réduire → mini bouton
document.getElementById("chat-toggle").addEventListener("click", () => {
    document.getElementById("chat-box").style.display = "none";
    document.getElementById("chat-mini").style.display = "flex";
});

document.getElementById("chat-mini").addEventListener("click", () => {
    document.getElementById("chat-box").style.display = "block";
    document.getElementById("chat-mini").style.display = "none";
});

setInterval(loadMessages, 3000);
loadMessages();
</script>
