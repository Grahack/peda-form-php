<meta charset="utf-8">
<link href="style.css" rel="stylesheet"/>
<?php
/* Connecteur maison.
 * Crée le fichier contenant la base de données si besoin.
 */
class MyDB extends SQLite3 {
    function __construct() {
        // Un moyen d’explorer l’API de sqlite-php:
        // var_dump(get_class_vars(SQLite3));
        // var_dump(get_class_methods(SQLite3));
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
Bonjour&nbsp;! <br>
<br>
Ces pages vont vous sensibiliser à la sécurisation des formulaires en PHP. <br>
Vaste sujet, que nous n’allons pouvoir qu’effleurer. <br>
Vous serez guidés d’étape en étape à l’aide d’explications et de liens. <br>
<br>
Vous êtes actuellement à l’<strong>étape zéro</strong>, étape par défaut
configurée à l’aide de la ligne :
<pre><code>if (!isset($_GET['etape'])) $_GET['etape'] = 0;</code></pre>
À tout moment, vous pourrez bidouiller l'URL, mais ce n'est pas le but. <br>
Si par hasard un vrai problème de conception apparaissait, il faudra le
signaler, bidouiller, et proposer une solution. <br>
<br>
Votre <strong>première mission</strong> est d’installer
<a href="http://sqlite.org">SQLite</a> ainsi que les liens pour PHP. <br>
C’est un SGBD minimal sans serveur, pour lequel les données d’une base sont
contenues dans un seul fichier. <br>
Vous devez juste faire en sorte que votre installation de PHP ait accès à la
classe <code>SQLite3</code>. <br>
L’étape 1 va vérifier tout cela en tentant de&nbsp;:
<ol>
    <li>créer un fichier vide,</li>
    <li>passer ce fichier au format SQLite,</li>
    <li>créer les tables et rentrer les données nécessaires pour la suite.</li>
</ol>
<a href="?etape=1">Passer à l’étape 1</a>
<?php
break;


case 1:
$db = new MyDB();
$db->exec('CREATE TABLE foo (bar STRING)');
$db->exec("INSERT INTO foo (bar) VALUES ('Tout s’est bien passé.')");
$result = $db->query('SELECT bar FROM foo');
echo $result->fetchArray()['bar'] . '<br>';
echo "SQLite version: " . $db->version()['versionString'];
$db->close();
break;

// Fin du case géant.
}
