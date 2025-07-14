<?php
    require("../inc/function.php");
    dbconnect();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-page">

<div class="login-box">
    <h1><i class="bi bi-pencil-square me-2"></i>Inscription</h1>

    <form action="../inc/registre.php" method="post">
        <div class="input-box">
            <label for="nom"><i class="bi bi-person-fill"></i> Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
        </div>
        <div class="input-box">
            <label for="email"><i class="bi bi-envelope-fill"></i> Email</label>
            <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
        </div>
        <div class="input-box">
            <label for="motdepasse"><i class="bi bi-lock-fill"></i> Mot de passe</label>
            <input type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe" required>
        </div>
        <div class="input-box">
            <label for="datedenaissance"><i class="bi bi-calendar-date-fill"></i> Date de naissance</label>
            <input type="date" id="datedenaissance" name="datedenaissance" required>
        </div>
        <button type="submit" class="btn-login">S'inscrire</button>
    </form>

    <div class="links">
        <p>Déjà un compte ? <a href="Login.php">Se connecter</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>