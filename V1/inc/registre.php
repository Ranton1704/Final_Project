<?php
require("connection.php");
if (dbconnect()) {
    if (isset($_POST['email']) && isset($_POST['motdepasse']) && isset($_POST['nom']) && isset($_POST['datedenaissance'])) {
        $email = $_POST['email'];
        $motdepasse = $_POST['motdepasse'];
        $nom = $_POST['nom'];
        $datedenaissance = $_POST['datedenaissance'];

        $requete = sprintf(
            "INSERT INTO Cat_Membres (email, mdp, Nom, date_de_naissance) VALUES ('%s', '%s', '%s', '%s')",
            mysqli_real_escape_string(dbconnect(), $email),
            mysqli_real_escape_string(dbconnect(), $motdepasse),
            mysqli_real_escape_string(dbconnect(), $nom),
            mysqli_real_escape_string(dbconnect(), $datedenaissance)
        );

        if (mysqli_query(dbconnect(), $requete)) {
            header("Location: ../page/Login.php");
        }
    }
    mysqli_close(dbconnect());
} else {
    echo 'échec';
}
?>