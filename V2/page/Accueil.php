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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

<div class="welcome">
  <h1>Bienvenue <?= ($_SESSION['Nom']) ?> !</h1>
<a class="btn-deconnexion" href="../inc/deconnexion.php">
  <i class="fas fa-sign-out-alt"></i> Se déconnecter
</a>
</div>

<form method="get" class="filtre-box">
  <label for="categorie">Filtrer par catégorie :</label>
  <select name="categorie" id="categorie">
      <option value="">-- Toutes --</option>
      <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre == $cat['id_categorie']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nom_categorie']) ?>
          </option>
      <?php endforeach; ?>
  </select>
  <button type="submit" class="btn-filtrer">Filtrer</button>
</form>


<div class="page-layout">

  <div class="formulaire-ajout">
   <h2>Ajouter une image par catégorie</h2>
<form action="ajouter_image_categorie.php" method="post" enctype="multipart/form-data">
  <!-- Sélection de la catégorie -->
  <label>Catégorie :</label><br>
  <select name="id_categorie" required>
    <?php foreach ($categories as $cat): ?>
      <option value="<?= $cat['id_categorie'] ?>"><?= $cat['nom_categorie'] ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <!-- Nom de l’image -->
  <label>Nom de l’image (ex : rasoir, perceuse...):</label><br>
  <input type="text" name="nom_image_custom" placeholder="Entrez un nom descriptif" required><br><br>

  <!-- Fichier image -->
  <label>Image :</label><br>
  <input type="file" name="image" accept="image/*" required><br><br>

  <!-- Bouton d’envoi -->
  <input type="submit" value="Ajouter l’image">
</form>

  </div>


<div class="liste-objets">
<h2>Objets disponibles</h2>
<div class="container">
<?php if (empty($objets)): ?>
    <p>Aucun objet trouvé pour cette catégorie.</p>
<?php endif; ?>

<?php foreach ($objets as $obj): ?>
 <div class="card">
    <?php
    $stmt = mysqli_prepare($conn, "SELECT nom_image FROM Cat_images_categorie WHERE id_categorie = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $cat['id_categorie']);
mysqli_stmt_execute($stmt);
$resImg = mysqli_stmt_get_result($stmt);
$image = mysqli_fetch_assoc($resImg);

if ($image) {
    echo '<img src="../uploads/' . ($image['fichier_image']) . '" alt="' . ($image['nom_image']) . '">';
}



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


    <img src="../uploads/<?= ($image) ?>" alt="Objet">
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
  </div>
</body>
</html>
