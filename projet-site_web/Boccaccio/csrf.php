<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Configuration sécurisée des cookies de session (SameSite, HttpOnly).
    $cookieParams = session_get_cookie_params();
    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'; //verif si connection est HTTPS

    //Utilisation 
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => $cookieParams['lifetime'],
            'path' => $cookieParams['path'],
            'domain' => $cookieParams['domain'],
            'secure' => $secure, // en https
            'httponly' => true,// java ne peux pas lire le cookies (pour attaque contre XSS)
            'samesite' => 'Lax' //CSRF
        ]);
        session_start();
    } else {
        //Ajout de SameSite si pas 
        session_set_cookie_params(
            $cookieParams['lifetime'],
            $cookieParams['path'] . '; samesite=Lax',
            $cookieParams['domain'],
            $secure,
            true
        );
        session_start();
    }
}
// token : un code secret que le serveur donne au navigateur et qu’il va vérifier à chaque formulaire POST.
function csrf_init() {
    // Génère un token CSRF s'il n'existe pas encore
    if (empty($_SESSION['csrf_token'])) {
        try {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));// genere un token de 32octets aléatoire, chaque octet et un nombre entre 0 et 255
            // bin2hex transforme les octets en hexadécimal docn 64 caractère
        } catch (Exception $e) {
            // Fallback si random_bytes n'est pas disponible
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
}
// fct qui recupère le token 
function csrf_token() {
    csrf_init();
    return $_SESSION['csrf_token'];
}

function csrf_input() {
    // Génère un champ input hidden contenant le token CSRF a mettre dans tout les formualaire
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $t . '">';
}

// Comparaison de chaînes sécurisée contre les attaques temporelles
function csrf_secure_compare($a, $b) {
    if (!is_string($a) || !is_string($b)) return false;

    if (function_exists('hash_equals')) {
        return hash_equals($a, $b);
    }

    if (strlen($a) !== strlen($b)) return false;

    $res = 0; // difference
    $len = strlen($a);
    for ($i = 0; $i < $len; $i++) {
        $res |= ord($a[$i]) ^ ord($b[$i]); // ccomparaison des tokens
    }

    return $res === 0;
}

// Validation du token CSRF (POST ou header pour les requêtes AJAX)
function csrf_validate() {
    // Validation uniquement pour les requêtes POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return true;

    // recup token envoyé par le formulaire 
    $token = $_POST['csrf_token'] ?? null;

    // Fallback : certains appels AJAX envoient le token dans le header 'X-CSRF-Token'
    if (empty($token)) {
        $headers = [];

        // Récupération du header selon différentes méthodes
        if (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $headers[] = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }

        if (!empty(getallheaders()['X-CSRF-Token'] ?? null)) {
            $headers[] = getallheaders()['X-CSRF-Token'];
        }

        $token = $headers[0] ?? $token;
    }

    if (empty($token)) return false;

    return csrf_secure_compare($token, $_SESSION['csrf_token'] ?? '');
}
?>
