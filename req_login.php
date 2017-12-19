<?php

if(isset($_POST['login']) AND isset($_POST['password'])) {
    $email = $_POST['login'];
    $password = $_POST['password'];
    $dbh = new PDO('mysql:host=localhost;dbname=pictionnary', 'root', '');

    $sql = $dbh->query("SELECT * FROM users WHERE email ='".$email."' AND password = '".$password."'");

    session_start();

    $donnee = $sql->fetch();
    $_SESSION['email'] = $donnee['email'];
    $_SESSION['nom'] = $donnee['nom'];
    $_SESSION['prenom'] = $donnee['prenom'];
    $_SESSION['profilepic'] = $donnee['profilepic'];


    header("Location: main.php");
}

if(!isset($_SESSION['email']));
    header("Location: main.php?erreur=".urlencode("un problème est survenu"));


?>