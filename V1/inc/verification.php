<?php
session_start(); 
require("connection.php");
if (dbconnect()) {
    if (isset($_POST['email']) && isset($_POST['motdepasse'])) {
        $email = $_POST['email'];
        $motdepasse = $_POST['motdepasse'];

        
        $requete = sprintf(
            "SELECT id_Membre, Nom FROM Cat_Membres WHERE email = '%s' AND mdp = '%s'",
            mysqli_real_escape_string(dbconnect(), $email),
            mysqli_real_escape_string(dbconnect(), $motdepasse)
        );

        $result = mysqli_query(dbconnect(), $requete);
        if ($result && mysqli_num_rows($result) > 0) {
            
            $user = mysqli_fetch_assoc($result);
            $_SESSION['id_Membre'] = $user['id_Membre'];
            $_SESSION['Nom'] = $user['Nom'];
            header("Location: ../page/Accueil.php");
            exit();
        } else {
            
            header("Location: ../page/Login.php?error=Email ou mot de passe incorrect");
            exit();
        }
    } else {
        header("Location: ../page/Login.php?error=Veuillez remplir tous les champs");
        exit();
    }
    mysqli_close(dbconnect());
} else {
    header("Location: ../page/Login.php?error=Échec de la connexion à la base de données");
    exit();
}
?>