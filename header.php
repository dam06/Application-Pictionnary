<?php

session_start();
//print_r($_SESSION);
if(isset($_SESSION['email'])) {
    $nom = $_SESSION['nom'];
    $profilepic = $_SESSION["profilepic"];
    echo " <br> Bonjour " . $nom . " ! <br> ";
    echo "<img src=\".$profilepic.\" alt=\"Photo de profil\" />  ";
    echo "<a class=\"nav-link\" href=\"logout.php\">Logout</a>";

}

else {
    echo " <form class=\"connection\" action=\"req_login.php\" method=\"post\" name=\"connection\" >
        
        <label for=\"login\">Login :</label>
        <input type=\"text\" name=\"login\" id=\"login\" required placeholder=\"Email\"/> <br>

        <label for=\"password\">Mot de passe :</label>
        <input type=\"text\" name=\"password\" id=\"password\" required placeholder=\"Mot de passe\"/> <br>
        
    <input type=\"submit\" value=\"Connexion\" > <br>
    </form>
 " ;
    echo "<a class=\"nav-link\" href=\"tp_continuer.html\">Inscription</a>";

}
?>