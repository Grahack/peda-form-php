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
$result_array = $result->fetchArray();
if ($result_array['desc'] == 'Je suis madame Test.') { ?>
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


case 2:
$db = new MyDB();
if (isset($_POST['Valider'])) {
    $db->exec("INSERT INTO users (nom, mdp, desc) VALUES ('".
        $_POST['nom']."', '".$_POST['mdp']."', '".$_POST['desc']."')");
}
?>
<h1>Étape 2</h1>
Passons aux choses sérieuses. Voici une liste des utilisateurs du site :
<ol>
<?php
$nbre_users = 0;
$result = $db->query('SELECT nom, desc FROM users');
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $nbre_users++;
    echo '    <li>' . $row['nom'] . ' : ' . $row['desc'] . '</li>'."\n";
}
?>
</ol>
<?php
if ($nbre_users < 10) { ?>
Votre <strong>deuxième mission</strong> consiste à faire du mal à la base de
données ou au site, simplement en ajoutant des utilisateurs. <br>
Il y a plusieurs façons différentes de le faire, essayez d’en trouver un
maximum. Vous avez 9 essais. <br>
En jetant un œil au code PHP au tout début de cette étape, vous verriez :
<pre><code>$db->exec("INSERT INTO users (nom, mdp, desc) VALUES ('".
          $_POST['nom']."', '".$_POST['mdp']."', '".$_POST['desc']."')");</code></pre>
<code>echo</code>er la requête finale pourrait vous aider. Cela ressemblerait à : <br>
<pre><code>INSERT INTO users (nom, mdp, desc) VALUES ('X', 'Y', 'Z')</code></pre>
Pour cela, vous utiliserez <strong>uniquement le formulaire suivant</strong> : <br>
<form method ="post" action ="#">
    <label for="nom">Nom :</label><input type="text" name="nom" id="nom"><br>
    <label for="mdp">MDP :</label><input type="password" name="mdp" id="mdp"><br>
    <label for="nom">Desc :</label><input type="text" name="desc" id="desc"><br>
    <input type="submit" name="Valider" value="Valider">
</form>
<script type="text/javascript">
onload = function () {document.getElementById("nom").focus();}
</script>
<?php } else { ?>
Votre action malfaisante a été détectée. Qu’avez-vous réussi à faire ? <br>
Après un échange avec les autres, plusieurs possibilités :
<ul>
    <li>Personne n’a réussi quoi que ce soit. Il est temps d’aller demander de
        l’aide à Internet.</li>
    <li>Un être malfaisant a réussi quelque chose. <br>
        Sans dire comment, il vous dira ce qu’il a réussi à faire, et vous
        essaierez d’atteindre le même résultat.</li>
</ul>
Comment réinitialiser la base de données pour retenter cette mission ? <br>
<br>
Si vous avez trouvé <strong>au moins deux façons de faire du mal</strong>, il
vous sera facile de passer à l’étape trois en trichant, <br>
puisque vous êtes un être malfaisant.<br>
Mais avant, réinitialisez la base de données, et notez les chaînes de caractères
malfaisantes.
<?php }
break;


case 3:
$db = new MyDB();
if (isset($_POST['Valider'])) {
    $db->exec("INSERT INTO users (nom, mdp, desc) VALUES ('".
        $_POST['nom']."', '".$_POST['mdp']."', '".$_POST['desc']."')");
}
?>
<h1>Étape 3</h1>
Voici encore une liste des utilisateurs du site :
<ol>
<?php
$result = $db->query('SELECT nom, desc FROM users');
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo '    <li>' . $row['nom'] . ' : ' . $row['desc'] . '</li>'."\n";
}
?>
</ol>
Votre <strong>troisième mission</strong> consiste à modifier le code de l’étape
2 afin de le protéger des êtres malfaisants. <br>
Le code de l’étape 2 a donc été copié et collé dans l’étape 3. <br>
Seul le titre et cet entête ont été changés, et le nombre de tentatives n’est
plus limité. <br>
<br>
Il vous faut donc, à l’aide d’un éditeur, <strong>modifier le code du fichier
.php</strong>. <br>
Vous ferez attention à ne <strong>modifier que le code de l’étape 3</strong>. <br>
<br>
Une fois satisfaite, vous demanderez à quelqu’un de vérifier, puis passerez à
<a href="?etape=4">l’étape 4</a>. <br>
<br>
<form method ="post" action ="#">
    <label for="nom">Nom :</label><input type="text" name="nom" id="nom"><br>
    <label for="mdp">MDP :</label><input type="password" name="mdp" id="mdp"><br>
    <label for="nom">Desc :</label><input type="text" name="desc" id="desc"><br>
    <input type="submit" name="Valider" value="Valider">
</form>
<script type="text/javascript">
onload = function () {document.getElementById("nom").focus();}
</script>
<?php
break;


case 4: ?>
<h1>Étape 4</h1>
Passons au formulaire de connexion. <br>
Ce coup-ci, l’être malfaisant cherche à se connecter avant de faire le mal. <br>
<br>
Votre <strong>quatrième mission</strong> consistera à, sans forcément utiliser
le nom d’un utilisateur existant, <br>
juste par <a href="http://gizmodo.com/5498412/sql-injection-license-plate-hopes-to-foil-euro-traffic-cameras/all">injection
SQL</a>, passer ce formulaire (et ainsi aller à l’étape 5). <br>
<br>
Notez que dans la vraie vie, le nom d’un utilisateur existant sera nécessaire. <br>
En effet, il faut bien « emprunter » les droits de quelqu’un. <br>
Au passage, pour qui peut-il être intéressant de se faire passer ? <br>
<br>
Vous n’accèderez à l’étape 5 <strong>que si vous vous faîtes passer pour un
utilisateur intéressant</strong> ! <br>
<br>
<form method ="post" action ="#">
    <label for="nom">Nom :</label><input type="text" name="nom" id="nom"><br>
    <label for="mdp">MDP :</label><input type="password" name="mdp" id="mdp"><br>
    <input type="submit" name="Connexion" value="Connexion">
</form>
<script type="text/javascript">
onload = function () {document.getElementById("nom").focus();}
</script>
<?php
$db = new MyDB();
if (isset($_POST['Connexion'])) {
    $result = $db->query("SELECT nom, mdp FROM users WHERE " .
        "nom='" . $_POST['nom'] . "' AND mdp='" . $_POST['mdp'] . "'");
    if ($result->fetchArray() !== False) {
        $comptes_interessants = array('admin', 'root', 'prof');
        if (in_array($_POST['nom'], $comptes_interessants)) { ?>
            Très bien, vous pouvez passer à <a href="?etape=5">l’étape 5</a>. <br>
        <?php
        } else { ?>
            Bof, le nom d’utilisateur n’est pas très intéressant.
        <?php
        }
    } else { ?>
		Le nom d’utilisateur et le mot de passe ne correspondent pas.
	<?php
	}
}
break;


case 5: ?>
<h1>Étape 5</h1>
La <strong>cinquième mission</strong> est encore malfaisante. <br>
<br>
Elle consiste à faire tourner un logiciel de force brute contre le formulaire
d’authentification pour voir en combien de temps le mot de passe de Mme Test
est découvert. <br>
<br>
<form method ="post" action ="#">
    <label for="nom">Nom :</label><input type="text" name="nom" id="nom"><br>
    <label for="mdp">MDP :</label><input type="password" name="mdp" id="mdp"><br>
    <input type="submit" name="Connexion" value="Connexion">
</form>
<script type="text/javascript">
onload = function () {document.getElementById("nom").focus();}
</script>
<?php
$db = new MyDB();
if (isset($_POST['Connexion'])) {
    $result = $db->query("SELECT nom, mdp FROM users WHERE " .
        "nom='" . $_POST['nom'] . "' AND mdp='" . $_POST['mdp'] . "'");
    if ($_POST['nom'] == 'Mme Test') {
        if ($result->fetchArray() !== False) { ?>
            Très bien, vous pouvez passer à <a href="?etape=6">l’étape 6</a>. <br>
            Que dit le chrono ?
        <?php
        } else { ?>
            Raté.
        <?php
        }
    } else { ?>
        Il faut voler le compte de Mme Test, de personne d’autre.
        <?php
    }
}
break;


case 6: ?>
<h1>Étape 6</h1>
La <strong>sixième mission</strong> est identique à la mission 5. <br>
<br>Vous devez maintenant craquer des mots de passe plus difficiles,
tout en mesurant toujours le temps que cela prend. <br>
<br>
Vous pouvez vous servir de l’étape 3 pour ajouter des utilisateurs.
<?php
break;


case 7: ?>
<h1>Étape 7</h1>
La <strong>septième mission</strong> est bienfaisante. <br>
<br>
Elle consiste à réutiliser le code écrit lors de la mission 3 en y ajoutant
une fonctionnalité essentielle dans un système d’authentification : le
stockage des mots de passe sous forme de condensat. <br>
<br>
Bon courage !
<?php
break;


default: ?>
<h1>Fin</h1>
Si vous avez des idées pour améliorer cette activité, tant en qualité qu’en
quantité, vous pouvez contribuer
<a href="https://github.com/Grahack/peda-form-php">au projet</a>. <br>
<br>
Et encore bravo.
<?php
// Fin du case géant.
}
