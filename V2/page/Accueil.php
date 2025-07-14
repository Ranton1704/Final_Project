<?php
require_once '../inc/function.php';
session_start();

$init = initialiser();
$conn = $init['conn'];
$categories = $init['categories'];
$filtre = $init['filtre'];
$objets = $init['objets'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Liste des Objets</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
    :root {
    --main-color: #ff3c3c;
    --bg-color: #0d0d0d;
    --card-color: #1a1a1a;
    --text-color: #ffffff;
    --text-muted: #cccccc;
    --input-border: #ff3c3c;
    --radius: 18px;
    --shadow: 0 0 30px rgba(255, 60, 60, 0.25);
    }

    body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, var(--bg-color), #1a1a1a);
    color: var(--text-color);
    padding: 30px 20px;
    min-height: 100vh;
    }
    </style>
    
</head>
<body>

<h1>Bienvenue <?= ($_SESSION['Nom']) ?> !</h1>
<p style="text-align:center;"><a href="../inc/deconnexion.php">Se déconnecter</a></p>

<form method="get" action="Accueil.php">
    <label>Filtrer par catégorie :</label>
    <select name="categorie">
        <option value="">-- Toutes --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre == $cat['id_categorie']) ? 'selected' : '' ?>>
                <?= ($cat['nom_categorie']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Filtrer">
</form>

<h2>Objets disponibles</h2>
<div class="container">
<?php if (empty($objets)): ?>
    <p>Aucun objet trouvé pour cette catégorie.</p>
<?php endif; ?>

<?php foreach ($objets as $obj): ?>
 <div class="card">
    <?php
    $stmtImg = mysqli_prepare($conn, "SELECT nom_image FROM Cat_images_objet WHERE id_objet = ?");
    mysqli_stmt_bind_param($stmtImg, 'i', $obj['id_objet']);
    mysqli_stmt_execute($stmtImg);
    $resImg = mysqli_stmt_get_result($stmtImg);
    $imageData = mysqli_fetch_assoc($resImg);
    $image = $imageData ? $imageData['nom_image'] : 'default.jpg';

    $stmtStatut = mysqli_prepare($conn, "SELECT date_retour FROM Cat_emprunt WHERE id_objet = ? ORDER BY id_emprunt DESC LIMIT 1");
    mysqli_stmt_bind_param($stmtStatut, 'i', $obj['id_objet']);
    mysqli_stmt_execute($stmtStatut);
    $resStatut = mysqli_stmt_get_result($stmtStatut);
    $statutData = mysqli_fetch_assoc($resStatut);

    $isEmprunte = ($statutData && $statutData['date_retour'] === null);

    $dateRetour = null;
    if ($isEmprunte) {
        $stmtDate = mysqli_prepare($conn, "SELECT date_retour FROM Cat_emprunt WHERE id_objet = ? AND date_retour IS NULL ORDER BY id_emprunt DESC LIMIT 1");
        mysqli_stmt_bind_param($stmtDate, 'i', $obj['id_objet']);
        mysqli_stmt_execute($stmtDate);
        $resDate = mysqli_stmt_get_result($stmtDate);
        $dataDate = mysqli_fetch_assoc($resDate);
        $dateRetour = $dataDate['date_retour'] ?? null;
    }
    ?>


    <img src="uploads/<?= ($image) ?>" alt="Objet">
    <h3><?= ($obj['nom_objet']) ?></h3>
    <p><strong>Catégorie :</strong> <?= ($obj['nom_categorie']) ?></p>
    <p><strong>Propriétaire :</strong> <?= ($obj['nom_proprietaire']) ?></p>

    <p class="statut <?= $isEmprunte ? 'emprunte' : 'disponible' ?>">
    <?= $isEmprunte ? 'Emprunté' : 'Disponible' ?>
    <?php if ($isEmprunte && $dateRetour): ?>
        <br><small>Date de retour : <?= date('d/m/Y', strtotime($dateRetour)) ?></small>
    <?php endif; ?>
    </p>

</div>

<?php endforeach; ?>
</div>

</body>
</html>
