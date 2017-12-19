<?php

// récupérer les éléments du formulaire
// et se protéger contre l'injection MySQL (plus de détails ici: http://us.php.net/mysql_real_escape_string)
$email=stripslashes($_POST['email']);
$password=stripslashes($_POST['password']);
$nom=stripslashes($_POST['nom']);
$prenom=stripslashes($_POST['prenom']);
$tel=stripslashes($_POST['tel']);
$website=stripslashes($_POST['website']);
$sexe='';
if (array_key_exists('sexe',$_POST)) {
    $sexe=stripslashes($_POST['sexe']);
}
$birthdate=stripslashes($_POST['birthdate']);
$ville=stripslashes($_POST['ville']);
$taille=stripslashes($_POST['taille']);
$couleur=stripslashes($_POST['couleur']);
$profilepic=stripslashes($_POST['profilepic']);

try {
    // Connect to server and select database.
    $dbh = new PDO('mysql:host=localhost;dbname=pictionnary', 'test', 'test');

    // Vérifier si un utilisateur avec cette adresse email existe dans la table.
    // En SQL: sélectionner tous les tuples de la table USERS tels que l'email est égal à $email.
    $sql = $dbh->query("select * from user  where email='".email."'");
    if ($sql->rowCount()>=1) {
        header("location:index.html")
        // rediriger l'utilisateur ici, avec tous les paramètres du formulaire plus le message d'erreur
        // utiliser à bon escient la méthode htmlspecialchars http://www.php.net/manual/fr/function.htmlspecialchars.php          // et/ou la méthode urlencode http://php.net/manual/fr/function.urlencode.php
    }
    else {
        // Tenter d'inscrire l'utilisateur dans la base
        $sql = $dbh->prepare("INSERT INTO users (email, password, nom, prenom, tel, website, sexe, birthdate, ville, taille, couleur, profilepic) "
            . "VALUES (:email, :password, :nom, :prenom, :tel, :website, :sexe, :birthdate, :ville, :taille, :couleur, :profilepic)");
        $sql->bindValue(":email", $email);
        $sql->bindValue(":password", $password); // de même, lier la valeur pour le mot de passe
        if ($nom != null || $nom != undefined) {// lier la valeur pour le nom, attention le nom peut être nul, il faut alors lier avec NULL, ou DEFAULT
            $sql->bindValue(":nom", "");
        }
        else { $sql->bindValue(":nom", $nom); }
        $sql->bindValue(":prenom", $prenom);
        $sql->bindValue(":tel", $tel);
        $sql->bindValue(":website", $website);
        if($sexe == 'H' || $sexe == 'F' || $sexe==''){
            $sql->bindValue(":sexe", $sexe);
        }
        $sql->bindValue(":birthdate", $birthdate);
        $sql->bindValue(":ville", $ville);
        $sql->bindValue(":taille", $taille);
        if ( ($couleur).Count < 7){
            $sql->bindValue(":couleur", $couleur);
        }
        $sql->bindValue(":profilepic", $profilepic);
        if (!$sql->execute()) {
            echo "PDO::errorInfo():<br/>";
            $err = $sql->errorInfo();
            print_r($err);
        } else {
             session_start();

            // ensuite on requête à nouveau la base pour l'utilisateur qui vient d'être inscrit, et
            $sql = $dbh->query("SELECT u.id, u.email, u.nom, u.prenom, u.couleur, u.profilepic FROM USERS u WHERE u.email='".$email."'");
            if ($sql->rowCount()<1) {
                header("Location: main.php?erreur=".urlencode("un problème est survenu"));
            }
            else {
                // on récupère la ligne qui nous intéresse avec $sql->fetch(),
                // et on enregistre les données dans la session avec $_SESSION["..."]=...

                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['birthdate'] = $birthdate;
                $_SESSION['taille'] = $taille;
                $_SESSION['tel'] = $tel;
                $_SESSION['sexe'] = $sexe;
                $_SESSION['ville'] = $ville;
                $_SESSION['couleur'] = $couleur;
                $_SESSION['profilepic'] = $profilepic;
                $_SESSION['website'] = $website;
            }
            header("location:main.php")
            // ici,  rediriger vers la page main.php
        }
        $dbh = null;
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    $dbh = null;
    die();
}
?>