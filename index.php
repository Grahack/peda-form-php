<!-- WARNING:

Le code PHP et HTML de ce fichier ne peut en aucun cas servir en production.
Certaines mauvaises pratiques ont été utilisées dans un but de simplification.

-->
<meta charset="utf-8">
<link href="style.css" rel="stylesheet"/>
<?php
/* Connecteur maison.
 * Crée le fichier contenant la base de données si besoin.
 */
class MyDB extends SQLite3 {
    function __construct() {
        // Un moyen d’explorer l’API de sqlite-php:
        // var_dump(get_class_vars('SQLite3'));
        // var_dump(get_class_methods('SQLite3'));
        // echo "SQLite version: " . $db->version()['versionString'];
        $db_file = 'donnees.db';
        if (!file_exists($db_file)) touch($db_file);
        $this->open($db_file);
        // Pour simplifier, on n’appelera jamais $db->close().
    }
}

// Étape zéro par défaut:
if (!isset($_GET['etape'])) $_GET['etape'] = 0;
// Les pages, selon l’étape en cours:
switch ((int)$_GET['etape']) {
case 0: ?>
<h1>Introduction</h1>
Bonjour&nbsp;! <br>
<br>
Ces pages vont vous sensibiliser à la sécurisation des formulaires en PHP. <br>
Vaste sujet, que nous n’allons pouvoir qu’effleurer. <br>
Vous serez guidés d’étape en étape à l’aide d’explications et de liens.
<h1>Étapes et URLs</h1>
Vous êtes actuellement à l’<strong>étape zéro</strong>, étape par défaut
configurée à l’aide de la ligne :
<pre><code>if (!isset($_GET['etape'])) $_GET['etape'] = 0;</code></pre>
À tout moment, vous pourrez bidouiller l'URL, mais ce n'est pas le but. <br>
Si par hasard un vrai problème de conception apparaissait, il faudra le
signaler, bidouiller, et proposer une solution via Github.
<h1>C’est parti&nbsp;!</h1>
Votre <strong>première mission</strong> consiste à vérifier que PHP va pouvoir
communiquer avec une base <a href="http://sqlite.org">SQLite</a>. <br>
C’est un SGBD minimal sans serveur, pour lequel les données d’une base sont
contenues dans un seul fichier. <br>
Il suffira dans le meilleur des cas de configurer le serveur (à la souris ou
en ligne de commande), <br>
ou dans le pire des cas de l’installer, ainsi que les liens pour PHP. <br>
<br>
Le passage à l’étape 1 va vérifier tout cela en tentant de&nbsp;:
<ol>
    <li>créer un fichier vide,</li>
    <li>passer ce fichier au format SQLite,</li>
    <li>créer les tables et rentrer les données nécessaires pour la suite.</li>
</ol>
SQLite doit donc, en plus de pouvoir modifier le fichier de données, pouvoir
créer ce fichier. Attention donc aux droits d’accès. <br>
<br >
<a href="?etape=1">Mon serveur est prêt, je veux passer à l’étape 1</a>
<?php
break;


case 1: ?>
<h1>Étape 1</h1>
<?php
$db = new MyDB();
if ($db->exec('CREATE TABLE users (nom STRING, mdp STRING, desc STRING)')) {
    $db->exec("INSERT INTO users (nom, mdp, desc) VALUES ('Mme Test', 'mouton', 'Je suis madame Test.')");
}
$result = $db->query('SELECT desc FROM users');
if ($result->fetchArray()['desc'] == 'Je suis madame Test.') { ?>
Bravo, tout s’est bien passé. <br>
<br>
Si vous rafraîchissez la page, vous devriez voir une erreur. Laquelle et
pourquoi ? <br>
<form method ="post" action ="#">
    <input type="radio" name="reponse" value="x" id="1">
        <label for="1">Je n’ai plus Internet.</label><br>
    <input type="radio" name="reponse" value="x" id="2">
        <label for="2">SQLite doit être mieux configuré.</label><br>
    <input type="radio" name="reponse" value="v" id="3">
        <label for="3">Le serveur veut recréer la base de données.</label><br>
    <input type="radio" name="reponse" value="x" id="4">
        <label for="4">Les droits d’accès sont mal configurés.</label><br>
    <input type="radio" name="reponse" value="x" id="5">
        <label for="5">C’est une erreur qui ne veut rien dire.</label><br>
    <input type="submit" value="Valider pour passer à l’étape 2">
</form>
<?php
if (isset($_POST['reponse'])  && $_POST['reponse'] == 'v') { ?>
<a href="?etape=2">Bien, vous pouvez passer à l’étape 2</a>
<?php
}
} else { ?>
Raté. Veuillez lire et comprendre l’erreur ci-dessus, rectifier et rafraîchir la page.
<?php
}
break;

// Fin du case géant.
}
