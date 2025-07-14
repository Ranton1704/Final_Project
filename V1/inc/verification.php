<?php
session_start(); 
require("function.php");

$conn = dbconnect(); 

if ($conn) {
    if (!empty($_POST['email']) && !empty($_POST['motdepasse'])) {
        $email = $_POST['email'];
        $motdepasse = $_POST['motdepasse'];

        $stmt = mysqli_prepare($conn, "SELECT id_Membre, Nom FROM Cat_Membres WHERE email = ? AND mdp = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email, $motdepasse);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['id_membre'] = $user['id_Membre'];
            $_SESSION['nom'] = $user['Nom'];
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
    mysqli_close($conn);
} else {
    header("Location: ../page/Login.php?error=Échec de la connexion à la base de données");
    exit();
}
?>
